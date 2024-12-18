<?php

error_reporting(false);
define('COINCASH',true);

require_once(__DIR__.'/core/includes/init.php');

$BASE = new BASE($config['db']);

$TG = new TG($config['tg']['token']);

$text = $TG->getMess();
$chat = $TG->getChatId();
$user = $TG->getUsername();

if (isset($_GET['test']))
	{
	$text = '/reg anton_petrikov@mail.ru:9pXjRg';
	$chat = '441887797';
	$user = 'slkkll';
	}

// ПОЛЬЗОВАТЕЛЬСКИЕ ЗАПРОСЫ
if (preg_match('#^\/reg (.+?)\:(.+?)$#si',$text,$res)) // привязка аккаунта
	{
	$email = trim($res[1]);
	$code =  trim($res[2]);

	$user_data = $BASE->uni_select_one('users',['user_email'=>$email]);
	if ($user_data===false)
		{
		$TG->sendMess($chat,'Пользователь не зарегистрирован в системе');
		}
	else if ($user_data['user_tg']!='')
		{
		$TG->sendMess($chat,'К данному аккаунту уже привязан аккаунт Telegram');
		}
	else if ($user_data['user_tg_code']!=$code)
		{
		$TG->sendMess($chat,'Неверный код привязки, проверьте в личном кабинете на сайте');
		}
	else
		{
		// всё ок
		$upd = [
				'user_tg' => $user,
				'user_tg_chat_id' => $chat,
				];
		$BASE->uni_update('users',['user_id'=>$user_data['user_id']],$upd);
		$BASE->logWrite(LOG_TG_BOT_REG,'',$user,ACC_USER,$user_data['user_id']);

		$TG->sendMess($chat,'Ваш аккаунт привязан');
		}
	}
else if (preg_match('#^\/unreg (.+?)$#si',$text,$res)) // отвязка аккаунта
	{
	$email = trim($res[1]);

	$user_data = $BASE->uni_select_one('users',['user_email'=>$email]);
	if ($user_data===false)
		{
		$TG->sendMess($chat,'Пользователь не зарегистрирован в системе');
		}
	else if ($user_data['user_tg']!=$user)
		{
		$TG->sendMess($chat,'Этот Telegram аккаунт не приявязан к данному E-mail');
		}
	else
		{
		// всё ок
		$upd = [
				'user_tg' => '',
				'user_tg_chat_id' => '',
				'user_tg_code' => genPass(5,10)
				];
		$BASE->uni_update('users',['user_id'=>$user_data['user_id']],$upd);
		$BASE->logWrite(LOG_TG_BOT_UNREG,$user,'',ACC_USER,$user_data['user_id']);

		$TG->sendMess($chat,'Ваш аккаунт отзязан');
		}	
	}
	
// АДМИНСКИЕ ЗАПРОСЫ
else if (preg_match('#^\/regadmin (.+?)\:(.+?)$#si',$text,$res)) // привязка аккаунта
	{
	$login = trim($res[1]);
	$code =  trim($res[2]);

	$admin_data = $BASE->uni_select_one('admins',['admin_login'=>$login]);
	if ($admin_data===false)
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else if ($admin_data['admin_tg']!='')
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else if ($admin_data['admin_tg_code']!=$code)
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else
		{
		// всё ок
		$upd = [
				'admin_tg' => $user,
				'admin_tg_chat_id' => $chat,
				];
		$BASE->uni_update('admins',['admin_id'=>$admin_data['admin_id']],$upd);
		$BASE->logWrite(LOG_TG_BOT_REG,'',$user,ACC_ADMIN,$admin_data['admin_id']);

		$TG->sendMess($chat,'Ваш аккаунт привязан');
		}	
	}
else if (preg_match('#^\/unregadmin (.+?)\:(.+?)$#si',$text,$res)) // отвязка аккаунта
	{
	$login = trim($res[1]);
	$code =  trim($res[2]);

	$admin_data = $BASE->uni_select_one('admins',['admin_login'=>$login]);
	if ($admin_data===false)
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else if ($admin_data['admin_tg']!=$user)
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else if ($admin_data['admin_tg_code']!=$code)
		{
		$TG->sendMess($chat,'Ошибка');
		}
	else
		{
		// всё ок
		$upd = [
				'admin_tg' => '',
				'admin_tg_chat_id' => '',
				'admin_tg_code' => genPass(10,10)
				];
		$BASE->uni_update('admins',['admin_id'=>$admin_data['admin_id']],$upd);
		$BASE->logWrite(LOG_TG_BOT_UNREG,$user,'',ACC_ADMIN,$admin_data['admin_id']);

		$TG->sendMess($chat,'Аккаунт отзязан');
		}	
	}
else
	{
	$mess = '/reg you@email.com:code - привязка этого аккаунта TG к вашему аккаунту на COINCASH' . "\r\n" .
			'/unreg you@email.com - отвязка этого аккаунта TG от вашего аккаунта на COINCASH';
	$TG->sendMess($chat,$mess);
	}
