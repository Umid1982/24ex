<?php

class PaymentsModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function vars()
		{
		$this->all_vars['all_users'] = $this->db->uni_select('users');
		}

	function ajax()
		{
		if ($_GET['ajax']=='getPayments')
			{
			$bal_types = $this->db->getBalTypes();

			$pays = $this->db->getPayments($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,
						@$this->ajax_qs['user_id'],@$this->ajax_qs['type'],@$this->ajax_qs['status']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			$all_bals = $this->db->getAllBals();
			$all_bals[0] = ['bal_name'=>'$'];

			foreach ($pays as $k=>$v)
				{
				$v['bal_id'] = (int)$v['bal_id'];

				if ($v['user_id']==null)
					{
					$pays[$k]['user_email'] = false;
					}
				else
					{
					$user_data = $this->db->uni_select_one('users',['user_id'=>$v['user_id']]);
					$pays[$k]['user_email'] = $user_data['user_email'];
					}

				$pays[$k]['pay_type_line'] = payTypeText($v['pay_type']);
				$pays[$k]['bal_name'] = $all_bals[$v['bal_id']]['bal_name'];
				$pays[$k]['pay_dt_line'] = date('Y-m-d H:i',$v['pay_dt']);
				$pays[$k]['pay_value'] = cutZeros($v['pay_value']);
				$pays[$k]['pay_status_line'] = payStatusText($v['pay_status']);

				if (in_array($v['pay_status'], [PAY_STATUS_NEW,PAY_STATUS_IN_WORK,PAY_STATUS_PAYS,PAY_STATUS_USER_PAYS,
					PAY_STATUS_SEND_PROPS,PAY_STATUS_PENDING,PAY_STATUS_GO_PAY]) && $v['pay_end']<time())
					{
					$pays[$k]['expired'] = true;
					}
				else
					{
					$pays[$k]['expired'] = false;
					}

				}

			$this->ajax_return['data'] = $pays;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if ($_GET['ajax']=='getPaymentDetails')
			{
			$pay_id = (int)$_POST['pay_id'];
			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id]);

			if ($pay_data!==false)
				{
				$ori_bal_data = $this->db->getBalData($pay_data['bal_id']);

				$this->ajax_return['ava_statuses'] = [];
				$pids = avaStatuses($pay_data['pay_status']);
				if (count($pids)>0) foreach ($pids as $pid) $this->ajax_return['ava_statuses'][] = ['id'=>$pid,'title'=>payStatusText($pid)];

				$psd = @json_decode($pay_data['pay_ps_data'],true);

				if ($pay_data['pay_type']==PAY_TYPE_CHANGE_IN || $pay_data['pay_type']==PAY_TYPE_CHANGE_OUT)
					{
					$bal_data = $this->db->getBalData($psd['pay_ch_bal_id']);
					$ps_data = '<b>Отдает</b> - '.cutZeros($psd['pay_ch_value']).' '.$bal_data['bal_name'];
					$ps_data .= '<br><b>Получает</b> - '.cutZeros($pay_data['pay_value']).' '.$ori_bal_data['bal_name'];

					if (isset($psd['props'])) $ps_data .= '<br><b>На реквизиты</b> - '.$psd['props'];
					}
				else
					{
					$psd_temp = [];
					if (@count($psd)>0) foreach ($psd as $k=>$v)
						{
						$und = false;
						$top = true;

						switch($k)
							{
							case 'error': 			continue 2; break;
							case 'change_bal_id': 	continue 2; break;
							case 'props': 			$k = 'Реквизиты'; $und = true; break;
							case 'change_val': 		$k = 'Сумма при обмене'; $v = cutZeros($v); break;
							case 'change_com': 		$k = 'Комиссия за обмен'; $v = cutZeros($v); break;
							case 'pay_ch_value': 	$k = 'Сумма зачисления'; $v = cutZeros($v); $und = true; break;
							case 'pay_ch_bal_id': 	$k = 'Баланс зачисления'; $und = true; break;
							case 'm_num': 			$k = 'ID магазина'; $und = true; break;
							case 'order_id': 		$k = 'ID заказа'; $und = true; break;
							default: 				$k = ucfirst($k);  $top = false; break;
							}

						$line = '<b>'.$k.'</b> - '.$v;
						if ($und) $line = '<u>'.$line.'</u>';

						if (!$top) $psd_temp[] = $line;
						else array_unshift($psd_temp,$line);
						}

					$ps_data = implode("<br>",$psd_temp);
					}

				$this->ajax_return['pay_data'] = $pay_data;
				$this->ajax_return['pay_data']['pay_ps_data'] = $ps_data;
				$this->ajax_return['result'] = true;
				}
			}

		if ($_GET['ajax']=='savePayDetails')
			{
			$pay_id = (int)$_POST['pay_id'];
			$status = trim(htmlspecialchars($_POST['status']));
			$adm_comm = trim(htmlspecialchars($_POST['adm_comm']));

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id]);
			if ($pay_data!==false)
				{
				$upd = [];
				$upd['pay_comm_admin'] = $adm_comm;

				if ($status!='')
					{
					$pids = avaStatuses($pay_data['pay_status']);
					if (in_array($status,$pids))
						{
						try
							{
							$this->db->trans_begin();

							// пейауты
							if ($pay_data['pay_type']==PAY_TYPE_OUT && ($status==PAY_STATUS_CANCEL || $status==PAY_STATUS_REJECT))
								{
								// возвращаем деньги на счет
								$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_BACK_PAY,$pay_id);
								$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_com'],REASON_BACK_COM,$pay_id);

								$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

								// алерты
								$this->db->writeUserAlerts($pay_data['user_id'],'payout','Вывод средств ID:'.$pay_id.' отклонен, сумма возвращена на баланс');
								}
							// пейины
							else if ($pay_data['pay_type']==PAY_TYPE_IN && $status==PAY_STATUS_DONE)
								{
								// зачисляем деньги на счет
								$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_PAYMENT,$pay_id);

								$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

								// алерты
								$this->db->writeUserAlerts($pay_data['user_id'],'payin','Пополнение средств ID:'.$pay_id.' выполнено, сумма зачислена на баланс');
								}
							// заказы из магазинов
							else if ($pay_data['pay_type']==PAY_TYPE_ORDER)
								{
								$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
								$order_id = $pay_ps_data['order_id'];
								$m_num = $pay_ps_data['m_num'];

								$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
								$order_data = $this->db->uni_select_one('orders',['order_id'=>$order_id]);

								if ($status==PAY_STATUS_DONE)
									{
									// зачисляем деньги на счет
									$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_ORDER_PAY,$pay_id);

									$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

									// по процентам
									if ($m_data['m_comm_who']==0) // продавец платит %
										{
										$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_OUT,$pay_data['pay_com'],REASON_ORDER_COM,$pay_id);
										}

									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'order','Оплата заказа №'.$order_id.', выполнено, сумма зачислена на баланс');

									// на колбэк
									$this->db->writeCallback($pay_data['user_id'],$m_num,$order_id,PAY_SUCCESS);

									// обновляем статус заказа
									$this->db->uni_update('orders',['order_id'=>$order_id],['order_status'=>ORDER_STATUS_DONE]);
									$this->db->logWrite(LOG_ORDER_NEW_STATUS, '#'.$order_id.':'.orderStatusText(ORDER_STATUS_NEW),
																		  	  '#'.$order_id.':'.orderStatusText(ORDER_STATUS_DONE),
																		  	  ACC_ADMIN,$this->adminId());
									}
								else if ($status==PAY_STATUS_CANCEL || $status==PAY_STATUS_REJECT)
									{
									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'order','Заказ №'.$order_id.', оплата отлонена администратором');
									
									// на колбэк
									$this->db->writeCallback($pay_data['user_id'],$m_num,$order_id,PAY_REJECT);

									// обновляем статус заказа
									$this->db->uni_update('orders',['order_id'=>$order_id],['order_status'=>ORDER_STATUS_CANCEL]);
									$this->db->logWrite(LOG_ORDER_NEW_STATUS, '#'.$order_id.':'.orderStatusText(ORDER_STATUS_NEW),
																		  	  '#'.$order_id.':'.orderStatusText(ORDER_STATUS_CANCEL),
																		  	  ACC_ADMIN,$this->adminId());
									}
								}
							// обмены внутренние
							else if ($pay_data['pay_type']==PAY_TYPE_CHANGE_IN)
								{
								if ($status==PAY_STATUS_DONE)
									{
									// подтверждение платежа
									$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_CHANGEIN_PAY,$pay_id);
								
									$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'payin','Обмен средств ID:'.$pay_id.' выполнен, сумма зачислена на баланс');
									}
								else if ($status==PAY_STATUS_CANCEL || $status==PAY_STATUS_REJECT)
									{
									// отмена платежа
									$psd = json_decode($pay_data['pay_ps_data'],true);
									$this->db->changeUserBal($pay_data['user_id'],$psd['pay_ch_ub_id'],PS_TYPE_IN,$psd['pay_ch_value'],REASON_CHANGEIN_BACK_PAY,$pay_id);
									$this->db->changeUserBal($pay_data['user_id'],$psd['pay_ch_ub_id'],PS_TYPE_IN,$pay_data['pay_com'],REASON_CHANGEIN_BACK_COM,$pay_id);

									$this->db->changeChValue($psd['pay_ch_bal_id'],'plus',$psd['pay_ch_value'],$pay_data['user_id']);

									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'payin','Обмен средств ID:'.$pay_id.' отклонен, сумма возвращена на баланс');
									}
								}
							// обмены внешние
							else if ($pay_data['pay_type']==PAY_TYPE_CHANGE_OUT)
								{
								if ($status==PAY_STATUS_DONE)
									{
									// подтверждение платежа
									}
								else if ($status==PAY_STATUS_CANCEL || $status==PAY_STATUS_REJECT)
									{
									// отмена платежа
									}
								}
							// переводы
							else if ($pay_data['pay_type']==PAY_TYPE_TRANSFER)
								{
								if ($status==PAY_STATUS_DONE)
									{
									// подтверждение платежа
									$temp = json_decode($pay_data['pay_ps_data'],true);

									$user_bal_num = $temp['props'];
									$user_data_to = $this->db->uni_select_one('users',['user_bal_num'=>$user_bal_num]);
									$ub_data_to = $this->db->getOrCreateUB($user_data_to['user_id'],$pay_data['bal_id'],true);

									if (isset($temp['change_val']))
										{
										$to_value = $temp['change_val'];
										}
									else
										{
										$to_value = $pay_data['pay_value'];
										}

									// зачисляем деньги
									$this->db->changeUserBal($ub_data_to['user_id'],$ub_data_to['ub_id'],PS_TYPE_IN,$to_value,REASON_TRANSFER_GET_PAY,$pay_id);

									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'payin','Перевод ID:'.$pay_id.' подтвержден, сумма зачислена на баланс');
									}
								else if ($status==PAY_STATUS_CANCEL || $status==PAY_STATUS_REJECT)
									{
									// отмена платежа
									$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_TRANSFER_BACK_PAY,$pay_id);
									$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_com'],REASON_TRANSFER_BACK_COM,$pay_id);

									$temp = json_decode($pay_data['pay_ps_data'],true);
									if (isset($temp['change_com']))
										{
										$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$temp['change_com'],REASON_CHANGEIN_BACK_COM,$pay_id);
										}

									// алерты
									$this->db->writeUserAlerts($pay_data['user_id'],'payin','Перевод ID:'.$pay_id.' отменен, сумма возвращена на баланс');
									}
								}

							$upd['pay_status'] = $status;

							$this->db->logWrite(LOG_PAY_NEW_STATUS, '#'.$pay_id.':'.payStatusText($pay_data['pay_status']),'#'.$pay_id.':'.payStatusText($status),
												ACC_ADMIN,$this->adminId());

							$this->db->trans_commit();
							}
						catch (Exception $e)
							{
							$this->db->trans_rollback();
							}

						}
					}

				$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);

				$this->ajax_return['pay_data'] = $pay_data;
				$this->ajax_return['result'] = true;	

				if ($pay_data['pay_comm_admin']!=$adm_comm)
					{
					$this->db->logWrite(LOG_PAY_NEW_ADM_COMM,'#'.$pay_id.':'.$pay_data['pay_comm_admin'],'#'.$pay_id.':'.$adm_comm,ACC_ADMIN,$this->adminId());
					}
				}
			else
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			}
		}
	}