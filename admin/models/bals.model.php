<?php

class BalsModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{

		}

	function ajax()
		{
		$bal_types = $this->db->getBalTypes();

		$sort = (count($this->ajax_sort)>0) ? $this->ajax_sort : [];
		$from = (($this->ajax_pg-1)*$this->ajax_pp);
		
		$bals = $this->db->searchBals($sort,$from,$this->ajax_pp,@$this->ajax_qs['generalSearch']);
		$total = $this->db->last_count();
		$pg_max = ceil($total / $this->ajax_pp);

		foreach ($bals as $k=>$v)
			{
			$bals[$k]['bal_icon'] = getBalIcon($v['bal_icon']);
			$bals[$k]['bal_type_line'] = $bal_types[$v['bal_type_id']];
			$bals[$k]['bal_rate'] = cutZeros($v['bal_rate']);
			}

		$this->ajax_return['data'] = $bals;
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