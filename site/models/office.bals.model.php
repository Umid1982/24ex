<?php

class OfficeBalsModel extends OfficeModel
	{

	function vars2()
		{
		$this->all_vars['add_bals'] = $this->db->getBalsForAdd($this->userId());
		}

	function ajax2()
		{
		if ($_GET['ajax']=='getUBs')
			{
			$user_id = $this->userId();

			$bal_types = $this->db->getBalTypes();
			$ubs = $this->db->getUBs($user_id,$bal_types,$this->ajax_pg,$this->ajax_pp,@$this->ajax_sort,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			if (count($ubs)>0) foreach ($ubs as $k=>$v)
				{
				$ubs[$k]['ub_value'] = cutZeros($v['ub_value']);
				$ubs[$k]['can_transfer'] = $v['ub_lock']==0 && $v['bal_status_active']==1 && $this->db->balCanPay($v['bal_id'],'transfer');
				$ubs[$k]['can_payin'] = $v['ub_lock']==0 && $v['bal_status_active']==1 && $this->db->balCanPay($v['bal_id'],'in');
				$ubs[$k]['can_payout'] = $v['ub_lock']==0 && $v['bal_status_active']==1 && $this->db->balCanPay($v['bal_id'],'out') && $v['ub_value']>0;
				$ubs[$k]['can_voucher'] = $v['ub_lock']==0 && $v['bal_status_active']==1 && $this->db->balCanPay($v['bal_id'],'voucher') && $v['ub_value']>0;
				}	

			$this->ajax_return['data'] = array_values($ubs);
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if ($_GET['ajax']=='addBal')
			{
			$bal_id = (int)$_POST['id'];

			$result = $this->db->addBalToUser($this->userId(),$bal_id);
			if ($result===false)
				{
				$this->ajax_return['info'] = 'Баланс уже есть в вашем списке';
				}
			else
				{
				$one_bal = $result;
				$this->ajax_return['result'] = true;
				$this->db->logWrite(LOG_UB_ADD,'',$one_bal['bal_title'].' ['.$one_bal['bal_name'].']',ACC_USER,$this->userId());
				}
			}

		if ($_GET['ajax']=='delBal')
			{
			$ub_id = (int)$_POST['id'];

			$one_ub = $this->db->uni_select_one('users_bals',['user_id'=>$this->userId(),'ub_id'=>$ub_id]);
			if ($one_ub===false)
				{
				$this->ajax_return['info'] = 'Баланс не найден';
				}
			else if ($one_ub['ub_value']>0)
				{
				$this->ajax_return['info'] = 'Нельзя удалить не пустой баланс!';
				}
			else
				{
				$this->db->uni_delete('users_bals',['ub_id'=>$one_ub['ub_id']]);
				$this->ajax_return['result'] = true;

				$one_bal = $this->db->uni_select_one('bals',['bal_id'=>$one_ub['bal_id']]);
				$this->db->logWrite(LOG_UB_DEL,$one_bal['bal_title'].' ['.$one_bal['bal_name'].']','',ACC_USER,$this->userId());
				}
			}

		if ($_GET['ajax']=='sendPayIn' || $_GET['ajax']=='sendPayOut' || $_GET['ajax']=='newVoucher' || $_GET['ajax']=='newTransfer')
			{
			$ub_id = (int)@$_POST['ub_id'];
			$value = floatval(htmlspecialchars(@$_POST['val']));

			$this->ajax_return['value'] = $value;

			if ($value<=0)
				{
				$this->ajax_return['info'] = 'Неверная сумма';
				}
			else
				{
				$ub_data = $this->db->uni_select_one('users_bals',['ub_id'=>$ub_id,'user_id'=>$this->userId()]);
				if ($ub_data===false)
					{
					$this->ajax_return['info'] = 'Баланс пользователя не найден';
					}
				else if (($bal_data = $this->db->uni_select_one('bals',['bal_id'=>$ub_data['bal_id'],'bal_status_active'=>1])) === false)
					{
					$this->ajax_return['info'] = 'Баланс не найден';
					}
				else
					{

// ПОПОЛНЕНИЕ ========================================================================================================================
					
					if ($_GET['ajax']=='sendPayIn')
						{
						$result = $this->db->payInNew($this->userId(),$ub_data['ub_id'],$value);
						if ($result['result']==PAY_ERR_OK)
							{
							$this->ajax_return['result'] = true;
							$this->ajax_return['pay_link'] = $result['link'];
							}
						else
							{
							$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
							}
						}

// ПОПОЛНЕНИЕ /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ПЕРЕВОДЫ ==========================================================================================================================

					else if ($_GET['ajax']=='newTransfer')
						{
						$result = $this->db->transferNew($this->userId(),$ub_data['ub_id'],$value);
						if ($result['result']==PAY_ERR_OK)
							{
							$this->ajax_return['result'] = true;
							$this->ajax_return['pay_link'] = $result['link'];
							}
						else
							{
							$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
							}
						}

// ПЕРЕВОДЫ /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				


// ВЫВОД СРЕДСТВ ========================================================================================================================
					
					else if ($_GET['ajax']=='sendPayOut')
						{
						$result = $this->db->payOutNew($this->userId(),$ub_data['ub_id'],$value);
						if ($result['result']==PAY_ERR_OK)
							{
							$this->ajax_return['result'] = true;
							$this->ajax_return['pay_link'] = $result['link'];
							}
						else
							{
							$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
							}
						}

// ВЫВОД СРЕДСТВ /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// ВАУЧЕРЫ ========================================================================================================================

					else if ($_GET['ajax']=='newVoucher')
						{
						$result = $this->db->newVoucher($ub_data['bal_id'],$value,$this->userId(),$ub_data['ub_id']);
						if ($result['result']==PAY_ERR_OK)
							{
							$this->ajax_return['result'] = true;
							}
						else
							{
							$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
							}

						}

// ВАУЧЕРЫ /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

					}
				}
			}

		}

	}