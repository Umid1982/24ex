<?php

class AuthModel extends Model
	{

	function vars()
		{
		global $config;

		if ($this->isAuth()) goRedir('/office/');

		// проверка авторизации по гуглу
		if (!empty($_GET['code']))
			{
			// Отправляем код для получения токена (POST-запрос).
			$params = array(
				'client_id'     => $config['google']['client'],
				'client_secret' => $config['google']['secret'],
				'redirect_uri'  => /*$config['site']['url']*/ 'https://pio.monster' . '/' . _LANG_ .'/auth?google',
				'grant_type'    => 'authorization_code',
				'code'          => $_GET['code']
				);	
					
			$ch = curl_init('https://accounts.google.com/o/oauth2/token');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$data = curl_exec($ch);
			curl_close($ch);	
		 
			$data = json_decode($data, true);
			if (!empty($data['access_token']))
				{
				// Токен получили, получаем данные пользователя.
				$params = array(
					'access_token' => $data['access_token'],
					'id_token'     => $data['id_token'],
					'token_type'   => 'Bearer',
					'expires_in'   => 3599
					);
		 
				$info = @json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params))), true);
				
				/*
array(
	'id' => '123456789123456789',
	'email' => 'mail@example.com',
	'verified_email' => true,
	'name' => 'Иван Иванов',
	'given_name' => 'Иван',
	'family_name' => 'Иванов', 
	'picture' => 'https://.../photo.jpg', 
	'locale' => 'ru'
}				
				*/

				if (!empty($info['email']) && !empty($info['id']))
					{
					$user_data = $this->db->uni_select_one('users',['user_email'=>$info['email']]);

					// проверяем есть ли юзер
					if ($user_data!==false)
						{
						// есть ли айди гугла
						if ($user_data['user_goggle_ouath_id']!='' && $user_data['user_goggle_ouath_id']==$info['id'])
							{
							$go_auth = true;
							}
						// не привязан гугл, привязываем
						else if ($user_data['user_goggle_ouath_id']=='')
							{
							$this->db->uni_update('users',['user_id'=>$user_data['user_id']],['user_goggle_ouath_id' => $info['id']]);
							$go_auth = true;
							}
						}
					// юзера нет, регаем юзера
					else
						{
						$GA = new GA();
						$secret = $GA->genSecret();

						$pass = genPass(7,7);

						$reg_result = $this->db->goReg($info['email'],$pass,$secret,$info['given_name']);
						if ($reg_result['result'])
							{
							$_SESSION['coincash_user_need_change_pass'] = true;
							$user_data = $this->db->uni_select_one('users',['user_id'=>$reg_result['user_id']]);
							$go_auth = true;
							}					
						}

					if ($go_auth)
						{
						$_SESSION['coincash_auth'] = true;
						$_SESSION['coincash_user_id'] = $user_data['user_id'];
						$_SESSION['coincash_user_email'] = $user_data['user_email'];

						goRedir('/office/');
						}
					else
						{
						goRedir('/auth/#google-error');
						}
					}


				}
			}

		$this->all_vars['email'] = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '');

		$params = array(
			'client_id'     => $config['google']['client'],
			'redirect_uri'  => /*$config['site']['url']*/ 'https://pio.monster' . '/' . _LANG_ .'/auth?google',
			'response_type' => 'code',
			'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
			'state'         => '123'
			);
		$this->all_vars['oauth_path'] = 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query($params));

		}

	function ajax()
		{
		global $config;

		// LOGIN ------------------------------------------------------------------------------------------------------------------------------------------
		if (@$_GET['ajax']=='goLogin')
			{
			$email = trim(htmlspecialchars(@$_POST['email']));
			$pass = trim(@$_POST['pass']);
			$code_2fa = trim(@$_POST['code_2fa']);

			$error = false;

			// проверяем данные
			if ($email=='' || $pass=='')
				{
				$this->ajax_return['info'] = '[L:AUTH_EMPTY_FIELDS]';
				}
			else
				{
				$email = strtolower($email);

				$user_data = $this->db->uni_select_one('users',['user_email'=>$email]);
				if ($user_data===false)
					{
					$auth_ok = false;
					$err = '[L:AUTH_WRONG]';
					}
				else if ($user_data['user_lock']==1)
					{
					$auth_ok = false;
					$err = '[L:USER_LOCK]';
					}
				else
					{
					$hash = sha1($pass.$user_data['user_salt']);
					if ($hash!=$user_data['user_hash'])
						{
						$auth_ok = false;
						$err = '[L:AUTH_WRONG]';
						}
					else
						{
						$auth_ok = true;
						}
					}

				if ($auth_ok)
					{
					$can_login = false;

					if ($user_data['user_2fa']==1)
						{
						$GA = new GA();
						if ($GA->verify($user_data['user_2fa_secret'],$code_2fa))
							{
							$can_login = true;
							}
						else
							{
							$this->ajax_return['info'] = '[L:2FA_WRONG_CODE]';

							$this->db->logWrite(LOG_LOGIN_WRONG_2FA,'','',ACC_USER,$user_data['user_id']);
							$can_login = false;
							}
						}
					else
						{
						$can_login = true;
						}

					if ($can_login)
						{
						$this->db->logWrite(LOG_LOGIN,'','',ACC_USER,$user_data['user_id']);
						$this->db->uni_update('users',['user_id'=>$user_data['user_id']],['user_need_logout'=>0]);

						$this->ajax_return['info'] = '[L:AUTH_OK]';
						$this->ajax_return['result'] = true;

						$_SESSION['coincash_auth'] = true;
						$_SESSION['coincash_user_id'] = $user_data['user_id'];
						$_SESSION['coincash_user_email'] = $user_data['user_email'];

						$this->ajax_return['redirTo'] = isset($_SESSION['redirTo']) ? $_SESSION['redirTo'] : '';
						}
					}
				else
					{
					$this->ajax_return['info'] = $err;					
					}
				}
			}

		// REGISTRATION ------------------------------------------------------------------------------------------------------------------------------------------
		if (@$_GET['ajax']=='goReg')
			{
			$fname = trim(htmlspecialchars(@$_POST['fname']));
			$email = trim(htmlspecialchars(@$_POST['email']));
			$pass1 = trim(@$_POST['pass1']);
			$pass2 = trim(@$_POST['pass2']);

			$this->vars['email'] = $email;

			$error = false;

			// проверяем данные
			if (!checkEmail($email))
				{
				$error = true;
				$mess = 'REG_WRONG_EMAIL';
				}
			else if (strlen($pass1)<6)
				{
				$error = true;
				$mess = 'REG_SHORT_PASS';
				}
			else if ($pass1 != $pass2)
				{
				$error = true;
				$mess = 'REG_WRONG_PASS_CONFIRM';
				}

			if ($error)
				{
				$this->ajax_return['info'] = '[L:'.$mess.']';
				}
			else
				{
				$GA = new GA();
				$secret = $GA->genSecret();

				$result = $this->db->goReg($email,$pass1,$secret,$fname);
				if ($result['result'])
					{
					$this->db->logWrite(LOG_REG,'','',ACC_USER,$result['user_id']);

					$this->ajax_return['result'] = true;
					$this->ajax_return['info'] = '[L:REG_OK]';

					// default bals
					$this->db->setDefaultBals($result['user_id']);

					// referals
					if (isset($_SESSION['sponsor_id']))
						{
						$this->db->doRefs($result['user_id'],$_SESSION['sponsor_id']);
						unset($_SESSION['sponsor_id']);
						}

					$this->ajax_return['result'] = true;
					}
				else
					{
					$this->ajax_return['info'] = '[L:REG_EMAIL_ALREADY_REG]';		
					}
				}
			}

		// RESTORE ------------------------------------------------------------------------------------------------------------------------------------------
		if (@$_GET['ajax']=='goRestore')
			{
			$email = trim(htmlspecialchars(@$_POST['email']));

			// проверяем данные
			if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$#i',$email))
				{
				$this->ajax_return['info'] = '[L:RESTORE_WRONG_EMAIL]';
				}
			else
				{
				$user_data = $this->db->uni_select_one('users',['user_email'=>$email]);

				if ($user_data==false)
					{
					$this->ajax_return['info'] = '[L:RESTORE_EMAIL_NOT_REG]';	
					}
				else if ( $user_data['user_restore_dt']!=0 && ($user_data['user_restore_dt']+$config['site']['restore_time']*60) > time() )
					{
					$this->ajax_return['info'] = '[L:RESTORE_TOO_EARLY]';
					}
				else
					{
					// генерим код
					$rcode = genPass(7,7);
					$restore_url = genRestoreUrl($config['site']['url'],$user_data['user_email']);
					
					if (@$_POST['restore_type']=='tg') // шлем TG
						{
						// шлем на ТГ
						if ($user_data['user_tg']!='' && $user_data['user_tg_chat_id']!='')
							{
							$TG = new TG($config['tg']['token']);
							$TG->sendMess($user_data['user_tg_chat_id'],'Код для восстановления пароля - '.$rcode.', введите его по адресу - '.$restore_url);
							
							$this->ajax_return['info'] = '[L:RESTORE_SEND_TG_OK]';

							// обновляем в базе
							$this->db->uni_update('users',['user_id'=>$user_data['user_id']],['user_restore_code'=>$rcode,'user_restore_dt'=>time()]);				
							}
						else
							{
							$this->ajax_return['info'] = '[L:RESTORE_SEND_TG_ERROR]';
							}

						}
					else // шлем емейл
						{
						$data = ['restore_code' => $rcode, 'restore_url' => $restore_url];
						$send_result = sendMail('restore',$data,$user_data['user_email'],$user_data['user_fname']);

						if ($send_result)
							{
							$this->db->logWrite(LOG_RESTORE_SEND,'','',ACC_USER,$user_data['user_id']);

							// обновляем в базе
							$this->db->uni_update('users',['user_id'=>$user_data['user_id']],['user_restore_code'=>$rcode,'user_restore_dt'=>time()]);

							$this->ajax_return['info'] = '[L:RESTORE_SEND_EMAIL_OK]';	
							}
						else
							{	
							$this->ajax_return['info'] = '[L:RESTORE_SEND_EMAIL_ERROR]';	
							}
						}

					}
				}
			}			

		// RESTORE CONFIRM ------------------------------------------------------------------------------------------------------------------------------------------
		if (@$_GET['ajax']=='goRestoreConfirm')
			{
			$email = trim(htmlspecialchars(@$_POST['email']));
			$rcode = trim(htmlspecialchars(@$_POST['rcode']));

			// проверяем данные
			if ($email!='' && $rcode!='')
				{
				$user_data = $this->db->uni_select_one('users',['user_email'=>$email,'user_restore_code'=>$rcode]);

				if ($user_data!==false)
					{
					// обнуляем в любом случае
					$this->db->uni_update('users',['user_id'=>$user_data['user_id']],['user_restore_code'=>'','user_restore_dt'=>0]);

					if ( ($user_data['user_restore_dt']+$config['site']['restore_time']*60) > time() ) // всё ок, авторизуйем и кидаем на смену пароля
						{
						$_SESSION['coincash_auth'] = true;
						$_SESSION['coincash_user_id'] = $user_data['user_id'];
						$_SESSION['coincash_user_email'] = $user_data['user_email'];
						$_SESSION['coincash_user_need_change_pass'] = true;

						$this->db->logWrite(LOG_LOGIN_RESTORE,'','',ACC_USER,$this->userId());

						$this->ajax_return['result'] = true;
						}
					else
						{
						$this->ajax_return['info'] = '[L:RESTORE_WRONG_RESTORE_DATA]';	
						}
					}
				else
					{
					$this->ajax_return['info'] = '[L:RESTORE_WRONG_RESTORE_DATA]';				
					}
				}
			else
				{
				$this->ajax_return['info'] = '[L:RESTORE_WRONG_RESTORE_DATA]';
				}
			}

		}

	}