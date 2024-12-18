<?php

class OfficeMerchantsModel extends OfficeModel
	{

	function vars2()
		{
		
		}

	function ajax2()
		{
        if ($_GET['ajax']=='getMerchants')
            {
            $ms = $this->db->getMerchants($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,$this->userId());
            $total = $this->db->last_count();
            $pg_max = ceil($total / $this->ajax_pp);

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