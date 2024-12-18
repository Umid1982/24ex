<?php

class PaymentPayoutModel extends PaymentModel
	{
	function vars2()
		{
		// выплаты только авторизованным
		if (!$this->isAuth()) goRedir('/auth/');

		if (!isset($_GET['pay'])) goRedir('/');

		$pay_id = (int)$_GET['pay'];
		$pd = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_OUT]);

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

			if ($bal_data['bal_status_payout']==0 || $bal_data['bal_payout_list']=='') goRedir('/');

			$ps_ids = explode(',',$bal_data['bal_payout_list']);
			$pss_out = [];
			foreach ($ps_ids as $ps_id)
				{
				$one_ps = $this->db->uni_select_one('paysys',['paysys_id'=>$ps_id]);
				$one_ps_bal_data = $this->db->uni_select_one('bals',['bal_id'=>$one_ps['bal_id']]);

				if ($one_ps_bal_data===false) continue;

				$need = $pd['pay_value'] * $bal_data['bal_rate'] / $one_ps_bal_data['bal_rate']; 
				$need_bal_name = $one_ps_bal_data['bal_name'];

				$one_ps['value_need'] = cutZeros(number_format($need,10,'.',''));
				$one_ps['bal_name'] = $need_bal_name;

				$one_ps['paysys_icon'] = getBalIcon($one_ps['paysys_icon']);

				$pss_out[] = $one_ps;
				}
			if (count($pss_out)==0) goRedir('/');

			$this->all_vars['pss_out'] = $pss_out;
			}
		else if ($pd['pay_status']==PAY_STATUS_GO_PAY)
			{
			// уже выбрана система, начинаем ввод данных по выплате
			$this->all_vars['step'] = 2;

			$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$pd['paysys_id']]);
			$this->all_vars['paysys_title'] = $ps_data['paysys_title'];
			$this->all_vars['paysys_name'] = $ps_data['paysys_name'];
			$this->all_vars['paysys_icon'] = getBalIcon($ps_data['paysys_icon']);

			$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ps_data['bal_id']]);
			$this->all_vars['need_bal_name'] = $bal_data['bal_name'];

			$this->all_vars['pay_value'] = cutZeros($pd['pay_value']);

			// платежный пароль
			$this->all_vars['need_paypass'] = $this->db->isNeedPayPass($this->userId());
			}
		}

	function ajax2()
		{
		if ($_GET['ajax']=='sendPayOut')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$paysys_id = (int)@$_POST['paysys_id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id]);
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

					$need = $pay_data['pay_value'] * $bal_data['bal_rate'] / $one_ps_bal_data['bal_rate'];
					$need = cutZeros(number_format($need,10,'.',''));

					$user_name = $user_data['user_lname'] . ' ' . $user_data['user_fname'] . ' ' . $user_data['user_sname'];
					$pay_result_data = $this->pss[$ps_name]->genPaymentOut($ps_var,$pay_id,$need,$user_data['user_email'],$user_name);

					if ($pay_result_data['error']!='ok')
						{
						$this->db->logWrite(LOG_PAYSYS_OUT_ERROR,arrToLines(['paysys'=>$ps_data['paysys_name'],'val'=>$need]),
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

						$this->db->logWrite(LOG_PAYOUT_GOPAY,$pay_id,$ps_data['paysys_title'],ACC_USER,$user_data['user_id']);
						$this->ajax_return['result'] = true;
						}
					}
				}
			}

		if ($_GET['ajax']=='sendPayProps')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$props = trim(@$_POST['payout_props']);
			$pp = trim(@$_POST['pay_pass']);

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id]);

			if ($props=='')
				{
				$this->ajax_return['info'] = 'Введите реквизиты!';
				}
			else if ($pay_data===false)
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
			else if (!$this->db->checkPayPass($this->userId(),$pp))
				{
				$this->ajax_return['info'] = 'Неверный платежный пароль';
				}
			else
				{
				$user_data = $this->db->uni_select_one('users',['user_id'=>$pay_data['user_id']]);
				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$pay_data['bal_id']]);

				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
				$pay_ps_data['props'] = $props;

				$upd = [
						'pay_status' 	=> PAY_STATUS_SEND_PROPS,
						'pay_ps_data' 	=> json_encode($pay_ps_data)
						];

				$this->db->writeAdminAlerts('payout','Запрос на вывод, ID:'.$pay_id.
											', пользователь ['.$user_data['user_id'].'] '.$user_data['user_email'].
											', сумма '.cutZeros($pay_data['pay_value']).' '.$bal_data['bal_name'].
											', реквизиты: '.$props);

				$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);
				$this->ajax_return['result'] = true;
				}
			}
		}

	}