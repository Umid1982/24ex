<?php

class LogsModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function vars()
		{
		if ($this->adminRights() & CAN_SUPER) $this->all_vars['all_admins'] = $this->db->uni_select('admins');
		$this->all_vars['all_users'] = $this->db->uni_select('users');

		$this->all_vars['now_acc'] = trim(@$_GET['account']);

		$acc_line = '';
		$acc_search_line = '';
		if (@$_GET['account']!='')
			{
			$temp = explode('_',$_GET['account']);
			if (count($temp)!=2 || !in_array($temp[0],['admin','user'])) goRedir('logs',true);
			if (!($this->adminRights() & CAN_SUPER) && $temp[0]=='admin') goRedir('logs',true);

			if ($temp[0]=='admin')
				{
				$acc_data = $this->db->uni_select_one('admins',['admin_id'=>(int)$temp[1]]);
				if ($acc_data===false) goRedir('logs',true);
				$acc_line = ' &rarr; [ADMIN] '.$acc_data['admin_login'];
				$acc_search_line = $acc_data['admin_login'];
				}
			else
				{
				$acc_data = $this->db->uni_select_one('users',['user_id'=>(int)$temp[1]]);
				if ($acc_data===false) $acc_line = ' &rarr; Не авторизованные';
				else $acc_line = ' &rarr; [USER] '.$acc_data['user_email'];
				$acc_search_line = $acc_data['user_email'];
				}
			}

		$this->all_vars['acc_search_line'] = $acc_search_line;
		$this->all_vars['acc_line'] = $acc_line;

		$this->all_vars['all_log_types'] = getAllLogTypes();
		}

	function ajax()
		{
		global $config;

		if ($_GET['ajax']=='accSearch')
			{
			$s = trim(@$_GET['term']);
			$ret = [];
			$ret[] = ['id'=>'','label'=>'Все логи'];
			$ret[] = ['id'=>'user_0','label'=>'Не авторизованные'];
			$ret[] = ['id'=>'sep_1','label'=>'-'];

			if ($s!='')
				{
				$admins = $this->db->adminsSearch($s,10);
				if (count($admins)>0) foreach ($admins as $one) $ret[] = $one;

				$users = $this->db->usersSearch($s,10);
				if (count($users)>0) foreach ($users as $one) $ret[] = $one;
				}

			$this->ajax_return = $ret;
			}

		if ($_GET['ajax']=='getLogs')
			{
			$this->ajax_sort = ['field'=>'log_dt','sort'=>'desc'];

			$sel = [];
			if (@$this->ajax_qs['log_type']!='') $sel['log_type'] = (int)$this->ajax_qs['log_type'];

			$acc = trim(@$_GET['account']);
			if ($acc==='') // my logs
				{
				$logs = $this->db->uni_select('logs',$sel,['log_dt'=>'DESC'],false,(($this->ajax_pg-1)*$this->ajax_pp),$this->ajax_pp);
				}
			else
				{
				$temp = explode('_',$acc);
				if (count($temp)!=2 || !in_array($temp[0],['admin','user'])) goRedir('logs',true);
				if (!($this->adminRights() & CAN_SUPER) && $temp[0]=='admin') goRedir('logs',true);

				$acc_type = $temp[0]=='admin' ? ACC_ADMIN : ACC_USER;
				$acc_id = (int)$temp[1];

				if ($acc_id==0) $acc_id = null;
				if ($acc_id==null) $acc_type = ACC_UNAUTH;

				$sel['acc_id'] = $acc_id;
				$sel['acc_type'] = $acc_type;

				$logs = $this->db->uni_select('logs',$sel,['log_dt'=>'DESC'],false,(($this->ajax_pg-1)*$this->ajax_pp),$this->ajax_pp);
				}

			//$logs = $this->db->uni_select('logs',['acc_id'=>$acc_id,'acc_type'=>$acc_type],['log_dt'=>'DESC'],false,(($this->ajax_pg-1)*$this->ajax_pp),$this->ajax_pp);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			$accs = [];

			foreach ($logs as $k=>$v)
				{
				$logs[$k]['log_dt_line'] = date('d.m.Y',$v['log_dt']);
				$logs[$k]['log_time_line'] = date('H:i:s',$v['log_dt']);
				$logs[$k]['log_type_line'] = logText($v['log_type'],$v['log_data_old'],$v['log_data_new']);

				$kk = $v['acc_type'].'_'.$v['acc_id'];
				if (!isset($accs[$kk])) $accs[$kk] = $this->db->getAcc($v['acc_type'],$v['acc_id']);

				$logs[$k]['acc_line'] = $v['acc_type']==ACC_UNAUTH ? 'не авторизованный' : 
						'<a href="/'.$config['site']['admin_path'].'/?page=logs&account='.$accs[$kk]['acc_param'].'">'.
							$accs[$kk]['acc_mark'].' '.$accs[$kk]['acc_name'].'</a>';
				}

			$this->ajax_return['data'] = $logs;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}
		}
	}