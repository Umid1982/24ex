<?php

class OfficeOrdersModel extends OfficeModel
	{

	function vars2()
		{
		$merchants = $this->db->uni_select('merchants',['user_id'=>$this->userId()]);
        $this->all_vars['merchants'] = $merchants;
		}

	function ajax2()
		{
        if ($_GET['ajax']=='getOrders')
            {
            $mss = [];
            
            $orders = $this->db->getOrders($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,$this->userId(),@$this->ajax_qs['m_num']);
            $total = $this->db->last_count();
            $pg_max = ceil($total / $this->ajax_pp);
            if (count($orders)>0) foreach ($orders as $k=>$v)
                {
                if (!isset($mss[$v['m_num']])) $mss[$v['m_num']] = $this->db->uni_select_one('merchants',['m_num'=>$v['m_num']]);

                $m_data = $mss[$v['m_num']];
                $orders[$k]['m_title'] = $m_data['m_title'];

                $orders[$k]['order_dt_create_line'] = date('d.m.Y H:i',$v['order_dt_create']);
                $orders[$k]['order_amount'] = cutZeros($v['order_amount']);
                $orders[$k]['order_com'] = cutZeros($v['order_com']);
                $orders[$k]['order_status_line'] = orderStatusText($v['order_status']);
                }

            $this->ajax_return['data'] = $orders;
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