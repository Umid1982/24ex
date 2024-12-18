<?php

class Model
	{
	protected $pss;
	protected $db = false;
	protected $alert;
	protected $all_vars = [];
	protected $user_data = [];
	protected $page = '';
	protected $ajax_return = ['result'=>false,'info'=>'Unknown error'];
	protected $ajax_marker = false;
	protected $ajax_sort = [];
	protected $ajax_qs = [];

	public function __construct($db, $page, $pss)
		{
		$this->db = $db;

		// check IP lock
		$ips = $this->db->setGet('ips_lock_list');
		if ($ips!==false)
			{
			$ip = getClientIp();
			if (in_array($ip, $ips))
				{
				header('HTTP/1.0 403 Forbidden');
				die();
				}
			}

		$this->pss = $pss;
		$this->alert = false;
		$this->all_vars = [];
		$this->user_data = $this->db->uni_select_one('users',['user_id'=>$this->userId()]);
		$this->page = $page;

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
			}

		if (isset($_GET['reflink']))
			{
			$_SESSION['sponsor_id'] = (int)$_GET['reflink'];
			}
		}

	public function getVars()
		{
		global $config;

		// global vars
		$this->all_vars['server_time'] = time();
		$this->all_vars['get_page'] = $this->page;
		$this->all_vars['tg_bot'] = $config['tg']['bot'];
		$this->all_vars['site_url'] = $config['site']['url'];

		// alert
		$this->all_vars['alert'] = $this->alert;

		// lang pages
		$short_page = preg_replace('#index$#si','',$this->page);
		$short_page = rtrim($short_page,'/').'/';
		$this->all_vars['lang'] = _LANG_;
		$this->all_vars['current_page'] = '/'._LANG_.'/'.$short_page;
		$this->all_vars['current_page_ru'] = '/ru/'.$short_page;
		$this->all_vars['current_page_en'] = '/en/'.$short_page;

		$this->all_vars['ajax_url'] = '/'._LANG_.'/'.$this->page.'?ajax';

		// pages vars
		if (method_exists($this,'vars')) $this->vars();
		if (method_exists($this,'vars2')) $this->vars2();

		return $this->all_vars;
		}

	public function doAjax()
		{
		$this->ajax_marker = true;
		if (method_exists($this,'ajax')) $this->ajax();
		if (method_exists($this,'ajax2')) $this->ajax2();			
		}

	public function doActions()
		{
		$do_actions = false;

		/*
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
		*/
		// для тестов заглушка
		$do_actions = true;

		if ($do_actions)
			{
			// pages action
			if (method_exists($this,'actions')) $this->actions();
			if (method_exists($this,'actions2')) $this->actions2();
			}
		}

	protected function isAuth()
		{
		return isset($_SESSION['coincash_auth']);
		}

	protected function userId()
		{
		return (!isset($_SESSION['coincash_user_id'])) ? 0 : $_SESSION['coincash_user_id'];
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

	}