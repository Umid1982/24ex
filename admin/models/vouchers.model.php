<?php

class VouchersModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		$this->all_vars['all_users'] = $this->db->uni_select('users');
		$this->all_vars['all_bals'] = $this->db->uni_select('bals');
		}

	function ajax()
		{
		if ($_GET['ajax']=='getVouchers')
			{
			$vouchers = $this->db->getVouchers($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['user_id'],@$this->ajax_qs['status']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			$all_bals = $this->db->getAllBals();

			foreach ($vouchers as $k=>$v)
				{
				if ($v['user_id']!==null)
					{
					$user_data = $this->db->uni_select_one('users',['user_id'=>$v['user_id']]);
					$vouchers[$k]['user_email'] = $user_data['user_email'];
					}
					
				$vouchers[$k]['bal_name'] = $all_bals[$v['bal_id']]['bal_name'];
				$vouchers[$k]['voucher_dt_create_line'] = date('Y-m-d',$v['voucher_dt_create']);
				$vouchers[$k]['voucher_dt_activate_line'] = $v['voucher_dt_activate']==0 ? ' - никогда - ' : date('Y-m-d H:i',$v['voucher_dt_activate']);
				$vouchers[$k]['voucher_status_line'] = getVoucherStatusText($v['voucher_status']);
				$vouchers[$k]['voucher_value'] = cutZeros($v['voucher_value']);
				$vouchers[$k]['voucher_code'] = strtoupper($v['voucher_code']);
				}

			$this->ajax_return['data'] = $vouchers;
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];
			}

		if ($_GET['ajax']=='newVoucher')
			{
			$bal_id = (int)@$_POST['bal'];
			$value = @$_POST['value'];

			if ($value<=0 || $bal_id==0)
				{
				$this->ajax_return['info'] = 'Введите сумму и выберите баланс';
				}
			else
				{
				$result = $this->db->newVoucher($bal_id,$value);
				if ($result['result']==PAY_ERR_OK)
					{
					$v_id = $result['voucher_id'];
					$this->db->logWrite(LOG_VOUCHER_NEW,'',$v_id,ACC_ADMIN,$this->adminId());
					$this->ajax_return['result'] = true;
					}
				else
					{
					$this->ajax_return['info'] = getPayErrText(@$result['result'],@$result['min'],@$result['max']);
					}
				}
			}

		if ($_GET['ajax']=='lockVoucher')
			{
			$v_id = (int)@$_POST['id'];
			$status = (int)@$_POST['status'];

			$this->db->uni_update('vouchers',['voucher_id'=>$v_id],['voucher_status'=>$status]);
			$this->ajax_return['result'] = true;
			}
		}

	}