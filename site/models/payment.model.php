<?php

class PaymentModel extends Model
	{
	
	function ajax()
		{
		// IN/OUT UNI
		if ($_GET['ajax']=='resetPaysys')
			{
			$pay_id = (int)@$_POST['id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id]);
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
				$this->ajax_return['info'] = 'Время заявки прошло, создайте новую заявку';
				}
			else
				{
				$upd = [
						'pay_ps_data'	=>	'',
						'pay_status'	=>	PAY_STATUS_NEW,
						'paysys_id'		=>	NULL,
						];

				$this->db->uni_update('payments',['pay_id'=>$pay_id],$upd);
				$this->ajax_return['result'] = true;
				}
			}
		}

	}