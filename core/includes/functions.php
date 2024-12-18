<?

function getUrlParams()
	{
	global $config;

	$url = $_SERVER['REQUEST_URI'];
	$url = preg_replace('#\?[^\?]*#i', '', $url);
	$url = trim($url,'/ ');
	if ($url=='') return false;

	$temp = explode('/',$url);
	
	$ret = array();
	if (in_array($temp[0], $config['lang']['list'])) $ret['lang'] = $temp[0];
	else return false;

	unset($temp[0]);
	$ret['pages'] = array_values($temp);

	return $ret;
	}

function goRedir($path,$admin=false)
	{
	global $config;

	if ($admin)
		{
		$full_path = '/'.$config['site']['admin_path'].'/?page='.trim($path,'/');
		}
	else
		{
		$full_path = '/'.$config['lang']['default'].$path;
		}
		
	header('Location: '.$full_path);
	die();
	}

function adminUrlParams()
	{
	$ret = [];
	if (count($_GET)>0) foreach ($_GET as $k=>$v)
		{
		if ($k=='pg') continue;
		$ret[] = $k.'='.urlencode($v);
		}

	return implode('&',$ret);
	}

function getPage($pages)
	{
	if (count($pages)==0) // просто на морду кидаем
		{
		$page = 'index';
		}
	else if (count($pages)==1) // один уровень
		{
		$page = $pages[0];

		if (substr($page, 0, 2)=='__') // нельзя использовать такие ссылки
			{
			$page = 'error404';
			}
		else if (is_dir(_TEMPLATES_.'/'.$page)) // это папка
			{
			$page = $page.'/index';
			}
		else if (!file_exists(_TEMPLATES_.'/'.$page.'.php')) // 404
			{
			$page = 'error404';
			}
		}
	else if (count($pages)==2) // два уровня
		{
		$fold = $pages[0];
		$page = $pages[1];

		if (substr($page, 0, 2)=='__' || substr($fold, 0, 2)=='__') // нельзя использовать такие ссылки
			{
			$page = 'error404';
			}
		else if (!is_dir(_TEMPLATES_.'/'.$fold)) // 404 нет папки
			{
			$page = 'error404';
			}
		else if (!file_exists(_TEMPLATES_.'/'.$fold.'/'.$page.'.php')) // 404 нет файла
			{
			$page = 'error404';
			}
		else // всё ок
			{
			$page = $fold.'/'.$page;
			}
		}
	else // 3+ уровня пока не задействуем
		{
		$page = 'error404';
		}

	return $page;
	}

function getModelName($page)
	{
	$temp = explode('/',$page);

	if (count($temp)==2) // ищем модель глубоко
		{
		$model_name = ucfirst($temp[0]).ucfirst($temp[1]).'Model';
		if (!class_exists($model_name))
			{
			$model_name = ucfirst($temp[0]).'Model';
			if (!class_exists($model_name))
				{
				$model_name = 'Model';
				}
			}
		}
	else // 1 уровень
		{
		$model_name = ucfirst($temp[0]).'Model';
		if (!class_exists($model_name))
			{
			$model_name = 'Model';
			}
		}

	return $model_name;
	}

function genPass($n1=6,$n2=10,$clean=false)
	{
	if (!$clean) $line = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM!@$%&';
	else $line = '0123456789QWERTYUIOPASDFGHJKLZXCVBNM';
	$ret = '';

	$max = rand($n1,$n2);
	for ($i=0;$i<$max;$i++)
		{
		$ret .= substr($line,rand(0,strlen($line)),1);
		}

	return $ret;
	}

function genRestoreUrl($siteurl, $email)
	{
	$url = $siteurl . '/' . _LANG_ . '/auth?email='.urlencode($email).'#forgot-restore';
	return $url;
	}

function arrToLines($arr)
	{
	$ret = [];
	foreach ($arr as $k=>$v) $ret[] = '<b>'.$k.'</b> : '.$v;
	return implode('<br>',$ret);
	}

function sendMail($tpl,$data,$user_email,$name='')
	{
	global $config;

	$mail = new PHPMailer(true);

	//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output

	$mail->CharSet = 'utf-8';									// для кирилицы
	$mail->isSMTP();                                            //Send using SMTP
	$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	$mail->SMTPSecure = 'tls';         							//Enable TLS encryption

	$mail->Host       = $config['mail']['smtp'];                //Set the SMTP server to send through
	$mail->Username   = $config['mail']['from'];                //SMTP username
	$mail->Password   = $config['mail']['pass'];                //SMTP password
	$mail->Port       = $config['mail']['port'];  				//SMTP Port

	$mail->setFrom($config['mail']['from'], $config['mail']['name']);

	$mail->addAddress($user_email,$name);

	$mail->isHTML(true);

	$body = file_get_contents(_TEMPLATES_.'/__mails/'.$tpl.'.html');
	if (count($data)>0) foreach ($data as $k=>$v) $body = str_replace('['.$k.']', $v, $body);
	$mail->Body = $body;

	//find title for subject
	if (preg_match('#<title[^>]*>(.+?)</title>#si', $body, $res))
		{
		$subj = trim($res[1]);
		}
	else
		{
		$subj = $config['mail']['subject_default'];
		}

	$mail->Subject = $subj;

	try
		{
		$mail->send();
		return true;
		}
	catch (Exception $e)
		{
	    return false;
		}		
	}

function getClientIp()
	{
    $ipaddress = '';

	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
		{
    	$ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
  		}
  	else if (isset($_SERVER['HTTP_CLIENT_IP']))
  		{
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  		}
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    	{
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
    	{
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    	}
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    	{
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    	}
    else if(isset($_SERVER['HTTP_FORWARDED']))
    	{
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    	}
    else if(isset($_SERVER['REMOTE_ADDR']))
    	{
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    	}
    else
    	{
        $ipaddress = 'UNKNOWN';
    	}

	return $ipaddress;
	}

function getClientDevice()
	{
	$detect = new Mobile_Detect;
	
	if ($detect->isIphone()) 		$device = 'iphone';
	elseif ($detect->isIpad()) 		$device = 'ipad';
	elseif ($detect->isAndroidOS()) $device = 'android';
	elseif ($detect->isMobile()) 	$device = 'mobile';

	elseif ($detect->isSafari()) 	$device = 'macos';
	else 							$device = 'desktop';

	return $device;	
	}

function avaStatuses($status)
	{
	if 		($status==PAY_STATUS_NEW) 		$ret = [PAY_STATUS_IN_WORK,PAY_STATUS_REJECT];
	else if ($status==PAY_STATUS_GO_PAY) 	$ret = [PAY_STATUS_IN_WORK,PAY_STATUS_REJECT];
	else if ($status==PAY_STATUS_USER_PAYS) $ret = [PAY_STATUS_IN_WORK,PAY_STATUS_REJECT,PAY_STATUS_DONE];
	else if ($status==PAY_STATUS_SEND_PROPS)$ret = [PAY_STATUS_IN_WORK,PAY_STATUS_REJECT,PAY_STATUS_DONE];
	else if ($status==PAY_STATUS_PAYS) 		$ret = [PAY_STATUS_IN_WORK,PAY_STATUS_REJECT,PAY_STATUS_DONE];
	else if ($status==PAY_STATUS_IN_WORK) 	$ret = [PAY_STATUS_REJECT,PAY_STATUS_DONE];
	else if ($status==PAY_STATUS_PENDING) 	$ret = [PAY_STATUS_REJECT,PAY_STATUS_DONE];
	else 									$ret = [];

	return $ret;
	}

function getAllLogTypes()
	{
	$i = -1;
	$end = false;
	$ret = [];

	$errn = 0;

	while (!$end)
		{
		$i++;
		$test = logText($i,'','',true);

		if ($test===false)
			{
			$errn++;
			if ($errn>=5) break;
			else continue;
			}
		else
			{
			$errn = 0;
			}

		$ret[$i] = $test;
		}

	return $ret;
	}

function logText($type,$old,$new,$menu=false)
	{
	$mtext = true;

	switch ($type)
		{
		case LOG_REG: 					$text = 'Регистрация'; break;
		case LOG_LOGIN: 				$text = 'Вход в аккаунт'; break;
		case LOG_LOGOUT: 				$text = 'Выход из аккаунта'; break;
		case LOG_CHANGE_USER_DATA: 		$text = 'Изменены личные данные<code>'.$old.'</code>На<code>'.$new.'</code>'; $mtext = 'Изменены личные данные'; break;
		case LOG_PASS_CHANGE: 			$text = '<span style="color:red">Изменен пароль<span>'; break;
		case LOG_RESTORE_SEND: 			$text = 'Запрос на восстановление пароля'; break;
		case LOG_LOGIN_RESTORE: 		$text = '<span style="color:red">Вход по данным восстановления</span>'; break;
		case LOG_TG_BOT_REG: 			$text = 'Зарегистрирован TG акканут: '.$new; $mtext = 'Зарегистрирован TG акканут'; break;
		case LOG_TG_BOT_UNREG: 			$text = 'Отвязан TG акканут: '.$old;  $mtext = 'Отвязан TG акканут'; break;
		case LOG_LOGIN_WRONG_2FA: 		$text = '<span style="color:red">Попытка входа с неверным 2FA кодом</span>'; break;
		case LOG_2FA_ON: 				$text = 'Включена 2FA авторизация'; break;
		case LOG_2FA_OFF: 				$text = 'Выключена 2FA авторизация'; break;
		case LOG_PAYCODE_CHANGE: 		$text = 'Задан платежный код'; break;
		case LOG_LOGIN_WRONG_PASS:		$text = '<span style="color:red">Введен неверный пароль</span>'; break;
		case LOG_DELETE_ADMIN:			$text = '<span style="color:red">Удален админ/модератор <b>'.$old.'</b></span>'; break;
		
		case LOG_NEW_ADMIN:				$text = 'Создан админ/модератор <b>'.$new.'</b>'; break;
		case LOG_UPD_ADMIN:				$text = 'Изменен админ/модератор<code>'.$old.'</code>На<code>'.$new.'</code>'; $mtext = 'Изменен админ/модератор'; break;

		case LOG_NEW_BAL_TYPE:			$text = 'Создан тип баланса <b>'.$new.'</b>'; break;
		case LOG_UPD_BAL_TYPE:			$text = 'Изменен тип баланса <b>'.$old.'</b> на <b>'.$new.'</b>';  $mtext = 'Изменен тип баланса'; break;
		case LOG_DELETE_BAL_TYPE:		$text = '<span style="color:red">Удален тип баланса <b>'.$old.'</b></span>'; break;

		case LOG_DELETE_BAL:			$text = '<span style="color:red">Баланс удален  <b>'.$old.'</b><span>'; break;
		case LOG_UPDATE_BAL:			$text = 'Баланс изменен <code>'.$old.'</code>На<code>'.$new.'</code>';  $mtext = 'Баланс изменен'; break;
		case LOG_CREATE_BAL:			$text = 'Баланс создан <code>'.$new.'</code>'; break;

		case LOG_FEEDBACK_MARK_ANS: 	$text = 'Фидбэк сообщение <b>#'.$new.'</b> помечено как прочитанное'; $mtext = 'Фидбэк сообщение прочитано'; break;
		case LOG_FEEDBACK_SEND_ANS: 	$text = 'Фидбэк сообщение <b>#'.$old.'</b> отвечено, отправлен ответ <code>'.$new.'</code>'; $mtext = 'Фидбэк сообщение отвечено'; break;

		case LOG_UB_ADD:				$text = 'Баланс пользователя добавлен <code>'.$new.'</code>'; break;
		case LOG_UB_DEL:				$text = 'Баланс пользователя удален <code>'.$old.'</code>'; break;

		case LOG_PAYIN_NEW:				$text = 'Запрос на пополнение, №'.$new; $mtext = 'Запрос на пополнение'; break;
		case LOG_PAYIN_GOPAY:			$text = 'По заявки №'.$old.' выбран способ платежа '.$new; $mtext = 'Выбран способ для пополнения'; break;

		case LOG_PAYOUT_NEW:			$text = 'Запрос на вывод, №'.$new; $mtext = 'Запрос на вывод'; break;
		case LOG_PAYOUT_GOPAY:			$text = 'По заявки №'.$old.' выбран способ выплаты '.$new; $mtext = 'Выбран способ для вывода'; break;

		case LOG_PAY_CHANGE_COMM:		$text = 'Изменени комментарий пользователя <code>'.$old.'</code> на <code>'.$new.'</code>'; $mtext = 'Изменен комментарий юзера'; break;
		case LOG_PAY_CANCEL:			$text = 'Платеж отменен №'.$old; $mtext = 'Платеж отменен'; break;

		case LOG_PAY_NEW_STATUS:		$text = 'Изменени статус платежа <code>'.$old.'</code> на <code>'.$new.'</code>'; $mtext = 'Изменени статус платежа'; break;
		case LOG_PAY_NEW_ADM_COMM:		$text = 'Изменени комментарий админа <code>'.$old.'</code> на <code>'.$new.'</code>'; $mtext = 'Изменен комментарий админа'; break;

		case LOG_SECUR_CHANGE_IPS:		$text = 'Изменен список заблокированный IP адресов'; break;

		case LOG_USER_LOCK:				$text = 'Пользователь #'.$old.' заблокирован'; $mtext = 'Пользователь заблокирован'; break;
		case LOG_USER_UNLOCK:			$text = 'Пользователь #'.$old.' разблокирован'; $mtext = 'Пользователь разблокирован'; break;
		case LOG_USER_RESET_PAYCODE:	$text = 'Платежный пароль для пользователя #'.$old.' сброшен'; $mtext = 'Платежный пароль сброшен'; break;
		case LOG_USER_RESET_2FA:		$text = 'Двухфакторная авторизация для пользователя #'.$old.' сброшена'; $mtext = 'Двухфакторная авторизация сброшена'; break;

		case LOG_ONE_IP_LOCK:			$text = 'IP: '.$new.' заблокирован'; $mtext = 'IP заблокирован'; break;
		case LOG_UB_CHANGE_LOCK:		$text = $old.' баланс '.(($new==1) ? 'заблокирован' : 'разблокирован'); $mtext = 'Блокировка баланса изменена'; break;

		case LOG_PAYSYS_IN_ERROR:		$text = 'Ошибка создания платежа, отправили <code>'.$old.'</code> получили <code>'.$new.'</code>'; $mtext = 'Ошибка создания платежа на пополнение'; break;

		case LOG_MAIN_SETTINGS_SAVE:	$text = 'Общие настройки были изменены'; break;

		case LOG_NEW_PAYSYS:			$text = 'Создан новый способ ввода/вывода ID:<b>'.$new.'</b>'; $mtext = 'Создан новый способ ввода/вывода'; break;
		case LOG_CHANGE_PAYSYS:			$text = 'Отредактирован способ ввода/вывода ID:<b>'.$old.'</b>'; $mtext = 'Отредактирован способ ввода/вывода'; break;

		case LOG_VOUCHER_NEW:			$text = 'Создан ваучер №'.$new; $mtext = 'Создан ваучер'; break;
		case LOG_PAY_VOUCHER:			$text = 'Активирован ваучер №'.$old.' по платежу '.$new; $mtext = 'Активирован ваучер'; break;

		case LOG_PAYSYS_OUT_ERROR:		$text = 'Ошибка создания платежа, отправили <code>'.$old.'</code> получили <code>'.$new.'</code>'; $mtext = 'Ошибка создания платежа на вывод'; break;

		case LOG_UPDATE_CH:				$text = 'Настройки обмена изменены <code>'.$old.'</code> на <code>'.$new.'</code>'; $mtext = 'Настройки обмена изменены'; break;

		case LOG_CH_CHANGE_MINUS:		$text = 'Остаток баланса №'.$old.' <b style="color:red">уменьшен</b> на '.$new; $mtext = 'Остаток баланса уменьшен'; break;
		case LOG_CH_CHANGE_PLUS:		$text = 'Остаток баланса №'.$old.' <b style="color:green">увеличен</b> на '.$new; $mtext = 'Остаток баланса увеличен'; break;

		case LOG_PAYCHANGE_IN_NEW:		$text = 'Запрос на внутренний обмен №'.$new; $mtext = 'Запрос на внутренний обмен'; break;
		case LOG_PAYCHANGE_OUT_NEW:		$text = 'Запрос на обмен на сайте №'.$new; $mtext = 'Запрос на обмен на сайте'; break;

		case LOG_ASS_SAVE:				$text = 'Обновлены настройки TG уведомлений'; break;

		case LOG_NEWS_NEW:				$text = 'Создана новость №'.$new; $mtext = 'Новость создана'; break;
		case LOG_NEWS_UPD:				$text = 'Новость обновлена №'.$old; $mtext = 'Новость обновлена'; break;
		case LOG_NEWS_DEL:				$text = 'Новость удалена №'.$old; $mtext = 'Новость удалена'; break;
		case LOG_NEWS_TG:				$text = 'Новость отправлена на TG канал №'.$old; $mtext = 'Новость отправлена на TG канал'; break;

		case LOG_INVEST_PLAN_NEW:		$text = 'Создан инвест план №'.$new; break;
		case LOG_INVEST_PLAN_UPD:		$text = 'Инвест план обновлен №'.$old; break;
		case LOG_INVEST_PLAN_DEL:		$text = 'Инвест план удален №'.$old; break;

		case LOG_USER_INVEST_NEW:		$text = 'Создан инвест депозит №'.$new; break;
		case LOG_USER_INVEST_UPD:		$text = 'Статус депозита №'.$old.' изменен на `'.getInvStatusText($new).'`'; $mtext = 'Статус депозита изменен'; break;
		case LOG_USER_INVEST_DEFROST:	$text = 'Депозит №'.$old.' был разморожен'; $mtext = 'Депозит разморожен'; break;
		case LOG_USER_INVEST_APPEND:	$text = 'Депозит №'.$old.' дополнен на сумму '.$new; $mtext = 'Депозит дополнен на сумму'; break;

		case LOG_PAY_TRANFER_NEW:		$text = 'Новая заявка на перевод, №'.$new; $mtext = 'Новая заявка на перевод'; break;
		case LOG_PAY_TRANFER_PROPS:		$text = 'По переводу №'.$old.' введены данные кошелька '.$new; $mtext = 'По переводу введены данные кошелька'; break;
		case LOG_PAY_TRANFER_DONE:		$text = 'Перевод №'.$old.' проведен автоматически'; $mtext = 'Перевод проведен автоматически'; break;
		case LOG_PAY_TRANFER_RESET:		$text = 'По переводу №'.$old.' сброшены реквизиты'; $mtext = 'По переводу сброшены реквизиты'; break;
		case LOG_PAY_TRANFER_PAYS:		$text = 'По переводу №'.$old.' реквизиты подтвержены, ждет подтверждение админа'; $mtext = 'По переводу реквизиты подтвержены'; break;

		case LOG_GEN_UAPI:				$text = 'Сгенерирован API ключ'; break;

		case LOG_MERCH_CONFIRM:			$text = 'Магазин `'.$old.'` проверен успешно'; $mtext = 'Магазин проверен успешно'; break;
		case LOG_MERCH_MODER_SEND:		$text = 'Магазин `'.$old.'` отправлен на модерацию'; $mtext = 'Магазин отправлен на модерацию'; break;
		case LOG_MERCH_PSS_SAVE:		$text = 'Изменены валюты для магазина `'.$old.'`'; $mtext = 'Изменены валюты для магазина'; break;
		case LOG_MERCH_MODER_CHANGE:	$text = 'Изменен статус магазина `'.$old.'` на `'.$new.'`'; $mtext = 'Изменен статус магазина'; break;
		case LOG_MERCH_CHANGE_PRC:		$text = 'Изменена комиссия магазина `'.$old.'` на `'.$new.'`'; $mtext = 'Изменена комиссия магазина'; break;

		case LOG_ORDER_NEW:				$text = 'Создан новый заказ с сайта, №'.$new; $mtext = 'Создан новый заказ с сайта'; break;
		case LOG_ORDER_GOPAY:			$text = 'По заказу №'.$old.' выбран способ оплаты '.$new; $mtext = 'По заказу выбран способ оплаты'; break;
		case LOG_ORDER_NEW_STATUS:		$text = 'Изменен статус заказа <code>'.$old.'</code> на <code>'.$new.'</code>'; $mtext = 'Изменен статус заказа'; break;

		default:						$text = 'N/A'; $mtext = false; break;
		}

	if ($menu)
		{
		if ($mtext===false) return false;
		if ($mtext===true) $mtext = strip_tags($text);

		$mtext = trim($mtext);
		$mtext = trim($mtext,'№');
		return $mtext;
		}
	else
		{
		return $text;
		}
	}

function payTypeText($type)
	{
	switch ($type)
		{
		case PAY_TYPE_IN: 			$text = 'Пополнение'; break;
		case PAY_TYPE_OUT: 			$text = 'Вывод'; break;
		case PAY_TYPE_CHANGE_IN: 	$text = 'Внутренный обмен'; break;
		case PAY_TYPE_CHANGE_OUT: 	$text = 'Обмен на сайте'; break;
		case PAY_TYPE_TRANSFER:		$text = 'Перевод'; break;
		case PAY_TYPE_ORDER:		$text = 'Заказ в магазине'; break;
		default:					$text = 'N/A'; break;
		}

	return $text;	
	}

function getPayTypeUrl($type)
	{
	switch ($type)
		{
		case PAY_TYPE_IN: 			$text = 'payin'; break;
		case PAY_TYPE_OUT: 			$text = 'payout'; break;
		case PAY_TYPE_CHANGE_IN: 	$text = 'changein'; break;
		case PAY_TYPE_CHANGE_OUT: 	$text = 'changeout'; break;
		case PAY_TYPE_TRANSFER:		$text = 'transfer'; break;
		case PAY_TYPE_ORDER:		$text = 'order'; break;
		default:					$text = ''; break;
		}

	return $text;	
	}

function refPlanTypeText($type)
	{
	switch ($type)
		{
		case RP_DEPOSIT: 	$text = 'Пополнение'; break;
		case RP_INVEST: 	$text = 'Инвестиция'; break;
		default:			$text = 'N/A'; break;
		}

	return $text;	
	}

function payStatusText($status)
	{
	switch ($status)
		{
		case PAY_STATUS_NEW: 			$text = 'Новая'; break;
		case PAY_STATUS_IN_WORK: 		$text = 'В работе'; break;
		case PAY_STATUS_CANCEL: 		$text = 'Отменена'; break;
		case PAY_STATUS_REJECT: 		$text = 'Отклонена'; break;
		case PAY_STATUS_DONE: 			$text = 'Завершена'; break;
		case PAY_STATUS_PAYS: 			$text = 'Оплачена'; break;
		case PAY_STATUS_USER_PAYS:		$text = 'Отмечена как оплаченная'; break;
		case PAY_STATUS_SEND_PROPS:		$text = 'Реквизиты введены'; break;
		case PAY_STATUS_PENDING:		$text = 'Ожидание подтверждения оплаты'; break;
		case PAY_STATUS_GO_PAY:			$text = 'Ожидание оплаты/реквизитов'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;
	}

function adminTypeText($type)
	{
	switch ($type)
		{
		case AT_SUPER: 	$text = 'Супер-админ'; break;
		case AT_ADMIN: 	$text = 'Админ'; break;
		case AT_MODER: 	$text = 'Модератор'; break;
		default:		$text = 'N/A'; break;
		}

	return $text;
	}

function getBalStatusText($status)
	{
	switch ($status)
		{
		case BAL_STATUS_OFF: 			$text = 'Выключено'; break;
		case BAL_STATUS_ON: 			$text = 'Активно'; break;
		case BAL_STATUS_OFF_PAYIN: 		$text = 'Запрет на пополнение'; break;
		case BAL_STATUS_OFF_PAYOUT: 	$text = 'Запрет на вывод'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;
	}

function getReasonText($reason)
	{
	switch ($reason)
		{
		case REASON_PAYMENT: 			$text = 'Пополнение баланса'; break;
		case REASON_REFPAY: 			$text = 'Реферальные начисления'; break;
		case REASON_PAYOUT_PAY: 		$text = 'Вывод средств'; break;
		case REASON_PAYOUT_COM: 		$text = 'Комиссия за вывод средств'; break;
		case REASON_BACK_PAY: 			$text = 'Отмена вывода средств'; break;
		case REASON_BACK_COM: 			$text = 'Отмена комисси за вывод средств'; break;
		case REASON_NEW_VOUCHER: 		$text = 'Создание ваучера'; break;
		case REASON_NEW_VOUCHER_COM: 	$text = 'Коммисия за создание ваучера'; break;
		case REASON_VOUCHER_PAY:		$text = 'Активация ваучера'; break;
		case REASON_CHANGEIN_PAY:		$text = 'Внутренний обмен'; break;
		case REASON_CHANGEIN_COM:		$text = 'Внутренний обмен (комиссия)'; break;
		case REASON_CHANGEIN_BACK_PAY:	$text = 'Отмена внутреннего обмена'; break;
		case REASON_CHANGEIN_BACK_COM:	$text = 'Отмена комиссии за внутренний обмен'; break;
		case REASON_INVEST_NEW:			$text = 'Создание инвестиционного депозита'; break;
		case REASON_INVEST_PAYED:		$text = 'Зачисление депозита на счет'; break;
		case REASON_INVEST_DEFROST:		$text = 'Разморозка депозита'; break;
		case REASON_INVEST_REFPAY_DEPO:	$text = 'Реферальные начисления за депозит'; break;
		case REASON_INVEST_REFPAY_PROC:	$text = 'Реферальные начисления на % от депозита'; break;
		case REASON_INVEST_APPEND:		$text = 'Дополнение депозита'; break;
		case REASON_TRANSFER_PAY:		$text = 'Перевод на другой кошелек'; break;
		case REASON_TRANSFER_COM:		$text = 'Комиссия за перевод'; break;
		case REASON_TRANSFER_GET_PAY:	$text = 'Получение перевода'; break;
		case REASON_TRANSFER_BACK_PAY:	$text = 'Отмена перевода'; break;
		case REASON_TRANSFER_BACK_COM:	$text = 'Отмена комиссии за перевод'; break;
		case REASON_ORDER_PAY: 			$text = 'Оплата заказа'; break;
		case REASON_ORDER_COM: 			$text = 'Комиссия за оплату заказа'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;	
	}

function getVoucherStatusText($status)
	{
	switch ($status)
		{
		case VOUCHER_ACTIVE: 			$text = 'Активен'; break;
		case VOUCHER_LOCK: 				$text = 'Заморожен'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;	
	}

function getNewsStatusLine($status)
	{
	switch ($status)
		{
		case N_STATUS_DRAFT: 			$text = '<span style="color:gray">Черновик</span>'; break;
		case N_STATUS_PUB: 				$text = '<span style="color:green">Опубликовано</span>'; break;
		case N_STATUS_FUTURE: 			$text = '<span style="color:blue">Запланировано</span>'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;		
	}

function getInvestLogTypeLine($type)
	{
	switch ($type)
		{
		case INV_LOG_NEW: 			$text = 'Создание депозита'; break;
		case INV_LOG_PROC: 			$text = 'Начисление процентов'; break;
		case INV_LOG_PAYED: 		$text = 'Зачисление на баланс'; break;
		case INV_LOG_DEFROST_VAL: 	$text = 'Разморозка депозита'; break;
		case INV_LOG_DEFROST_PROC: 	$text = 'Комиссия за разморозку'; break;
		case INV_LOG_APPEND: 		$text = 'Депозит дополнен'; break;
		default:					$text = 'N/A'; break;
		}

	return $text;		
	}

function getInvStatusText($status)
	{
	switch ($status)
		{
		case INV_STATUS_ON: 			$text = '<span style="color:green">Активен</span>'; break;
		case INV_STATUS_END: 			$text = '<span style="color:blue">Завершен</span>'; break;
		case INV_STATUS_OFF: 			$text = '<span style="color:red">Выключен</span>'; break;
		case INV_STATUS_PAYED: 			$text = '<span style="color:blue">Выплачен</span>'; break;
		case INV_STATUS_DEFROST:		$text = '<span style="color:#96552d">Разморожен</span>'; break;
		default:						$text = 'N/A'; break;
		}

	return $text;	
	}

function getPayErrText($err,$p1='',$p2='')
	{
	switch ($err)
		{
		case PAY_ERR_INPUT_DATA: 		$text = 'Введены неверные данные'; break;
		case PAY_ERR_LOCK: 				$text = 'Платеж недоступен'; break;
		case PAY_ERR_LIMITS: 			$text = 'Сумма должна быть в диапазоне '.$p1.' - '.$p2; break;
		case PAY_ERR_LIMITS_2: 			$text = 'Сумма получения должна быть в диапазоне '.$p1.' - '.$p2; break;
		case PAY_ERR_UB_NUM:			$text = 'Неверный адрес получателя'; break;
		case PAY_ERR_UB_NUM_BAL:		$text = 'Баланс кошелька не соответствует валюте перевода'; break;
		case PAY_ERR_PAYCODE:			$text = 'Неверный платежный пароль'; break;
		case PAY_ERR_UB_VALUE:			$text = 'На счете недостаточно средств'; break;
		case PAY_ERR_CH_VALUE:			$text = 'Сумма больше резерва'; break;
		default:						$text = 'Неизвестная ошибка'; break;
		}

	return $text;	
	}

function orderStatusText($status)
	{
	switch ($status)
		{
		case ORDER_STATUS_NEW: 			$text = '<span style="color:blue">Новый</span>'; break;
		case ORDER_STATUS_PAYS: 		$text = '<span style="color:#a7a64e">Оплачен</span>'; break;
		case ORDER_STATUS_DONE: 		$text = '<span style="color:green">Завершен</span>'; break;
		case ORDER_STATUS_CANCEL: 		$text = '<span style="color:red">Отклонен</span>'; break;
		}

	return $text;		
	}

function orderCallbackText($status)
	{
	switch ($status)
		{
		case ORDER_CALLBACK_NONE: 		$text = '<span style="color:blue">Еще не отправлялось</span>'; break;
		case ORDER_CALLBACK_DONE: 		$text = '<span style="color:green">Успешно отработал</span>'; break;
		case ORDER_CALLBACK_ERROR: 		$text = '<span style="color:red">Ошибка, пробуем еще</span>'; break;
		case ORDER_CALLBACK_TRY_END: 	$text = '<span style="color:red">Подтверждение небыло получено</span>'; break;
		}

	return $text;		
	}

function clbAnswerText($answer)
	{
	switch ($answer)
		{
		case ANSWER_OK: 				$text = 'Успешно'; break;
		case ANSWER_ERR_UNKNOWN: 		$text = 'Неизвестная ошибка'; break;
		case ANSWER_ERR_SIGN: 			$text = 'Неверная подпись'; break;
		case ANSWER_ERR_SHOP: 			$text = 'Неверный магазин'; break;
		case ANSWER_ERR_ORDER_WRONG: 	$text = 'Неверный заказ'; break;
		case ANSWER_ERR_ORDER_STATUS: 	$text = 'Заказ уже был обработан'; break;
		case ANSWER_ERR_EMPTY: 			$text = 'Пустой или неверный ответ'; break;
		}

	return $text;		
	}

function getUploadDesc()
	{
	$dt = date('Y/m/d');
	$path = _ROOT_.'/uploads/'.$dt;

	if (!file_exists($path)) mkdir($path,0777,true);

	return $path;
	}

function nowDT()
	{
	return date('Y-m-d H:i:s');
	}

function getCbrXmlUsdRates()
	{
	$ret = [];
	$url = 'https://www.cbr-xml-daily.ru/daily_json.js';
	$json = @json_decode(file_get_contents($url),true);
	if (isset($json['Valute']))
		{
		$main_rate = 1 / ($json['Valute']['USD']['Value'] / $json['Valute']['USD']['Nominal']);

		foreach ($json['Valute'] as $k=>$v)
			{
			$ret[$k] = ($v['Value'] / $v['Nominal']) * $main_rate;
			}
		}

	return $ret;
	}

function getPrivat24UsdRates()
	{
	$ret = [];
	$url = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
	$json = @json_decode(file_get_contents($url),true);
	if ($json!==false)
		{
		$main_rate = false;
		foreach ($json as $one)
			{
			if ($one['ccy']=='USD')
				{
				$main_rate = 1 / $one['sale'];
				break;
				}
			else continue;
			}

		if ($main_rate!==false)
			{
			foreach ($json as $one)
				{
				$ret[$one['ccy']] = $one['sale'] * $main_rate;
				}
			}
		}

	return $ret;
	}

function getBalIcon($bal_icon='')
	{
	return ($bal_icon=='') ? 'assets/media/svg/money.svg' : $bal_icon;
	}

function getUserAvatar($ava='')
	{
	return ($ava=='') ? 'assets/media/users/blank.png' : $ava;
	}

function trimZeros($val)
	{
	$val = (string)$val;
	$val = preg_replace('#(\.[^\0]*)[\0]+$#si', '$1', $val);
	return $val;
	}

function timeElapsedString($ptime)
	{
    $etime = $ptime;

    if ($etime < 1)
    	{
        return '0 [L:elapsed_second]';
    	}

    $a = array( 365 * 24 * 60 * 60  =>  'repl01',
                 30 * 24 * 60 * 60  =>  'repl001',
                      24 * 60 * 60  =>  'repl1', // day
                           60 * 60  =>  'repl2', // hours
                                60  =>  'repl3', // minutes
                                 1  =>  'repl4'  // seconds
                );
    $a_plural = array( 'repl01' => 'repl02',
                       'repl001'=> 'repl002',
                       'repl1'	=> 'repl5',
                       'repl2'	=> 'repl6',
                       'repl3'	=> 'repl7',
                       'repl4'	=> 'repl8'
                );

    $ret = '';
    foreach ($a as $secs => $str)
    	{
        $d = $etime / $secs;
        if ($d >= 1)
        	{
            $r = round($d);
            $ret = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
            break;
        	}
    	}

    $ret = str_replace(array('repl01','repl02','repl001','repl002'),
    					array('[L:elapsed_year]','[L:elapsed_years]','[L:elapsed_month]','[L:elapsed_monthes]'),$ret);
    $ret = str_replace(array('repl1','repl2','repl3','repl4'),
    					array('[L:elapsed_day]','[L:elapsed_hour]','[L:elapsed_minute]','[L:elapsed_second]'),$ret);
    $ret = str_replace(array('repl5','repl6','repl7','repl8'),
    					array('[L:elapsed_days]','[L:elapsed_hours]','[L:elapsed_minutes]','[L:elapsed_seconds]'),$ret);

    return $ret;
	}

function getAllPaysysVars($pss,$type)
	{
	$ret = [];
	if (count($pss)>0)
		{
		foreach ($pss as $one)
			{
			$vars = $one->getVariants($type);
			foreach ($vars as $full_name=>$one) $ret[$full_name] = $one;
			}
		}

	return $ret;
	}

function formatTransFile($arr)
	{
	$out = 
"<?php
// Файл сгенерирован импортом
// Не редактируйте этот файл! 
// Используйте экспорт/импорт в админка, чтобы менять данный файл

\$translates = [
";

	foreach ($arr as $k=>$v)
		{
		$out .= "				'$k' => '$v',\r\n";
		}

	$out .= "];";

	return $out;
	}

function cutZeros($val)
	{
	if (strpos($val, '.')===false) return $val;

	$val = (string)$val;
	$val = preg_replace('#[0]+$#si', '', $val);
	$val = preg_replace('#\.[0]?$#si', '.00', $val);

	return $val;
	}

function slkDouble($val)
	{
	return number_format($val,10,'.','');
	}

function checkEmail($email)
	{
	return preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$#i',$email);
	}

function html2tg($text)
	{
	global $config;

	$text = str_replace('../uploads/', $config['site']['url'].'/uploads/', $text);
	$text = preg_replace('#<h[\d]+[^>]*>(.+?)</h[\d]+>#si', '<b>$1</b>', $text);

	//$text = preg_replace('#<p[^>]*>#si', "\r\n", $text);
	//$text = str_replace('</p>', "\r\n", $text);

	$ireg = '#<img[^>]+src="([^"]+)"[^>]*/>#si';
	if (preg_match_all($ireg,$text,$ires)>0) for ($i=0;$i<count($ires[1]);$i++)
		{
		$text .= '<a href="'.$ires[1][$i].'">&#8205;</a>';
		break;
		}

	
	$text = strip_tags($text,'<b><strong><i><em><u><ins><s><strike><del><a><code><pre>');
	$text = str_replace('&nbsp;', '', $text);
	return $text;
	}

function createThumbnail($filename)
	{
	global $config;
	$final_width_of_image = $config['avatar']['size'];
  
  	// Определяем формат изображения
	if (preg_match('/[.](jpg|jpeg)$/', $filename))
		{
		$type = 'jpg';
		$im = imagecreatefromjpeg($filename);
		}
	else if (preg_match('/[.](gif)$/', $filename))
		{
		$type = 'gif';
		$im = imagecreatefromgif($filename);
		}
	else if (preg_match('/[.](png)$/', $filename))
		{
		$type = 'png';
		$im = imagecreatefrompng($filename);
		}
	else
		{
		return false;
		}
  
	$ox = imagesx($im);
	$oy = imagesy($im);
  
	$nx = $final_width_of_image;
	$ny = floor($oy * ($final_width_of_image / $ox));
  
	$nm = imagecreatetruecolor($nx, $ny);
	imagealphablending($nm,false);
	$transparentindex = imagecolorallocatealpha($nm,0,0,0,127);
    imagefill($nm,0,0,$transparentindex);
    imagesavealpha($nm, true);

	imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
  
  	switch ($type)
  		{
  		case 'jpg':
  			imagejpeg($nm, $filename, 100);
  		break;
  		case 'gif':
  			imagegif($nm, $filename);
  		break;
  		case 'png':
  			imagepng($nm, $filename, 0); 
  		break;
  		}

  	return true;
	}

function sendCallbackData($url,$data)
	{
	$postdata = http_build_query($data);

	$opts = array('http' =>
				    array(
				        'method'  => 'POST',
				        'header'  => 'Content-Type: application/x-www-form-urlencoded',
				        'content' => $postdata
				    )
				);

	$context  = stream_context_create($opts);
	$result = @file_get_contents($url, false, $context);

	$temp = @json_decode($result,true);
	if (!isset($temp['request'])) return false;
	else return $temp;
	}

function checkMerchant($url,$m_num,$m_api_key)
	{
	$ret = [];

	// sign
	$data = [
			'r_shop' => $m_num,
			'r_orderid' => 'test',
			'r_status' => PAY_SUCCESS,
			'r_sign' => 'wrong_sign'
			];
	$send_result = sendCallbackData($url,$data);
	if (@$send_result['answer']==ANSWER_ERR_SIGN) $ret['sign'] = true;
	else $ret['sign'] = false;

	// shop
	$arHash = array('wrong','test',PAY_SUCCESS,$m_api_key);
	$r_sign = strtoupper(sha1(implode(':', $arHash)));
	$data = [
			'r_shop' => 'wrong',
			'r_orderid' => 'test',
			'r_status' => PAY_SUCCESS,
			'r_sign' => $r_sign
			];
	$send_result = sendCallbackData($url,$data);
	if (@$send_result['answer']==ANSWER_ERR_SHOP) $ret['shop'] = true;
	else $ret['shop'] = false;		

	// wrong order id
	$arHash = array($m_num,'wrong',PAY_SUCCESS,$m_api_key);
	$r_sign = strtoupper(sha1(implode(':', $arHash)));
	$data = [
			'r_shop' => $m_num,
			'r_orderid' => 'wrong',
			'r_status' => PAY_SUCCESS,
			'r_sign' => $r_sign
			];
	$send_result = sendCallbackData($url,$data);
	if (@$send_result['answer']==ANSWER_ERR_ORDER_WRONG) $ret['orderid_wrong'] = true;
	else $ret['orderid_wrong'] = false;	

	// fail order id
	$arHash = array($m_num,'fail',PAY_SUCCESS,$m_api_key);
	$r_sign = strtoupper(sha1(implode(':', $arHash)));
	$data = [
			'r_shop' => $m_num,
			'r_orderid' => 'fail',
			'r_status' => PAY_SUCCESS,
			'r_sign' => $r_sign
			];
	$send_result = sendCallbackData($url,$data);
	if (@$send_result['answer']==ANSWER_ERR_ORDER_STATUS) $ret['orderid_fail'] = true;
	else $ret['orderid_fail'] = false;		

	// success order id
	$arHash = array($m_num,'test',PAY_SUCCESS,$m_api_key);
	$r_sign = strtoupper(sha1(implode(':', $arHash)));
	$data = [
			'r_shop' => $m_num,
			'r_orderid' => 'test',
			'r_status' => PAY_SUCCESS,
			'r_sign' => $r_sign
			];
	$send_result = sendCallbackData($url,$data);
	if (@$send_result['answer']==ANSWER_OK) $ret['orderid_success'] = true;
	else $ret['orderid_success'] = false;

	$total = true;
	foreach ($ret as $k=>$v) { if (!$v) { $total=false; break; } }

	$ret['total'] = $total;
//var_dump($ret); die();
	return $ret;	
	}

