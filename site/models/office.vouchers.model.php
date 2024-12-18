<?php

class OfficeVouchersModel extends OfficeModel
	{

	function vars2()
		{

		}

	function ajax2()
		{
		if ($_GET['ajax']=='getUVs')
			{
			$order = count($this->ajax_sort)>0 ? [$this->ajax_sort['field']=>$this->ajax_sort['sort']] : [];
			$from = ($this->ajax_pg-1) * $this->ajax_pp;			

			$vouchers = $this->db->uni_select('vouchers',['user_id'=>$this->userId()],$order,false,@$this->ajax_pp);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);		
			
			if (count($vouchers)>0) foreach ($vouchers as $k=>$v)
				{
				$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$v['bal_id']]);

				$vouchers[$k]['bal_name'] = $bal_data['bal_name'];
				$vouchers[$k]['voucher_dt_create_line'] = date('Y-m-d H:i',$v['voucher_dt_create']);
				$vouchers[$k]['voucher_dt_activate_line'] = $v['voucher_dt_activate']==0 ? ' - никогда - ' : date('Y-m-d H:i',$v['voucher_dt_activate']);
				$vouchers[$k]['voucher_status_line'] = getVoucherStatusText($v['voucher_status']);
				$vouchers[$k]['voucher_value'] = cutZeros($v['voucher_value']);
				}

			$this->ajax_return['data'] = array_values($vouchers);
			$this->ajax_return['meta'] = [
									        "page" => $this->ajax_pg,
									        "pages" => $pg_max,
									        "perpage" => $this->ajax_pp,
									        "total" => $total,
									        "sort" => @$this->ajax_sort['sort'],
									        "field" => @$this->ajax_sort['field']
										 ];	
			}
		}

	}