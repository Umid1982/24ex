<?php

class PaymentNeworderModel extends PaymentModel
	{
	function vars2()
		{
		// check data
		$check_fields = true;
		$need = ['m_shop','m_orderid','m_amount','m_desc','m_sign'];
		foreach ($need as $one_need) { if (!isset($_REQUEST[$one_need])) { $check_fields = false; break; }}

		if (!$check_fields)
			{
			$check = CHECK_NEWORDER_FIELDS;
			}
		else
			{
			$m_num = $_REQUEST['m_shop'];

			$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
			if ($m_data===false)
				{
				$check = CHECK_NEWORDER_NOSHOP;
				}
			else if ($m_data['m_is_confirm']!=1 || $m_data['m_is_moder']!=1)
				{
				$check = CHECK_NEWORDER_SHOPLOCK;
				}
			else
				{
				$pss = [];
				$temp = ($m_data['m_pss']=='') ? [] : explode('|',$m_data['m_pss']);
				if (count($temp)>0)
					{
					foreach ($temp as $one_ps)
						{
						$ps_data = $this->db->uni_select_one('paysys',['paysys_id'=>$one_ps,'paysys_merch'=>1]);
						if ($ps_data!==false) $pss[] = $one_ps;
						}
					}

				if (count($pss)==0)
					{
					$check = CHECK_NEWORDER_NOPSS;
					}
				else
					{
					// check sign
					$sign_send = $_REQUEST['m_sign'];

					$m_orderid = $_REQUEST['m_orderid'];
					$m_amount = $_REQUEST['m_amount'];
					$m_desc = $_REQUEST['m_desc'];
					$m_sign = $_REQUEST['m_sign'];

					$arHash = array(
						$m_num,
						$m_orderid,
						$m_amount,
						$m_desc,
						$m_data['m_api_key'],
					);
					$sign_check = strtoupper(sha1(implode(':', $arHash)));

					if ($sign_send!=$sign_check)
						{
						$check = CHECK_NEWORDER_SIGN;
						}
					else
						{
						// ПРОВЕРЯЕМ УНИКАЛЬНОСТЬ ЗАКАЗА
						$uniq_ok = true;
						if ($m_data['m_order_uniq']==1)
							{
							$check_uniq = $this->db->uni_select_one('orders',['m_num'=>$m_num,'order_id_shop'=>$m_orderid]);
							if ($check_uniq!==false)
								{
								$check = CHECK_NEWORDER_UNIQID;
								$uniq_ok = false;
								}
							}

						if ($uniq_ok)
							{
							$com = slkDouble($m_amount * $m_data['m_prc'] / 100);

							// ВСЕ ПРОВЕРИЛИ, СОЗДАЕМ ЗАКАЗ
							$order_id = $this->db->orderNew($m_data['user_id'],$m_num,$m_orderid,$m_amount,$com,$m_desc);
							if ($order_id===false)
								{
								$check = CHECK_NEWORDER_ERROR;
								}
							else
								{
								// всё ок, создаем платеж
								$pay_ps_data = [
												'm_num' => $m_num,
												'order_id' => $order_id,
												];


								$ins = [
										'user_id' 		=> $m_data['user_id'],

										'pay_value'		=> 0,
										'pay_com'		=> 0,
										
										'pay_ps_data'	=> json_encode($pay_ps_data),

										'pay_dt'		=> time(),
										'pay_end'		=> time() + ($this->db->setGet('pay_life') * 60),
										'pay_status'	=> PAY_STATUS_NEW,
										'pay_type'		=> PAY_TYPE_ORDER,
										];

								$pay_id = $this->db->uni_insert('payments',$ins);
								$this->db->logWrite(LOG_ORDER_NEW,'',$order_id,ACC_UNAUTH);

								header('Location: /'._LANG_.'/payment/order?pay='.$pay_id);
								die();
								}
							}
						}
					}
				}
			}

		$this->all_vars['check'] = $check;
		}

	}