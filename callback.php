<?

error_reporting(E_ALL);
session_start();
define('COINCASH',true);
if (!isset($_GET['ps'])) die();

require_once( __DIR__ . '/core/includes/init.php');

$BASE = new BASE($config['db']);

require_once( __DIR__ . '/core/includes/paysys.init.php');

$ps_name = $_GET['ps'];
if (isset($PAYSYS[$ps_name]))
	{
	$fn = _ROOT_.'/tmp/'.uniqid().'_'.time().'.txt';
	file_put_contents($fn, $_SERVER['HTTP_HMAC']."\r\n\r\n".json_encode($_POST)."\r\n\r\n");

	$result = $PAYSYS[$ps_name]->confirmPayment();

	file_put_contents($fn, json_encode($result), FILE_APPEND);

	if ($result['result'])
		{
		$pay_id = $result['pay_id'];
		$pay_data = $BASE->getOnePayment($pay_id);

		if ($result['status']==PAY_STATUS_REJECT) // отклоняем платеж
			{
			$BASE->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_REJECT]);
			$BASE->logWrite(LOG_PAY_NEW_STATUS, '#'.$pay_id.':'.payStatusText($pay_data['pay_status']),
												'#'.$pay_id.':'.payStatusText(PAY_STATUS_REJECT),ACC_USER,$pay_data['user_id']);
			}
		else if ($result['status']==PAY_STATUS_PENDING) // обрабатывается
			{
			$BASE->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_PENDING]);
			$BASE->logWrite(LOG_PAY_NEW_STATUS, '#'.$pay_id.':'.payStatusText($pay_data['pay_status']),
												'#'.$pay_id.':'.payStatusText(PAY_STATUS_PENDING),ACC_USER,$pay_data['user_id']);
			}
		else if ($result['status']==PAY_STATUS_PAYS) // готово
			{
			$bal_data = $BASE->getBalFull($pay_data['bal_id']);

			if ($pay_data['pay_type']==PAY_TYPE_ORDER) // ОПЛАТА ЗАКАЗА
				{
				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
				$order_id = $pay_ps_data['order_id'];
				$m_num = $pay_ps_data['m_num'];

				$m_data = $BASE->uni_select_one('merchants',['m_num'=>$m_num]);
				$order_data = $BASE->uni_select_one('orders',['order_id'=>$order_id]);

				// авто режим
				if ($bal_data['bal_payin_auto']==1) 
					{
					// work with money
					$BASE->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_ORDER_PAY,$pay_id);
					$BASE->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

					// по процентам
					if ($m_data['m_comm_who']==0) // продавец платит %
						{
						$BASE->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_OUT,$pay_data['pay_com'],REASON_ORDER_COM,$pay_id);
						}

					// алерты
					$BASE->writeAdminAlerts('order','Оплата заказа №'.$order_id.' оплачен и проведен');
					$BASE->writeUserAlerts($pay_data['user_id'],'order','Оплата заказа №'.$order_id.', выполнено, сумма зачислена на баланс');

					// на колбэк
					$BASE->writeCallback($pay_data['user_id'],$m_num,$order_id,PAY_SUCCESS);

					$new_status = PAY_STATUS_DONE;
					$new_order_status = ORDER_STATUS_DONE;
					}
				// ручной режим
				else 
					{
					// алерты
					$BASE->writeAdminAlerts('order','Оплата заказа №'.$order_id.' ожидает подтверждения');	
					$BASE->writeUserAlerts($pay_data['user_id'],'order','Оплата заказа №'.$order_id.', средства получены, сумма будет зачислена на баланс в ближайшее время');

					$new_status = PAY_STATUS_PAYS;
					$new_order_status = ORDER_STATUS_PAYS;
					}

				// обновляем данные платежа
				$BASE->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>$new_status]);
				$BASE->logWrite(LOG_PAY_NEW_STATUS, '#'.$pay_id.':'.payStatusText($pay_data['pay_status']),
													'#'.$pay_id.':'.payStatusText($new_status),ACC_USER,$pay_data['user_id']);

				// обновляем статус заказа
				$BASE->uni_update('orders',['order_id'=>$order_id],['order_status'=>$new_order_status]);
				$BASE->logWrite(LOG_ORDER_NEW_STATUS, '#'.$order_id.':'.orderStatusText(ORDER_STATUS_NEW),
													  '#'.$order_id.':'.orderStatusText($new_order_status),ACC_USER,$pay_data['user_id']);			
				}
			else if ($pay_data['pay_type']==PAY_TYPE_IN) // ПОПОЛНЕНИЕ
				{
				// авто режим
				if ($bal_data['bal_payin_auto']==1) 
					{
					// work with money
					$BASE->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_PAYMENT,$pay_id);

					$BASE->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

					// алерты
					$BASE->writeAdminAlerts('payin','Пополнение ID:'.$pay_id.' на сумма '.$pay_data['pay_value'].' '.$bal_data['bal_name'].' оплачен и проведен');
					$BASE->writeUserAlerts($pay_data['user_id'],'payin','Пополнение ID:'.$pay_id.' выполнено, сумма зачислена на баланс');

					$new_status = PAY_STATUS_DONE;
					}
				// ручной режим
				else 
					{
					// алерты
					$BASE->writeAdminAlerts('payin','Пополнение ID:'.$pay_id.' на сумма '.$pay_data['pay_value'].' '.$bal_data['bal_name'].', ожидает подтверждения');	
					$BASE->writeUserAlerts($pay_data['user_id'],'payin','Пополнение ID:'.$pay_id.' средства получены, сумма будет зачислена на баланс в ближайшее время');

					$new_status = PAY_STATUS_PAYS;
					}

				$BASE->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>$new_status]);
				$BASE->logWrite(LOG_PAY_NEW_STATUS, '#'.$pay_id.':'.payStatusText($pay_data['pay_status']),
													'#'.$pay_id.':'.payStatusText($new_status),ACC_USER,$pay_data['user_id']);
				}
			else if ($pay_data['pay_type']==PAY_TYPE_CHANGE_OUT) // ОБМЕН НА САЙТЕ
				{
				// авто режим
				if ($bal_data['ch_out_auto']==1)
					{
					$new_status = PAY_STATUS_DONE;
					}
				// ручной режим
				else
					{
					$new_status = PAY_STATUS_PAYS;
					}

				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
				$from_data = $this->db->getBalData($pay_data['pay_ch_bal_id']);

				$BASE->writeAdminAlerts('changeout','Обмен на сайте, ID:'.$pay_id.' на сумма '.$pay_data['pay_ch_value'].' '.$from_data['bal_name'].
													' оплачен, ожидает проведения обмена на сумму '.$pay_data['pay_value'].' '.$bal_data['bal_name']);	

				$BASE->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>$new_status]);
				}
			}

		die('OK');
		}
	else
		{
		die('ERROR: '.$result['error']);
		}
	}

?>