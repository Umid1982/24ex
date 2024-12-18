<?php

class AuthModel extends Model
	{
	protected $rights_mask = CAN_UNAUTH;

	function vars()
		{
		if ($this->isAuth()) goRedir('index',true);
		}

	function ajax()
		{
		if (@$_GET['ajax']=='loginAdmin')
			{
			$login = trim(@$_POST['login']);
			$pass = trim(@$_POST['pass']);

			if ($login=='' || $pass=='')
				{
				$this->ajax_return['info'] = 'Заполните логин и пароль!';
				}
			else
				{
				$admin_data = $this->db->uni_select_one('admins',['admin_login'=>$login]);
				if ($admin_data===false)
					{
					$this->ajax_return['info'] = 'Неверный логин и/или пароль';
					}
				else if ($admin_data['admin_lock']==1)
					{
					$this->ajax_return['info'] = 'Аккаунт заблокирован!';
					}
				else
					{
					$hash = sha1($pass.$admin_data['admin_salt']);
					if ($hash!=$admin_data['admin_hash'])
						{
						$this->ajax_return['info'] = 'Неверный логин и/или пароль';

						$this->db->logWrite(LOG_LOGIN_WRONG_PASS,'','',ACC_ADMIN,$admin_data['admin_id']);
						}
					else
						{
						$_SESSION['coincash_admin_auth'] = true;
						$_SESSION['coincash_admin_id'] = $admin_data['admin_id'];
						$_SESSION['coincash_admin_login'] = $admin_data['admin_login'];

						switch ($admin_data['admin_type'])
							{
							case AT_SUPER: $rights = R_SUPER; break;
							case AT_ADMIN: $rights = R_ADMIN; break;
							case AT_MODER: $rights = R_MODER; break;
							}
						$_SESSION['coincash_admin_rights'] = $rights;

						$this->db->logWrite(LOG_LOGIN,'','',ACC_ADMIN,$admin_data['admin_id']);
						$this->db->uni_update('admins',['admin_id'=>$this->adminId()],['admin_need_logout'=>0]);

						$this->ajax_return['adminRedirTo'] = isset($_SESSION['adminRedirTo']) ? $_SESSION['adminRedirTo'] : '';

						$this->ajax_return['result'] = true;
						}
					}
				}
			}
		}

	}