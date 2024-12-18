<?php

class AdminsModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function ajax()
		{

		if ($_GET['ajax']=='getAdmins')
			{
			$admins = $this->db->getAdmins($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($admins as $k=>$v)
				{
				$admins[$k]['admin_last_action'] = date('d.m.Y H:i',$v['admin_last_action']);
				$admins[$k]['admin_type'] = adminTypeText($v['admin_type']);
				}

			$this->ajax_return['data'] = $admins;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if (@$_GET['ajax']=='deleteAdmin')
			{
			$admin_id = (int)@$_POST['admin_id'];
			if ($admin_id==$this->adminId())
				{
				$this->ajax_return['info'] = 'Нельзя удалить себя';
				}
			else
				{
				$admin_data = $this->db->uni_select_one('admins',['admin_id'=>$admin_id]);

				$this->db->uni_delete('admins',['admin_id'=>$admin_id]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_DELETE_ADMIN,$admin_data['admin_login'],'',ACC_ADMIN,$this->adminId());
				}
			}

		if (@$_GET['ajax']=='getAdminData')
			{
			$admin_id = (int)@$_POST['admin_id'];

			$admin_data = $this->db->uni_select_one('admins',['admin_id'=>$admin_id]);
			if ($admin_data!==false)
				{
				$this->ajax_return['result'] = true;
				$this->ajax_return['admin_login'] = $admin_data['admin_login'];
				$this->ajax_return['admin_type'] = $admin_data['admin_type'];
				$this->ajax_return['admin_id'] = $admin_data['admin_id'];
				$this->ajax_return['admin_lock'] = $admin_data['admin_lock'];
				}
			}

		if (@$_GET['ajax']=='saveAdminData')
			{
			$admin_id = (int)@$_POST['admin_id'];
			$admin_login = htmlspecialchars(@$_POST['admin_login']);
			$admin_type = (int)@$_POST['admin_type'];
			$admin_lock = (int)isset($_POST['admin_lock']);

			$this->ajax_return['admin_login'] = $admin_login;
			$this->ajax_return['admin_type_line'] = adminTypeText($admin_type);

			$new_pass = @$_POST['admin_new_pass'];

			if ($admin_login=='')
				{
				$this->ajax_return['info'] = 'Имя обязательно!';
				}
			else if (strlen($new_pass)<6 && $new_pass!='')
				{
				$this->ajax_return['info'] = 'Новый пароль слишком короткий!';
				}
			else if ($this->db->uni_select_one('admins',['admin_id'=>['eq'=>'<>','val'=>$admin_id],'admin_login'=>$admin_login])!==false)
				{
				$this->ajax_return['info'] = 'Данное имя админа занято!';
				}
			else
				{
				$upd = [
						'admin_login' => $admin_login,
						'admin_type' => $admin_type,
						'admin_need_logout' => ($this->adminId()==$admin_id) ? 0 : 1,
						'admin_lock' => ($this->adminId()==$admin_id) ? 0 : $admin_lock,
						];

				if ($new_pass!='')
					{
					$salt = genPass(5,5);
					$hash = sha1($new_pass.$salt);

					$upd['admin_salt'] = $salt;
					$upd['admin_hash'] = $hash;
					}


				if ($admin_id==0) // NEW ADMIN
					{
					if (!isset($upd['admin_hash']))
						{
						$this->ajax_return['info'] = 'Введите пароль!';
						}
					else
						{
						$new_admin_id = $this->db->uni_insert('admins',$upd);
						$this->ajax_return['result'] = true;
						$this->ajax_return['is_new'] = true;
						$this->ajax_return['admin_id'] = $new_admin_id;

						$this->db->logWrite(LOG_NEW_ADMIN,'',$admin_login,ACC_ADMIN,$this->adminId());
						}
					}
				else // UPDATE
					{
					$old_data = $this->db->uni_select_one('admins',['admin_id'=>$admin_id]);

					$this->db->uni_update('admins',['admin_id'=>$admin_id],$upd);
					$this->ajax_return['result'] = true;
					$this->ajax_return['admin_id'] = $admin_id;

					$new_data = $this->db->uni_select_one('admins',['admin_id'=>$admin_id]);

					$this->db->logWrite(LOG_UPD_ADMIN,arrToLines($old_data),arrToLines($new_data),ACC_ADMIN,$this->adminId());
					}
				}
			}
		}

	}