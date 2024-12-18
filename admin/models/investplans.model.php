<?php

class InvestplansModel extends Model
	{
	protected $rights_mask = CAN_ADMIN;

	function ajax()
		{
		global $config;

		if ($_GET['ajax']=='getInvestPlans')
			{
			$plans = $this->db->getInvestPlans($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($plans as $k=>$v)
				{
				$bal_data = $this->db->getBalData($v['bal_id']);
				$plans[$k]['bal_line'] = '['.$bal_data['bal_name'].'] '.$bal_data['bal_title'];
				}

			$this->ajax_return['data'] = $plans;
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