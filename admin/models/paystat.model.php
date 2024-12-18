<?php

class PaystatModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		$this->all_vars['all_users'] = $this->db->uni_select('users');
		}

	function ajax()
		{
		if ($_GET['ajax']=='getPaystat')
			{
			$bal_types = $this->db->getBalTypes();

			$paystat = $this->db->getPaystat($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['user_id']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			$all_bals = $this->db->getAllBals();

			if (count($paystat)>0)
				{
				foreach ($paystat as $k=>$v)
					{
					$paystat[$k]['bal_name'] = $all_bals[$v['bal_id']]['bal_name'];
					$paystat[$k]['ps_dt_line'] = date('Y-m-d H:i',$v['ps_dt']);
					$paystat[$k]['reason_line'] = getReasonText($v['ps_reason']);
					$paystat[$k]['ps_value'] = cutZeros($v['ps_value']);
					}
				}

			$this->ajax_return['data'] = $paystat;
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