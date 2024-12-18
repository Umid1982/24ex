<?php

class PaymentOrderModel extends PaymentModel
	{
	function vars2()
		{
		if (!isset($_GET['pay'])) goRedir('/');

		$pay_id = (int)$_GET['pay'];
		$pd = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_ORDER]);

		if ($pd===false) goRedir('/');

		$ps_data = json_decode($pd['pay_ps_data'],true);
		if (!isset($ps_data['m_num']) || !isset($ps_data['order_id'])) goRedir('/');

		$m_num = $ps_data['m_num'];
		$order_id = (int)$ps_data['order_id'];
		$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
		$order_data = $this->db->uni_select_one('orders',['order_id'=>$order_id]);
		if ($m_data===false || $order_data===false) goRedir('/');	

		$this->all_vars['pay_id'] = $pd['pay_id'];
		$this->all_vars['m_num'] = $m_num;
		$this->all_vars['order_id'] = $order_id;

		$order_data['order_amount'] = cutZeros($order_data['order_amount']);
		$order_data['order_com'] = cutZeros($order_data['order_com']);
		foreach ($order_data as $k=>$v) $this->all_vars[$k] = $v;
		foreach ($m_data as $k=>$v) $this->all_vars[$k] = $v;

		$is_new = in_array($pd['pay_status'], [PAY_STATUS_NEW,PAY_STATUS_GO_PAY]);

		$this->all_vars['pay_id'] = $pay_id;

		if ( ($is_new && time()>$pd['pay_end']) || !$is_new )
			{
			// просрочено, либо другой статус
			$this->all_vars['step'] = 3;

			if ($is_new && time()>$pd['pay_end'])
				{
				$this->all_vars['expired'] = true;
				}
			else
				{
				$this->all_vars['expired'] = false;
				$this->all_vars['pay_status'] = $pd['pay_status'];
				}
			}
		else if ($pd['pay_status']==PAY_STATUS_NEW)
			{
			// первый шаг, выбор платежной системы
			$this->all_vars['step'] = 1;

			if ($m_data['m_is_moder']!=1) goRedir('/');

			$ps_ids = explode('|',$m_data['m_pss']);
			$pss_in = [];
			foreach ($ps_ids as $ps_id)
				{
				$one_ps = $this->db->uni_select_one('paysys',['paysys_id'=>$ps_id,'paysys_type'=>'in','paysys_merch'=>1]);
				if ($one_ps===false) continue;

				$one_ps_bal_data = $this->db->uni_select_one('bals',['bal_id'=>$one_ps['bal_id']]);
				if ($one_ps_bal_data===false) continue;

				if ($one_ps['paysys_name']=='voucher||voucher') continue;

				$com = ($m_data['m_comm_who']==0) ? 0 : $order_data['order_com']; // 0 - продавец платит, 1 - покупатель

				$need = ($order_data['order_amount'] + $com) / $one_ps_bal_data['bal_rate']; 
				$need_bal_name = $one_ps_bal_data['bal_name'];

				$one_ps['value_need'] = cutZeros(number_format($need,10,'.',''));
				$one_ps['bal_name'] = $need_bal_name;

				$one_ps['paysys_icon'] = getBalIcon($one_ps['paysys_icon']);

				$pss_in[] = $one_ps;
				}
			if (count($pss_in)==0) goRedir('/');

			$this->all_vars['pss_in'] = $pss_in;
			}
		else if ($pd['pay_status']==PAY_STATUS_GO_PAY)
			{
			// уже выбрана система, начинаем ввод данных по оплате
			$this->all_vars['step'] = 2;

			$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$pd['paysys_id']]);
			$this->all_vars['paysys_title'] = $ps_data['paysys_title'];
			$this->all_vars['paysys_name'] = $ps_data['paysys_name'];
			$this->all_vars['paysys_icon'] = getBalIcon($ps_data['paysys_icon']);

			$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ps_data['bal_id']]);
			$this->all_vars['need_bal_name'] = $bal_data['bal_name'];
			$this->all_vars['is_voucher'] = false;

			$json = json_decode($pd['pay_ps_data'],true);
			foreach ($json as $k=>$v) $this->all_vars['psd_'.$k] = $v;
			}
		}

	function ajax2()
		{
		if ($_GET['ajax']=='sendOrder')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$paysys_id = (int)@$_POST['paysys_id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_ORDER]);
			$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$paysys_id]);

			$pay_ps_data = @json_decode($pay_data['pay_ps_data'],true);
			
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}			
			else if ($ps_data===false)
				{
				$this->ajax_return['info'] = 'Неверный способ вывода';
				}
			else if ($pay_data['pay_status'] != PAY_STATUS_NEW)
				{
				$this->ajax_return['info'] = 'Неверный статус платежа';
				}
			else if ($pay_data['pay_end'] < time())
				{
				$this->ajax_return['info'] = 'Время оплаты заявки прошло, создайте новую заявку';
				}
			else if (!isset($pay_ps_data['m_num']) || !isset($pay_ps_data['order_id']))
				{
				$this->ajax_return['info'] = 'Неверные платежные данные';
				}
			else
				{
				$order_id = $pay_ps_data['order_id'];
				$m_num = $pay_ps_data['m_num'];

				$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
				$order_data = $this->db->uni_select_one('orders',['order_id'=>$order_id]);

				if ($order_data===false || @$order_data['order_status']!=0 || $m_data===false || @$m_data['m_is_moder']!=1)
					{
					$this->ajax_return['info'] = 'Неверные платежные данные';
					}
				else
					{
					$temp = explode('||',$ps_data['paysys_name']);
					$ps_name = $temp[0];
					$ps_var = $temp[1];

					$bal_id = $ps_data['bal_id'];
					$user_id = $pay_data['user_id'];
					$ub_data = $this->db->getUbByBal($user_id,$bal_id);

					if ($ub_data===false)
						{
						$this->ajax_return['info'] = 'Невозможно принять данную валюту';
						}
					else
						{
						$ub_id = $ub_data['ub_id'];

						$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);
						$user_data = $this->db->uni_select_one('users',['user_id'=>$user_id]);

						$info = @$this->pss[$ps_name]->getInfo($ps_var);
						if ($info===false)
							{
							$this->ajax_return['info'] = 'Данный способ выплаты сейчас не доступен';
							}
						else if ($ps_data['paysys_name']=='voucher||voucher')
							{
							$this->ajax_return['info'] = 'Нельзя оплачивать ваучером';
							}
						else
							{
							// генерим оплату
							$one_ps_bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ps_data['bal_id']]);
							 // расчет в валюте которую хотим

							$need_com = cutZeros(number_format($order_data['order_com'] / $one_ps_bal_data['bal_rate'],10,'.',''));
							$need_value = cutZeros(number_format($order_data['order_amount'] / $one_ps_bal_data['bal_rate'],10,'.',''));

							if ($m_data['m_comm_who']==0) $need_total = $need_value;
							else $need_total = $need_value + $need_com;

							$user_name = $user_data['user_lname'] . ' ' . $user_data['user_fname'] . ' ' . $user_data['user_sname'];
							$pay_result_data = $this->pss[$ps_name]->genPaymentIn($ps_var,$pay_id,$need_total,$user_data['user_email'],$user_name);

							if ($pay_result_data['error']!='ok')
								{
								$this->db->logWrite(LOG_PAYSYS_IN_ERROR,arrToLines(['paysys'=>$ps_data['paysys_name'],'val'=>$need]),
																		arrToLines($pay_result_data),ACC_USER,$user_data['user_id']);
								$this->ajax_return['info'] = 'Ошибка, возможно сейчас данный способ оплаты недоступен, обратитесь в поддержку';
								}
							else
								{
								$pay_ps_data = $pay_result_data;
								$pay_ps_data['m_num'] = $m_num;
								$pay_ps_data['order_id'] = $order_id;

								$upd = [
										'bal_id'		=> $bal_id,
										'ub_id'			=> $ub_id,

										'pay_value'		=> $need_value,
										'pay_com'		=> $need_com,

										'pay_ps_data'	=>	json_encode($pay_ps_data),
										'pay_status'	=>	PAY_STATUS_GO_PAY,
										'paysys_id'		=>	$paysys_id,
										];

								$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);

								$this->db->writeAdminAlerts('order','Оплата заказа, № заказа:'.$order_id.', ID платежа:'.$pay_id.
															', сумма '.$need_value.' '.$one_ps_bal_data['bal_name']);

								$this->db->logWrite(LOG_ORDER_GOPAY,$order_id,$ps_data['paysys_title'],ACC_UNAUTH);
								$this->ajax_return['result'] = true;
								}
							}
						}
					}
				}
			}

		if ($_GET['ajax']=='resetPaysysOrder')
			{
			$pay_id = (int)@$_POST['id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_ORDER]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			else if ($pay_data['pay_status']!=PAY_STATUS_GO_PAY)
				{
				$this->ajax_return['info'] = 'Нельзя изменить способ оплаты для данной заявки';
				}
			else if ($pay_data['pay_end'] < time())
				{
				$this->ajax_return['info'] = 'Время оплаты заявки прошло, создайте новую заявку';
				}
			else
				{
				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
				$new_pay_ps_data = ['m_num'=>$pay_ps_data['m_num'],'order_id'=>$pay_ps_data['order_id']];

				$upd = [
						'pay_ps_data'	=>	json_encode($new_pay_ps_data),
						'pay_status'	=>	PAY_STATUS_NEW,
						'paysys_id'		=>	NULL,
						'bal_id'		=>	NULL,
						'ub_id'			=>	NULL,
						'pay_value'		=>	0,
						'pay_com'		=>	0,
						];

				$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);
				$this->ajax_return['result'] = true;
				}
			}

		if ($_GET['ajax']=='setPayDoneOrder')
			{
			$pay_id = (int)@$_POST['id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_ORDER]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			else if ($pay_data['pay_status']!=PAY_STATUS_GO_PAY)
				{
				$this->ajax_return['info'] = 'Данный платеж уже был подтвержден';
				}
			else if ($pay_data['pay_end'] < time())
				{
				$this->ajax_return['info'] = 'Время оплаты заявки прошло, создайте новую заявку или обратитесь в поддержку';
				}
			else
				{
				$upd = [
						'pay_status' =>	PAY_STATUS_USER_PAYS,
						];

				$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);
				$this->ajax_return['result'] = true;
				}
			}
		}

	}