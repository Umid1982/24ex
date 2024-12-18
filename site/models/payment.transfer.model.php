<?php

class PaymentTransferModel extends PaymentModel
	{
	function vars2()
		{
		if (!$this->isAuth()) goRedir('/auth/');
		if (!isset($_GET['pay'])) goRedir('/');

		$pay_id = (int)$_GET['pay'];
		$pd = $this->db->uni_select_one('payments',['user_id'=>$this->userId(),'pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_TRANSFER]);

		if ($pd===false) goRedir('/');

		$this->all_vars['pay_id'] = $pd['pay_id'];

		$is_new = in_array($pd['pay_status'], [PAY_STATUS_NEW,PAY_STATUS_GO_PAY]);

		$this->all_vars['pay_id'] = $pay_id;


		// check ub
		$ub_id = $pd['ub_id'];
		$ub_data = $this->db->uni_select_one('users_bals',['ub_id'=>$ub_id]);
		if ($ub_data===false) goRedir('/');

		// ub lock
		if ($ub_data['ub_lock']==1) goRedir('/');

		// check bal
		$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ub_data['bal_id'],'bal_status_active'=>1]);
		if ($bal_data===false) goRedir('/');

		if ($bal_data['bal_status_transfer']==0) goRedir('/');	

		// платежный пароль
		$this->all_vars['need_paypass'] = $this->db->isNeedPayPass($this->userId());

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
			// первый шаг, ввод реквизитов
			$this->all_vars['step'] = 1;

			$this->all_vars['bal_name'] = $bal_data['bal_name'];
			$this->all_vars['pay_value'] = cutZeros($pd['pay_value']);
			$this->all_vars['pay_com'] = cutZeros($pd['pay_com']);
			}
		else if ($pd['pay_status']==PAY_STATUS_GO_PAY)
			{
			// уже введены данные куда слать
			$this->all_vars['step'] = 2;

			$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ub_data['bal_id'],'bal_status_active'=>1]);
			//if ($bal_data===false) goRedir('/');

			$temp = json_decode($pd['pay_ps_data'],true);

			if (isset($temp['change_val']))
				{
				$change_bal_data = $this->db->getBalData($temp['change_bal_id']);

				$this->all_vars['need_change'] = true;
				$this->all_vars['change_val'] = cutZeros($temp['change_val']);
				$this->all_vars['change_com'] = cutZeros($temp['change_com']);
				$this->all_vars['change_bal_name'] = $change_bal_data['bal_name'];
				}
			else
				{
				$this->all_vars['need_change'] = false;
				}

			$this->all_vars['user_bal_num'] = $temp['props'];
			$this->all_vars['bal_name'] = $bal_data['bal_name'];
			$this->all_vars['pay_value'] = cutZeros($pd['pay_value']);
			$this->all_vars['pay_com'] = cutZeros($pd['pay_com']);
			}
		}

	function ajax2()
		{
		if ($_GET['ajax']=='sendTransferData')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$user_bal_num = trim(@$_POST['user_bal_num']);
			$pay_pass = @$_POST['pay_pass'];

			$result = $this->db->transferSetPayData($this->userId(),$pay_id,$user_bal_num,$pay_pass);
			if ($result['result']==PAY_ERR_OK)
				{
				$this->ajax_return['result'] = true;
				}
			else
				{
				$this->ajax_return['info'] = getPayErrText(@$result['result']);
				}
			}

		if ($_GET['ajax']=='resetTransferProps')
			{
			$pay_id = (int)@$_POST['pay_id'];
			$pay_data = $this->db->uni_select_one('payments',['user_id'=>$this->userId(),'pay_id'=>$pay_id]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			else
				{
				$temp = json_decode($pay_data['pay_ps_data'],true);
				if (!isset($temp['props']))
					{
					$this->ajax_return['info'] = 'Реквизиты не введены';
					}
				else
					{
					// ручной режим
					$upd = [
							'pay_status' 	=> PAY_STATUS_NEW,
							'pay_ps_data'	=> '',
							];						

					$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);
					$this->ajax_return['result'] = true;

					$this->db->logWrite(LOG_PAY_TRANFER_RESET,$pay_id,'',ACC_USER,$this->userId());
					}
				}
			}	

		if ($_GET['ajax']=='confirmTransfer')
			{
			$pay_id = (int)@$_POST['pay_id'];
			
			$result = $this->db->transferConfirm($this->userId(),$pay_id);
			if ($result['result']==PAY_ERR_OK)
				{
				$this->ajax_return['result'] = true;
				}
			else
				{
				$this->ajax_return['info'] = getPayErrText(@$result['result']);
				}

			}

		}

	}