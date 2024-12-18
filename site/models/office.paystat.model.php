<?php

class OfficePaystatModel extends OfficeModel
	{

	function vars2()
		{
		}

	function ajax2()
		{
		if ($_GET['ajax']=='getPSs')
			{	
			$order = count($this->ajax_sort)>0 ? [$this->ajax_sort['field']=>$this->ajax_sort['sort']] : ['pay_id'=>'DESC'];
			$from = ($this->ajax_pg-1) * $this->ajax_pp;

			$stat = $this->db->uni_select('payments',['user_id'=>$this->userId()],$order,false,$from,@$this->ajax_pp);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			if (count($stat)>0) foreach ($stat as $k=>$v)
				{
				$v['bal_id'] = (int)$v['bal_id'];
				if ($v['bal_id']==0) $bal_data = ['bal_name'=>'$'];
				else $bal_data = $this->db->uni_select_one('bals',['bal_id'=>$v['bal_id']]);

				$stat[$k]['bal_name'] = $bal_data['bal_name'];
				$stat[$k]['pay_dt_line'] = date('Y-m-d H:i',$v['pay_dt']);
				$stat[$k]['pay_type_line'] = payTypeText($v['pay_type']);
				$stat[$k]['pay_status_line'] = payStatusText($v['pay_status']);
				$stat[$k]['pay_value'] = cutZeros($v['pay_value']);
				$stat[$k]['typeurl'] = getPayTypeUrl($v['pay_type']);

				if (in_array($v['pay_status'], [PAY_STATUS_NEW,PAY_STATUS_IN_WORK,PAY_STATUS_PAYS,PAY_STATUS_USER_PAYS,
					PAY_STATUS_SEND_PROPS,PAY_STATUS_PENDING,PAY_STATUS_GO_PAY]) && $v['pay_end']<time())
					{
					$stat[$k]['expired'] = true;
					}
				else
					{
					$stat[$k]['expired'] = false;
					}

				}

			$this->ajax_return['data'] = array_values($stat);
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if ($_GET['ajax']=='saveUserComm')
			{
			$pay_id = (int)$_POST['pay_id'];
			$comm = trim(htmlspecialchars($_POST['comm']));

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'user_id'=>$this->userId()]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			else
				{
				$this->db->uni_update('payments',['pay_id'=>$pay_id],['pay_comm_user'=>$comm]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_PAY_CHANGE_COMM,$pay_data['pay_comm_user'],$comm,ACC_USER,$this->userId());
				}
			}

		if ($_GET['ajax']=='cancelPay')
			{
			$pay_id = (int)$_POST['pay_id'];

			$pay_data = $this->db->uni_select_one('payments',['pay_id'=>$pay_id,'user_id'=>$this->userId()]);
			if ($pay_data===false)
				{
				$this->ajax_return['info'] = 'Платеж не найден';
				}
			else if ($pay_data['pay_status']!=PAY_STATUS_NEW)
				{
				$this->ajax_return['info'] = 'Нельзя отменить данный платеж';
				}
			else 
				{
				// отмена выплаты
				if ($pay_data['pay_type']==PAY_TYPE_OUT)
					{
					$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_value'],REASON_BACK_PAY,$pay_id);
					$this->db->changeUserBal($pay_data['user_id'],$pay_data['ub_id'],PS_TYPE_IN,$pay_data['pay_com'],REASON_BACK_COM,$pay_id);

					// остатки
					$this->db->changeChValue($pay_data['bal_id'],'plus',$pay_data['pay_value'],$pay_data['user_id']);
					}
				// отмена перевода
				else if ($pay_data['pay_type']==PAY_TYPE_TRANSFER)
					{
					
					}

				$this->db->uni_update('payments',['pay_id'=>$pay_id],['pay_status'=>PAY_STATUS_CANCEL]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_PAY_CANCEL,$pay_id,'',ACC_USER,$this->userId());
				}	
			}
		}

	}