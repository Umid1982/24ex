<?php

class ChangesModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{

		}

	function ajax()
		{
		if ($_GET['ajax']=='getBals')
			{
			$sort = (count($this->ajax_sort)>0) ? [$this->ajax_sort['field']=>$this->ajax_sort['sort']] : [];
			$from = (($this->ajax_pg-1)*$this->ajax_pp);
			
			$bals = $this->db->searchBals($sort,$from,$this->ajax_pp,@$this->ajax_qs['generalSearch']);
			$total = $this->db->last_count();
			$pg_max = ceil($total / $this->ajax_pp);

			foreach ($bals as $k=>$v)
				{
				$bals[$k]['bal_icon'] = getBalIcon($v['bal_icon']);

				$ch_data = $this->db->uni_select_one('bals_changes',['bal_id'=>$v['bal_id']]);
				if ($ch_data===false)
					{
					$bals[$k]['has_ch'] = false;
					}
				else
					{
					$bals[$k]['has_ch'] = true;
					$bals[$k]['ch_data'] = $ch_data;
					$bals[$k]['ch_data']['ch_value'] = cutZeros($ch_data['ch_value']);
					}
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
	}