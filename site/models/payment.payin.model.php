<?php

class PaymentPayinModel extends PaymentModel
	{
	function vars2()
		{
		if (!isset($_GET['pay'])) goRedir('/');

		$pay_id = (int)$_GET['pay'];
		$pd = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_IN]);

		if ($pd===false) goRedir('/');

		$this->all_vars['pay_id'] = $pd['pay_id'];

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

			// check ub
			$ub_id = $pd['ub_id'];
			$ub_data = $this->db->uni_select_one('users_bals',['ub_id'=>$ub_id]);
			if ($ub_data===false) goRedir('/');

			// ub lock
			if ($ub_data['ub_lock']==1) goRedir('/');

			// check bal
			$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ub_data['bal_id'],'bal_status_active'=>1]);
			if ($bal_data===false) goRedir('/');

			if ($bal_data['bal_status_payin']==0 || $bal_data['bal_payin_list']=='') goRedir('/');

			$ps_ids = explode(',',$bal_data['bal_payin_list']);
			$pss_in = [];
			foreach ($ps_ids as $ps_id)
				{
				$one_ps = $this->db->uni_select_one('paysys',['paysys_id'=>$ps_id]);
				$one_ps_bal_data = $this->db->uni_select_one('bals',['bal_id'=>$one_ps['bal_id']]);

				if ($one_ps_bal_data===false) continue;

				if ($one_ps['paysys_name']=='voucher||voucher') // для ваучеров оригина валюты
					{
					$need = $pd['pay_value'];
					$need_bal_name = $bal_data['bal_name'];
					}
				else // расчет в валюте которую хотим
					{
					$need = ($pd['pay_value'] + $pd['pay_com']) * $bal_data['bal_rate'] / $one_ps_bal_data['bal_rate']; 
					$need_bal_name = $one_ps_bal_data['bal_name'];
					}

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

			if ($ps_data['paysys_name']=='voucher||voucher') // для ваучера
				{
				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$pd['bal_id']]);
				$this->all_vars['need_bal_name'] = $bal_data['bal_name'];
				$this->all_vars['is_voucher'] = true;
				}
			else
				{
				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ps_data['bal_id']]);
				$this->all_vars['need_bal_name'] = $bal_data['bal_name'];
				$this->all_vars['is_voucher'] = false;
				}

			$json = json_decode($pd['pay_ps_data'],true);
			foreach ($json as $k=>$v) $this->all_vars['psd_'.$k] = $v;
			}
		}

	function ajax2()
		{
		if ($_GET['ajax']=='sendPayIn')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$paysys_id = (int)@$_POST['paysys_id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_IN]);
			$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$paysys_id]);
			
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
			else
				{
				$temp = explode('||',$ps_data['paysys_name']);
				$ps_name = $temp[0];
				$ps_var = $temp[1];

				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$pay_data['bal_id']]);
				$user_data = $this->db->uni_select_one('users',['user_id'=>$pay_data['user_id']]);

				$info = @$this->pss[$ps_name]->getInfo($ps_var);
				if ($info===false)
					{
					$this->ajax_return['info'] = 'Данный способ выплаты сейчас не доступен';
					}
				else
					{
					// генерим оплату
					$one_ps_bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ps_data['bal_id']]);
					 // расчет в валюте которую хотим

					if ($ps_data['paysys_name']=='voucher||voucher') // для ваучеров оригинал валюты
						{
						$need = $pay_data['pay_value'];
						}
					else // расчет в валюте которую хотим
						{
						$need = ($pay_data['pay_value'] + $pay_data['pay_com']) * $bal_data['bal_rate'] / $one_ps_bal_data['bal_rate'];
						}
					$need = cutZeros(number_format($need,10,'.',''));

					$user_name = $user_data['user_lname'] . ' ' . $user_data['user_fname'] . ' ' . $user_data['user_sname'];
					$pay_result_data = $this->pss[$ps_name]->genPaymentIn($ps_var,$pay_id,$need,$user_data['user_email'],$user_name);

					if ($pay_result_data['error']!='ok')
						{
						$this->db->logWrite(LOG_PAYSYS_IN_ERROR,arrToLines(['paysys'=>$ps_data['paysys_name'],'val'=>$need]),
																arrToLines($pay_result_data),ACC_USER,$user_data['user_id']);
						$this->ajax_return['info'] = 'Ошибка, возможно сейчас данный способ оплаты недоступен, обратитесь в поддержку';
						}
					else
						{
						$upd = [
								'pay_ps_data'	=>	json_encode($pay_result_data),
								'pay_status'	=>	PAY_STATUS_GO_PAY,
								'paysys_id'		=>	$paysys_id,
								];

						$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);

						$this->db->writeAdminAlerts('payin','Запрос на пополнение, ID:'.$pay_id.
													', пользователь ['.$user_data['user_id'].'] '.$user_data['user_email'].
													', сумма '.cutZeros($pay_data['pay_value']).' '.$bal_data['bal_name']);

						$this->db->logWrite(LOG_PAYIN_GOPAY,$pay_id,$ps_data['paysys_title'],ACC_USER,$user_data['user_id']);
						$this->ajax_return['result'] = true;
						}
					}
				}
			}

		if ($_GET['ajax']=='setPayDone')
			{
			$pay_id = (int)@$_POST['id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_IN]);
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

		if ($_GET['ajax']=='activateVoucher')
			{
			$pay_id = (int)@$_POST['id'];
			$code = trim(@$_POST['code']);

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_IN]);
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
			else if ($code=='')
				{
				$this->ajax_return['info'] = 'Код не введен';
				}
			else
				{
				$v_data = $this->db->uni_select_one('vouchers',['voucher_code'=>$code,'bal_id'=>$pay_data['bal_id'],'voucher_status'=>0]);
				if ($v_data===false)
					{
					$this->ajax_return['info'] = 'Ваучер не найден, проверьте код и валюту ваучера!';
					}
				else if ($pay_data['pay_value']>$v_data['voucher_value'])
					{
					$this->ajax_return['info'] = 'На балансе ваучера недостаточно средств';
					}
				else
					{
					// данные валюты
					$bal_data = $this->db->getBalData($pay_data['bal_id']);

					// списываем деньги с ваучера
					$this->db->activateVoucher($v_data['voucher_id'],$pay_data['pay_value']);

					// меняем баланс юзера
					$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],
												REASON_VOUCHER_PAY,$v_data['voucher_id']);

					// остатки
					$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);

					// обновляем заявку
					$this->db->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_DONE]);

					// логи
					$this->db->logWrite(LOG_PAY_VOUCHER, $v_data['voucher_id'], $pay_id, ACC_USER, $pay_data['user_id']);

					// алерты
					$this->db->writeAdminAlerts('payin','Платеж ID:'.$pay_id.' оплачен ваучером ID:'.$v_data['voucher_id'].
														' на сумму '.$pay_data['pay_value'].' '.$bal_data['bal_name']);

					// ok
					$this->ajax_return['result']  = true;
					}
				}
			}
		}

	}