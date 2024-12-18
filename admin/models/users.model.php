<?php

class UsersModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function ajax()
		{
		if ($_GET['ajax']=='getUsers')
			{
			$users = $this->db->getUsers($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($users as $k=>$v)
				{
				$users[$k]['user_last_action'] = timeElapsedString(time()-$v['user_last_action']);
				$users[$k]['user_online'] = (($v['user_last_action'] + 5*60)>time()) ? true : false;
				$users[$k]['user_tg'] = $v['user_tg']=='' ? '-' : '@'.$v['user_tg'];
				$users[$k]['user_dt_reg'] = preg_replace('#[\s]+.+?$#si', '', $v['user_dt_reg']);
				$users[$k]['user_api_rules'] = $v['user_api_rules']=='' ? [] : json_decode($v['user_api_rules']);
				}

			$this->ajax_return['data'] = $users;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if ($_GET['ajax']=='getUbs')
			{
			$user_id = $this->ajax_qs['user_id'];

			$bal_types = $this->db->getBalTypes();
			$ubs = $this->db->getUBs($user_id,$bal_types,$this->ajax_pg,$this->ajax_pp);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			if (count($ubs)>0) foreach ($ubs as $k=>$v)
				{
				$ubs[$k]['ub_value'] = cutZeros($v['ub_value']);
				}	

			$this->ajax_return['data'] = $ubs;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];

			}

		if ($_GET['ajax']=='saveUser')
			{
			$user_id = (int)$_POST['user_id'];

			$user_data = $this->db->uni_select_one('users',['user_id'=>$user_id]);
			if ($user_data===false)
				{
				$this->ajax_return['info'] = 'Пользователь не найден';
				}
			else
				{
				$user_lock = (int)isset($_POST['user_lock']);
				$user_lock_date = htmlspecialchars($_POST['user_lock_date']);

				$upd = ['user_lock'=>$user_lock,'user_lock_date'=>$user_lock_date];

				if ($user_data['user_lock']!=$user_lock)
					{
					if ($user_lock==1 )
						{
						$upd['user_need_logout'] = 1;
						$this->db->logWrite(LOG_USER_LOCK,$user_id,'',ACC_ADMIN,$this->adminId());
						}
					else
						{
						$this->db->logWrite(LOG_USER_UNLOCK,$user_id,'',ACC_ADMIN,$this->adminId());
						}
					}

				$upd['user_deposit_plan'] = @$_POST['deposit_plan']=='' ? NULL : (int)$_POST['deposit_plan'];
				$upd['user_invest_plan_depo'] = @$_POST['invest_plan_depo']=='' ? NULL : (int)$_POST['invest_plan_depo'];
				$upd['user_invest_plan_proc'] = @$_POST['invest_plan_proc']=='' ? NULL : (int)$_POST['invest_plan_proc'];

				$api_rules = [];
				if (isset($_POST['user_api_rules']))
					{
					foreach ($_POST['user_api_rules'] as $one_rule=>$trash)
						{
						$api_rules[$one_rule] = 1;
						}
					}
				$upd['user_api_rules'] = json_encode($api_rules);

				$this->db->uni_update('users',['user_id'=>$user_id],$upd);
				$this->ajax_return['result'] = true;
				}
			}

		if ($_GET['ajax']=='resetPaycode')
			{
			$user_id = (int)$_POST['user_id'];

			$user_data = $this->db->uni_select_one('users',['user_id'=>$user_id]);
			if ($user_data===false)
				{
				$this->ajax_return['info'] = 'Пользователь не найден';
				}
			else if ($user_data['user_payhash']=='')
				{
				$this->ajax_return['info'] = 'Платежный пароль не установлен';
				}
			else
				{
				$this->ajax_return['result'] = true;
				$upd = ['user_payhash'=>'','user_paysalt'=>''];
				$this->db->uni_update('users',['user_id'=>$user_id],$upd);
				$this->db->logWrite(LOG_USER_RESET_PAYCODE,$user_id,'',ACC_ADMIN,$this->adminId());
				}			
			}

		if ($_GET['ajax']=='reset2FA')
			{
			$user_id = (int)$_POST['user_id'];

			$user_data = $this->db->uni_select_one('users',['user_id'=>$user_id]);
			if ($user_data===false)
				{
				$this->ajax_return['info'] = 'Пользователь не найден';
				}
			else if ($user_data['user_2fa']==0)
				{
				$this->ajax_return['info'] = 'Двухфакторная авторизация отключена';
				}
			else
				{
				$this->ajax_return['result'] = true;
				$upd = ['user_2fa'=>0];
				$this->db->uni_update('users',['user_id'=>$user_id],$upd);
				$this->db->logWrite(LOG_USER_RESET_2FA,$user_id,'',ACC_ADMIN,$this->adminId());
				}			
			}

		if ($_GET['ajax']=='lockUB')
			{
			$ub_id = (int)$_POST['ub_id'];
			$lock = (int)$_POST['lock'];

			$ub_data = $this->db->uni_select_one('users_bals',['ub_id'=>$ub_id]);
			if ($ub_data===false)
				{
				$this->ajax_return['info'] = 'Баланс пользователя не найден';
				}
			else
				{
				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ub_data['bal_id']]);

				$this->ajax_return['result'] = true;
				$this->ajax_return['lock'] = $lock;

				$upd = ['ub_lock'=>$lock];
				$this->db->uni_update('users_bals',['ub_id'=>$ub_id],$upd);
				$this->db->logWrite(LOG_UB_CHANGE_LOCK, ('USER ID:'.$ub_data['user_id'].', BAL:'.$bal_data['bal_name']), $lock, ACC_ADMIN,$this->adminId());
				}			
			}
		}

	}