<?php

class MerchantsModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function ajax()
		{
		global $config;

		if ($_GET['ajax']=='getMerchants')
			{
			$udts = [];

			$ms = $this->db->getMerchants($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['user_id']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);
			foreach ($ms as $k=>$v)
				{
				if (!isset($udts[$v['user_id']])) $udts[$v['user_id']] = $this->db->uni_select_one('users',['user_id'=>$v['user_id']]);

				$user_data = $udts[$v['user_id']];
				$ms[$k]['user_email'] = $user_data['user_email'];
				}

			$this->ajax_return['data'] = $ms;
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