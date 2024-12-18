<?php

class Model
	{
	protected $pss;
	protected $db;
	protected $alert;
	protected $all_vars;
	protected $user_data;
	protected $page;
	protected $ajax_return = ['result'=>false,'info'=>'Unknown error'];
	protected $ajax_marker = false;

	protected $ajax_pg = 1;
	protected $ajax_pp = PER_PAGE;
	protected $ajax_sort = [];
	protected $ajax_qs = [];

	public function __construct($db, $page, $pss)
		{
		if (!$this->checkRights())
			{
			if ($this->isAuth()) goRedir('index',true);
			else
				{
				$_SESSION['adminRedirTo'] = $_SERVER['REQUEST_URI'];
				goRedir('auth/index',true);
				}
			}	

		$this->db = $db;
		$this->pss = $pss;
		$this->alert = false;
		$this->all_vars = [];
		$this->admin_data = $this->db->uni_select_one('admins',['admin_id'=>$this->adminId()]);
		$this->page = $page;


		if ($this->isAuth())
			{
			if ($this->admin_data['admin_need_logout']==1 && @$_GET['action']!='logout')
				{
				goRedir('index&action=logout',true);
				}
			}
		}

	public function getVars()
		{
		global $config;

		if (!$this->isAuth() && $this->page!='auth/index') goRedir('auth/index',true);

		// global vars
		$this->all_vars['tg_bot'] = $config['tg']['bot'];

		$this->all_vars['server_time'] = time();
		$this->all_vars['get_page'] = $this->page;
		$this->all_vars['now_page'] = '/'.$config['site']['admin_path'].'/?'.adminUrlParams();
		$this->all_vars['ajax_url'] = '/'.$config['site']['admin_path'].'/?page='.$this->page.'&ajax';
		$this->all_vars['adm_path'] = $config['site']['admin_path'];

		$this->all_vars['paging_now'] = isset($_GET['pg']) ? (int)$_GET['pg'] : 1;
		$this->all_vars['paging_max'] = 1;

		$this->all_vars['now_time'] = time();

		$this->all_vars['all_langs'] = $config['lang']['list'];

		// alert
		$this->all_vars['alert'] = $this->alert;

		// feedbacks
		$this->all_vars['feedbacks_count'] = $this->db->uni_count('sup_msgs',['sup_msg_ans_mark'=>0]);

		// user_data
		if ($this->isAuth())
			{
			$this->all_vars['admin_login'] = $this->admin_data['admin_login'];
			$this->all_vars['admin_type']  = adminTypeText($this->admin_data['admin_type']);
			$this->all_vars['admin_rights'] = $this->adminRights();

			// logs
			$logs = $this->db->uni_select('logs',['acc_type'=>ACC_ADMIN,'acc_id'=>$this->adminId()],['log_dt'=>'DESC'],false,0,10);
			$this->all_vars['admin_logs'] =  [];
			foreach ($logs as $one_log)
				{
				$one_log['dt_line'] = ( date('d.m.Y') == date('d.m.Y',$one_log['log_dt']) ) ? date('H:i',$one_log['log_dt']) : date('d.m',$one_log['log_dt']);
				$one_log['text'] = logText($one_log['log_type'],$one_log['log_data_old'],$one_log['log_data_new']);
				$this->all_vars['admin_logs'][] = $one_log;
				}
			}

		$this->all_vars['search'] = @$_GET['search'];

		// pages vars
		if (method_exists($this,'vars')) $this->vars();
		if (method_exists($this,'vars2')) $this->vars2();

		return $this->all_vars;
		}

	public function doActions()
		{
		global $config;

		$do_actions = false;

		if (isset($_GET['ajax']))
			{
			$this->ajax_marker = true;
			
			// TABLE PAGINATION
			if (isset($_POST['pagination']))
				{
				$this->ajax_pg = (int)$_POST['pagination']['page'];
				$this->ajax_pp = (int)$_POST['pagination']['perpage'];
				}

			// TABLE SORTINGS
			if (isset($_POST['sort']))
				{
				$this->ajax_sort = $_POST['sort'];
				}

			// TABLE GENERAL SEARCH
			if (isset($_POST['query']) && is_array($_POST['query']))
				{
				foreach ($_POST['query'] as $k=>$v) $this->ajax_qs[$k] = trim($v);
				}

			// GLOBAL AJAX

			// LOCK IP
			if ($_GET['ajax']=='lockIp')
				{
				$ip = trim(htmlspecialchars($_POST['ip']));
				if ($ip!='' && $ip!='UNKNOWN')
					{
					$ips = $this->db->setGet('ips_lock_list');
					if (array_search($ip, $ips)===false)
						{
						$ips[] = $ip;
						$this->db->setSet('ips_lock_list',json_encode($ips));
						$this->db->logWrite(LOG_ONE_IP_LOCK,'',$ip,ACC_ADMIN,$this->adminId());

						$this->ajax_return['result'] = true;
						}
					else
						{
						$this->ajax_return['info'] = 'IP уже заблокирован';
						}
					}
				else
					{
					$this->ajax_return['info'] = 'Некорректный IP';
					}
				}

			// REF PLANS
			if ($_GET['ajax']=='getAllRefPlans')
				{
				$refplans = $this->db->uni_select('ref_plans');
				if (count($refplans)>0)
					{
					foreach ($refplans as $k=>$v)
						{
						$refplans[$k]['rp_id'] = (int)$v['rp_id'];
						$refplans[$k]['prcs_line'] = '% '.implode('<span style="color:#aaa">-</span>',json_decode($v['rp_prcs'],true));
						}

					$this->ajax_return['result'] = true;
					$this->ajax_return['refplans'] = $refplans;
					}
				}

			// ALERTS
			if ($_GET['ajax']=='getNewAlerts')
				{
				$this->ajax_return['result'] = true;
				$this->ajax_return['nowtm'] = time();
				$this->ajax_return['alerts'] = [];

				$from = (int)$_POST['from'];
				$alerts = $this->db->getNewAlerts($from,$this->admin_data['admin_type']);
				if (count($alerts)>0)
					{
					$this->ajax_return['alerts'] = $alerts;
					}
				}

			// INVEST LOGS
			if ($_GET['ajax']=='getIls')
				{
				$ui_id = (int)@$this->ajax_qs['ui_id'];

				$lis = $this->db->uni_select('invest_logs',['ui_id'=>$ui_id]);
				$total = $this->db->last_count();
				$pg_max = ceil($total / $this->ajax_pp);

				if (count($lis)>0) foreach ($lis as $k=>$v)
					{
					$lis[$k]['il_dt_line'] = date('d.m.y H:i',$v['il_dt']);
					$lis[$k]['il_type_line'] = getInvestLogTypeLine($v['il_type']);
					$lis[$k]['il_val'] = cutZeros($v['il_val']);
					}

				$this->ajax_return['data'] = $lis;
				$this->ajax_return['meta'] = [
										        "page" => $this->ajax_pg,
										        "pages" => $pg_max,
										        "perpage" => $this->ajax_pp,
										        "total" => $total,
										        "sort" => @$this->ajax_sort['sort'],
										        "field" => @$this->ajax_sort['field']
											 ];				
				}

			// USERS SEARCH FOR AUTOCOMPLETE
			if ($_GET['ajax']=='userSearchAutocomplete')
				{
				$s = trim(@$_GET['term']);

				$ret = [];
				if ($s!='') $ret = $this->db->usersSearch($s,10);

				$this->ajax_return = $ret;
				}

			// MERCHANT SEARCH FOR AUTOCOMPLETE
			if ($_GET['ajax']=='merchantSearchAutocomplete')
				{
				$s = trim(@$_GET['term']);

				$ret = [];
				if ($s!='') $ret = $this->db->merchantsSearch($s,10);

				$this->ajax_return = $ret;
				}

			if (method_exists($this,'ajax')) $this->ajax();
			if (method_exists($this,'ajax2')) $this->ajax2();			
			}
		else
			{
			if (isset($_POST['action'])) // ВСЕ ПОСТ ЭКШЕНЫ ПОД ЗАЩИТОЙ
				{
				if (@$_POST['action_hash']==$_SESSION['action_hash'])
					{
					$do_actions = true;
					}
				}
			else if (isset($_GET['action'])) // GET ЗАПРОСЫ НЕ ЗАЩИЩАЕМ
				{
				$do_actions = true;
				}

			// для тестов заглушка
			$do_actions = true;

			if (@$_GET['action']=='logout')
				{
				$this->db->logWrite(LOG_LOGOUT,'','',ACC_ADMIN,$_SESSION['coincash_admin_id']);

				unset($_SESSION['coincash_admin_auth']);
				unset($_SESSION['coincash_admin_id']);
				unset($_SESSION['coincash_admin_login']);
				unset($_SESSION['coincash_admin_rights']);

				goRedir('auth/index',true);
				}

			if ($do_actions)
				{
				// pages action
				if (method_exists($this,'actions')) $this->actions();
				if (method_exists($this,'actions2')) $this->actions2();
				}
			}
		}

	// AUTH DATA

	protected function isAuth()
		{
		return isset($_SESSION['coincash_admin_auth']);
		}

	protected function adminId()
		{
		return (!isset($_SESSION['coincash_admin_id'])) ? 0 : $_SESSION['coincash_admin_id'];
		}

	// FOR AJAX

	public function isAjax()
		{
		return $this->ajax_marker;
		}	

	public function getAjaxVars()
		{
		return $this->ajax_return;
		}

	public function adminRights()
		{
		return $_SESSION['coincash_admin_rights'];
		}

	public function checkRights()
		{
		$admin_mask = isset($_SESSION['coincash_admin_rights']) ? $_SESSION['coincash_admin_rights'] : R_UNAUTH;
		$page_mask = $this->rights_mask;

		$ret = $admin_mask & $page_mask;

		return $ret;
		}

	public function genUserLine($user_id)
		{
		global $config;

		if ($user_id==0)
			{
			$uline = 'не авторизованный';
			}
		else
			{
			$user_data = $this->db->uni_select_one('users',['user_id'=>$user_id]);
			if ($user_data===false) $uline = 'пользователь не найден';
			else $uline = 'ID:'.$user_id.' <a href="/'.$config['site']['admin_path'].'/?page=users&search='.urlencode($user_data['user_email']).
							'">'.$user_data['user_email'].'</a>';
			}

		return $uline;
		}

	public function getAllPss()
		{
		$pss_all = [];
		if (count($this->pss)>0) foreach ($this->pss as $ps_name=>$one_ps)
			{
			$data = $one_ps->getInfo();
			$pss_all[] = ['name'=>$ps_name,'title'=>$data['title']];
			}
		return $pss_all;		
		}

	}