<?php

class OfficeCabinetModel extends OfficeModel
	{

	function vars2()
		{
		$user_data = $this->user_data;
		if ($user_data===false) goRedir('/auth/');

		$fields = [
					'user_email',
					'user_fname',
					'user_sname',
					'user_lname',
					'user_birth_date',
					'user_birth_city',
					'user_address',
					'user_postcode',
					'user_avatar',
					];

		foreach ($fields as $one_field) $this->all_vars[$one_field] = $user_data[$one_field];
		$this->all_vars['user_avatar'] = getUserAvatar($user_data['user_avatar']);

		$GA = new GA();
		$this->all_vars['image_2fa'] = $GA->getQRLink($this->user_data['user_2fa_secret']);
		$this->all_vars['need_change_pass'] = isset($_SESSION['coincash_user_need_change_pass']);

		$ass = $this->db->getASS(ACC_USER,$this->userId());
		if (count($ass)>0) foreach ($ass as $k=>$v) $this->all_vars['tga'][$k] = $v;
		}

	function ajax2()
		{
		// СМЕНА ПАРОЛЯ
		if (@$_GET['ajax']=='setNewPass')
			{
			$old_pass = @$_POST['old_pass'];
			$pass1 = @$_POST['pass1'];
			$pass2 = @$_POST['pass2'];

			$error = false;

			// проверяем старый пароль если не запрашивали восстановление
			if (!isset($_SESSION['coincash_user_need_change_pass'])) // проверяем старый пароль
				{
				$hash = sha1($old_pass.$this->user_data['user_salt']);
				if ($hash!=$this->user_data['user_hash'])
					{
					$error = true;
					$mess = 'WRONG_OLD_PASS';
					}
				}

			// проверяем корректность паролей / ошибки берем с регистрации
			if (!$error)
				{
				if (strlen($pass1)<6)
					{
					$error = true;
					$mess = 'REG_SHORT_PASS';
					}
				else if ($pass1 != $pass2)
					{
					$error = true;
					$mess = 'REG_WRONG_PASS_CONFIRM';
					}
				}

			if ($error)
				{
				$this->ajax_return['info'] = '[L:'.$mess.']';
				}
			else
				{
				$salt = genPass(5,5);
				$hash = sha1($pass1.$salt);

				$this->db->uni_update('users',['user_id'=>$this->userId()],['user_hash' => $hash,'user_salt' => $salt]);

				unset($_SESSION['coincash_user_need_change_pass']);
				$this->db->logWrite(LOG_PASS_CHANGE,'','',ACC_USER,$this->userId());

				$this->ajax_return['result'] = true;
				}

			}

		//  СОХРАНИТЬ ЛИЧНЫЕ ДАННЫЕ
		if ($_GET['ajax']=='saveUData')
			{
			if (isset($_SESSION['coincash_user_need_change_pass'])) return false;

			$file_ok = false;

			if (@$_POST['profile_avatar_remove']!=1)
				{ 
				$file = $_FILES['profile_avatar']; //var_dump($file);
				if ($file['error']==0)
					{
					$fn = date('His').'_'.$file['name'];
					$mime = mime_content_type($file['tmp_name']);
					$fld = getUploadDesc();

					$fend = preg_replace('#^.+\.([^\.]+$)#si', '$1', $fn);

					if (!in_array($mime,['image/jpeg','image/jpg','image/gif','image/png'])) $error_file = 'Можно загрузить только JPEG, GIF, PNG';
					else if (!in_array($fend, ['jpeg','jpg','gif','png'])) $error_file = 'Можно загрузить только JPEG, GIF, PNG';
					else if (!move_uploaded_file($file['tmp_name'], $fld.'/'.$fn)) $error_file = 'Ошибка загрузки файла';
					else { $file_ok = true; $user_avatar = preg_replace('#^.+?/uploads/#','uploads/', $fld.'/'.$fn); createThumbnail($fld.'/'.$fn); }
					}
				else if ($file['name']!='')
					{
					$error_file = 'Ошибка загрузки файла, возможно файл слишком большой';
					}
				else
					{
					$file_ok = true;
					$user_avatar = $this->user_data['user_avatar'];
					}
				}
			else
				{
				$file_ok = true;
				$user_avatar = '';
				}

			if (!$file_ok)
				{
				$this->ajax_return['info'] = $error_file;
				}
			else
				{
				$upd = [];
				$upd['user_fname'] = htmlspecialchars(@$_POST['fname']);
				$upd['user_sname'] = htmlspecialchars(@$_POST['sname']);
				$upd['user_lname'] = htmlspecialchars(@$_POST['lname']);
				$upd['user_birth_date'] = htmlspecialchars(@$_POST['birth_date']);
				$upd['user_birth_city'] = htmlspecialchars(@$_POST['birth_city']);
				$upd['user_address'] = htmlspecialchars(@$_POST['address']);
				$upd['user_postcode'] = htmlspecialchars(@$_POST['postcode']);
				$upd['user_avatar'] = $user_avatar;

				$temp = $this->db->uni_select_one('users',['user_id'=>$this->userId()]);
				$old_data = array(); foreach ($upd as $k=>$v) $old_data[$k] = $temp[$k];

				$this->db->uni_update('users',['user_id'=>$this->userId()],$upd);

				$this->db->logWrite(LOG_CHANGE_USER_DATA,arrToLines($old_data),arrToLines($upd),ACC_USER,$this->userId());

				$this->ajax_return['result'] = true;
				$this->ajax_return['info'] = '[L:USER_DATA_SAVED]';
				}
			}

		// ПЛАТЕЖНЫЙ ПАРОЛЬ
		if ($_GET['ajax']=='setPayPass')
			{
			if (isset($_SESSION['coincash_user_need_change_pass'])) return false;

			$paycode1 = @$_POST['paycode1'];
			$paycode2 = @$_POST['paycode2'];


			if ($this->user_data['user_payhash']!='')
				{
				$this->ajax_return['info'] = '[L:PAYCODE_ALREADY_SET]';
				}
			else if (strlen($paycode1)<6)
				{
				$this->ajax_return['info'] = '[L:REG_SHORT_PASS]';
				}
			else if ($paycode1 != $paycode2)
				{
				$this->ajax_return['info'] = '[L:REG_WRONG_PASS_CONFIRM]';
				}
			else
				{
				$salt = genPass(5,5);
				$hash = sha1($paycode1.$salt);

				$this->db->uni_update('users',['user_id'=>$this->userId()],['user_payhash' => $hash,'user_paysalt' => $salt]);
				$this->db->logWrite(LOG_PAYCODE_CHANGE,'','',ACC_USER,$this->userId());	

				$this->ajax_return['result'] = true;			
				}		
			}

		// ВКЛЮЧИТЬ 2ФА
		if ($_GET['ajax']=='enable2Fa')
			{
			if (isset($_SESSION['coincash_user_need_change_pass'])) return false;

			$code = trim(@$_POST['code_2fa']);
			$GA = new GA();

			if (!$GA->verify($this->user_data['user_2fa_secret'],$code))
				{
				$this->ajax_return['info'] = '[L:2FA_WRONG_CODE]';		
				}
			else
				{
				$this->db->uni_update('users',['user_id'=>$this->userId()],['user_2fa'=>1]);
				$this->db->logWrite(LOG_2FA_ON,'','',ACC_USER,$this->userId());

				$this->ajax_return['result'] = true;	
				}
			}

		// ВЫКЛЮЧИТЬ 2ФА
		if ($_GET['ajax']=='disable2Fa')
			{
			if (isset($_SESSION['coincash_user_need_change_pass'])) return false;

			$code = trim(@$_POST['code_2fa']);
			$GA = new GA();

			if (!$GA->verify($this->user_data['user_2fa_secret'],$code))
				{
				$this->ajax_return['info'] = '[L:2FA_WRONG_CODE]';			
				}
			else
				{
				$this->db->uni_update('users',['user_id'=>$this->userId()],['user_2fa'=>0,'user_2fa_secret'=>$GA->genSecret()]);
				$this->db->logWrite(LOG_2FA_OFF,'','',ACC_USER,$this->userId());

				$this->ajax_return['result'] = true;
				}
			}

		// НАСТРОЙКИ УВЕДОМЛЕНИЙ
		if ($_GET['ajax']=='saveASS')
			{
			if (isset($_SESSION['coincash_user_need_change_pass'])) return false;

			$ava = [
					'feedback',
					'payout','payin',
					'changein',
					'order'
					];

			$todo = [];
			foreach ($ava as $one)
				{
				if (isset($_POST['tga'][$one])) $todo[$one] = 1;
				}

			$this->db->saveASS(ACC_USER,$this->userId(),$todo);
			$this->db->logWrite(LOG_ASS_SAVE,'','',ACC_USER,$this->userId());

			$this->ajax_return['result'] = true;
			}

		// API
		if ($_GET['ajax']=='genUAPIkey')
			{
			if ($this->user_data['user_api_key']!='')
				{
				$this->ajax_return['info'] = 'API ключ уже был сформирован';	
				}
			else
				{
				$key = $this->db->genUAPIkey();

				$this->db->uni_update('users',['user_id'=>$this->userId()],['user_api_key'=>$key]);
				$this->db->logWrite(LOG_GEN_UAPI,'','',ACC_USER,$this->userId());

				$this->ajax_return['result'] = true;
				}
			}

		}

	}