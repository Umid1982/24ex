<?php

class PaymentChangeinModel extends PaymentModel
	{

	function vars2()
		{
		global $config;

		if (!$this->isAuth()) goRedir('/auth/');

		// ШАГ 1: ВЫБОР НАПРАВЛЕНИЙ
		if (!isset($_GET['pay']))
			{
			$this->all_vars['step'] = 1;

			$ubs = $this->db->getUBs($this->userId());
			if (count($ubs)==0) goRedir('/office/');
				
			$ub_ids = [];
			foreach ($ubs as $one_ub)
				{
				if ($one_ub['ub_lock']==1) continue;
				$ub_ids[] = $one_ub['bal_id'];
				}

			if (count($ub_ids)==0) goRedir('/office/');

			$chs = $this->db->getAllChs('in',false,$ub_ids);
			$ubs = $this->db->getUBs($this->userId());

			$need = [
					'bal_id','bal_title','bal_name','bal_icon','bal_rate',
					'ch_value','ch_in_status','ch_in_list','ch_in_com','ch_in_min','ch_in_max',
					];

			$ready = [];
			foreach ($chs as $k=>$v)
				{
				if (!isset($ubs[$v['bal_id']])) continue;
				if ($ubs[$v['bal_id']]['ub_lock']==1) continue;

				if ($v['ch_value']<=0) $v['ch_value'] = 0;

				$one_ready = [];
				foreach ($need as $one_need) $one_ready[$one_need] = $v[$one_need];

				$one_ready['ub_value'] = cutZeros($ubs[$v['bal_id']]['ub_value']);

				$ready[$v['bal_id']] = $one_ready;
				}

			$this->all_vars['changes_json'] = json_encode($ready);

			// платежный пароль
			$this->all_vars['need_paypass'] = $this->db->isNeedPayPass($this->userId());
			}
		else
			{
			$pay_id = (int)$_GET['pay'];
			$pay_data = $this->db->uni_select_one('payments',['user_id'=>$this->userId(),'pay_id'=>$pay_id,'pay_type'=>PAY_TYPE_CHANGE_IN]);
			if ($pay_data===false) goRedir('/payment/changein');

			foreach ($pay_data as $k=>$v) $this->all_vars[$k] = $v;
			$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);
			foreach ($pay_ps_data as $k=>$v) $this->all_vars['ps_data_'.$k] = $v;

			// ШАГ 2: ПОДТВЕРЖДЕНИЕ
			if ( ($pay_data['pay_status']==PAY_STATUS_NEW && time()<=$pay_data['pay_end']) )
				{
				$this->all_vars['step'] = 2;

				$pay_ps_data = json_decode($pay_data['pay_ps_data'],true);

				$from_bal_id = $pay_ps_data['pay_ch_bal_id'];
				$to_bal_id = $pay_data['bal_id'];

				$from_bal_data = $this->db->getBalData($from_bal_id);
				foreach ($from_bal_data as $k=>$v) $this->all_vars['from_'.$k] = $v;
				$this->all_vars['from_bal_icon'] = getBalIcon($from_bal_data['bal_icon']);

				$to_bal_data = $this->db->getBalData($to_bal_id);
				foreach ($to_bal_data as $k=>$v) $this->all_vars['to_'.$k] = $v;
				$this->all_vars['to_bal_icon'] = getBalIcon($to_bal_data['bal_icon']);
				}
			// ШАГ 3: СТАТУС
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

		if ($_GET['ajax']=='goChangeIn')
			{
			$from = (int)@$_POST['from'];
			$to = (int)@$_POST['to'];
			$from_val = number_format((double)@$_POST['val'],10,'.','');
			$pp = @$_POST['pp'];

			$result = $this->db->changeInNew($this->userId(),$from,$to,$from_val,$pp='');
			if ($result['result']==PAY_ERR_OK)
				{
				$pay_id = $result['pay_id'];

				$this->ajax_return['result'] = true;
				$this->ajax_return['pay_id'] = $pay_id;
				}
			else
				{
				$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
				}
			}



		if ($_GET['ajax']=='cancelChange')
			{
			$pay_id = (int)@$_POST['pay_id'];

			$pay_data = $this->db->uni_select_one('payments',[
															'user_id'=>$this->userId(),
															'pay_id'=>$pay_id,
															'pay_type'=>PAY_TYPE_CHANGE_IN,
															'pay_status'=>PAY_STATUS_NEW
															]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';				
				}
			else
				{
				$this->db->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_CANCEL]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_PAY_CANCEL,$pay_id,'',ACC_USER,$this->userId());
				}
			}




		if ($_GET['ajax']=='confirmChange')
			{
			$pay_id = (int)@$_POST['pay_id'];

			$result = $this->db->changeInConfirm($this->userId(),$pay_id);
			if ($result['result']==PAY_ERR_OK)
				{
				$this->ajax_return['result'] = true;
				}
			else
				{
				$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
				}

			}

		}

	}