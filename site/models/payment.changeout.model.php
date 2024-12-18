<?php

class PaymentChangeoutModel extends PaymentModel
	{

	function vars2()
		{
		global $config;

		// ШАГ 1: ВЫБОР НАПРАВЛЕНИЙ
		if (!isset($_GET['pay']))
			{
			$this->all_vars['step'] = 1;

			$chs = $this->db->getAllChs('out',false,false,true);
			$ubs = $this->db->getUBs($this->userId());

			$need = [
					'bal_id','bal_title','bal_name','bal_icon','bal_rate',
					'ch_value','ch_out_status','ch_out_list','ch_out_com','ch_out_min','ch_out_max', 'ch_out_ps_in', 'ch_out_ps_out',
					];

			$ready = [];
			foreach ($chs as $k=>$v)
				{
				$one_ready = [];

				if ($v['ch_value']<=0) $v['ch_value'] = 0;

				foreach ($need as $one_need) $one_ready[$one_need] = $v[$one_need];
				$ready[$v['bal_id']] = $one_ready;
				}

			$this->all_vars['changes_json'] = json_encode($ready);
			}
		else
			{
			$pay_id = (int)$_GET['pay'];
			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_CHANGE_OUT]);
			if ($pay_data===false) goRedir('/payment/changeout');

			foreach ($pay_data as $k=>$v) $this->all_vars[$k] = $v;
			$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
			foreach ($pay_ps_data as $k=>$v) $this->all_vars['ps_data_'.$k] = $v;

			// ШАГ 2: ОПЛАТА
			if ($pay_data['pay_status']==PAY_STATUS_GO_PAY && time()<=$pay_data['pay_end'])
				{
				$this->all_vars['step'] = 2;

				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
				foreach ($pay_ps_data as $k=>$v) $this->all_vars['psd_'.$k] = $v;

				$from_bal_id = $pay_ps_data['pay_ch_bal_id'];
				$to_bal_id = $pay_data['bal_id'];

				$from_bal_data = $this->db->getBalData($from_bal_id);
				foreach ($from_bal_data as $k=>$v) $this->all_vars['from_'.$k] = $v;
				$this->all_vars['from_bal_icon'] = getBalIcon($from_bal_data['bal_icon']);

				$to_bal_data = $this->db->getBalData($to_bal_id);
				foreach ($to_bal_data as $k=>$v) $this->all_vars['to_'.$k] = $v;
				$this->all_vars['to_bal_icon'] = getBalIcon($to_bal_data['bal_icon']);
				}
			// ШАГ 3: СТАТУС ИЛИ ПРОСРОЧЕНО
			else
				{
				// просрочено, либо другой статус
				$this->all_vars['step'] = 3;

				if ($pay_data['pay_status']==PAY_STATUS_NEW && time()>$pay_data['pay_end'])
					{
					$this->all_vars['expired'] = true;
					}
				else
					{
					$this->all_vars['expired'] = false;
					$this->all_vars['pay_status'] = $pay_data['pay_status'];
					}
				}
			}
		}

	function ajax2()
		{

		if ($_GET['ajax']=='goChangeOut')
			{
			$from = (int)@$_POST['from'];
			$to = (int)@$_POST['to'];
			$from_val = number_format((double)@$_POST['val'],10,'.','');
			$props_val = trim(@$_POST['props_val']);
			$email = trim(@$_POST['email']);

			if ($from_val==0)
				{
				$this->ajax_return['info'] = 'Нулевая сумма';
				}
			else if ($props_val=='')
				{
				$this->ajax_return['info'] = 'Введите реквизиты пополнения';
				}
			else if (!checkEmail($email))
				{
				$this->ajax_return['info'] = 'Введите правильный адрес E-mail';
				}
			else if ($from==0 || $to==0)
				{
				$this->ajax_return['info'] = 'Неверные направления обмена';
				}
			else
				{
				$from_data = $this->db->getBalFull($from);
				$to_data = $this->db->getBalFull($to);

				if ($from_data===false || $to_data===false)
					{
					$this->ajax_return['info'] = 'Неверные направления обмена';
					}
				else
					{
					$paysys_id = $from_data['ch_out_ps_in'];
					$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$paysys_id]);
					// проверяем направления
					$from_list = $from_data['ch_out_list']=='' ? [] : explode(',',$from_data['ch_out_list']);

					if (count($from_list)>0 && !in_array($to, $from_list))
						{
						$this->ajax_return['info'] = 'Неверные направления обмена';
						}		
					else if ($ps_data===false)
						{
						$this->ajax_return['info'] = 'Неверный способ вывода';
						}
					else
						{
						// проверяем цифры
						$to_val = slkDouble($from_data['bal_rate'] * $from_val / $to_data['bal_rate']);
						$from_com = slkDouble($from_val * $from_data['ch_out_com'] / 100);

						if ($from_val<$from_data['ch_out_min'] || $from_val>$from_data['ch_out_max'] || $to_val<$to_data['ch_out_min'] || $to_val>$to_data['ch_out_max'])
							{
							$this->ajax_return['info'] = 'За пределами минимум-максимум';
							}
						else if ($to_val > $to_data['ch_value'])
							{
							$this->ajax_return['info'] = 'Сумма больше резерва';
							}
						else
							{
							// всё ок, создаем платеж
							$pay_ps_data = [
											'pay_ch_bal_id' => $from,
											'pay_ch_value'	=> $from_val,
											'props' => $props_val,
											'email' => $email,
											];

							$ins = [
									'bal_id' 		=> $to,
									'pay_value'		=> $to_val,

									'pay_com'		=> $from_com,
									'pay_ps_data'	=> json_encode($pay_ps_data),

									'pay_dt'		=> time(),
									'pay_end'		=> time() + ($this->db->setGet('pay_life') * 60),
									'pay_status'	=> PAY_STATUS_NEW,
									'pay_type'		=> PAY_TYPE_CHANGE_OUT,
									];

							$pay_id = $this->db->uni_insert('payments',$ins);
							if ($pay_id===false)
								{
								$this->ajax_return['info'] = 'Внутренняя ошибка, обратитесь к техническую поддержку';
								}
							else
								{
								$this->ajax_return['pay_id'] = $pay_id;

								$this->db->logWrite(LOG_PAYCHANGE_OUT_NEW,'',$pay_id,ACC_UNAUTH);

								// генерим оплату
								$temp = explode('||',$ps_data['paysys_name']);
								$ps_name = $temp[0];
								$ps_var = $temp[1];

								$full_val = $from_val + $from_com;

								$pay_result_data = $this->pss[$ps_name]->genPaymentIn($ps_var,$pay_id,$full_val,$email,$email);

								if ($pay_result_data['error']!='ok')
									{
									$upd = [
											'pay_ps_data'	=>	json_encode(array_merge($pay_ps_data,$pay_result_data)),
											'pay_status'	=>	PAY_STATUS_REJECT,
											];
									$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);

									$this->db->logWrite(LOG_PAYSYS_IN_ERROR,arrToLines(['paysys'=>$ps_data['paysys_name'],'val'=>$from_val]),
																			arrToLines($pay_result_data),ACC_UNAUTH);
									$this->ajax_return['info'] = 'Ошибка, возможно сейчас данный способ оплаты недоступен, обратитесь в поддержку';
									}
								else
									{
									$upd = [
											'pay_ps_data'	=>	json_encode(array_merge($pay_ps_data,$pay_result_data)),
											'pay_status'	=>	PAY_STATUS_GO_PAY,
											'paysys_id'		=>	$paysys_id,
											];
									$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);

									$this->db->writeAdminAlerts('changeout','Обмен на сайте, ID:'.$pay_id.
																', email '.$email.
																', меняет '.cutZeros($from_val).' '.$from_data['bal_name'].
																', на '.cutZeros($to_val).' '.$to_data['bal_name'].
																', реквизиты: '.$props_val);


									$this->db->logWrite(LOG_PAYIN_GOPAY,$pay_id,$ps_data['paysys_title'],ACC_UNAUTH);
									$this->ajax_return['result'] = true;
									}
								}
							}
						}
					}
				}
			}




		if ($_GET['ajax']=='cancelChange')
			{
			$pay_id = (int)@$_POST['pay_id'];

			$pay_data = $this->db->uni_select_one('payments',[
																'pay_id'=>$pay_id,
																'pay_type'=>PAY_TYPE_CHANGE_OUT,
																'pay_status'=>PAY_STATUS_GO_PAY
															]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';				
				}
			else
				{
				$this->db->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_CANCEL]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_PAY_CANCEL,$pay_id,'',ACC_UNAUTH);
				}
			}




		if ($_GET['ajax']=='confirmChange')
			{
			$pay_id = (int)@$_POST['pay_id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_CHANGE_OUT]);
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