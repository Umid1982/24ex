<?php

class OfficeCblsModel extends OfficeModel
	{

	function vars2()
		{
		$merchants = $this->db->uni_select('merchants',['user_id'=>$this->userId()]);
        $this->all_vars['merchants'] = $merchants;
		}

	function ajax2()
		{
        if ($_GET['ajax']=='getCallbacks')
            {
            $clbs = $this->db->getCallbacks($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,$this->userId(),@$this->ajax_qs['m_num']);
            $total = $this->db->last_count();
            $pg_max = ceil($total / $this->ajax_pp);

            $mss = [];

            if (count($clbs)>0) foreach ($clbs as $k=>$v)
                {
                $clbs[$k]['cbl_id'] = (int)$v['cbl_id'];

                if (!isset($mss[$v['m_num']])) $mss[$v['m_num']] = $this->db->uni_select_one('merchants',['m_num'=>$v['m_num']]);

                $order_data = $this->db->uni_select_one('orders',['order_id'=>$v['order_id']]);
                $clbs[$k]['order_id_shop'] = $order_data['order_id_shop'];

                $m_data = $mss[$v['m_num']];
                $clbs[$k]['m_title'] = $m_data['m_title'];

                $clbs[$k]['cbl_dt_create_line'] = date('d.m.Y H:i',$v['cbl_dt_create']);
                $clbs[$k]['cbl_dt_last_send_line'] = $v['cbl_dt_last_send']==0 ? '-' : date('d.m.Y H:i',$v['cbl_dt_last_send']);
                $clbs[$k]['cbl_dt_end_line'] = $v['cbl_dt_end']==0 ? '-' : date('d.m.Y H:i',$v['cbl_dt_end']);

                $clbs[$k]['cbl_status_line'] = orderCallbackText($v['cbl_status']);
                }

            $this->ajax_return['data'] = $clbs;
            $this->ajax_return['meta'] = [
                                            "page" => $this->ajax_pg,
                                            "pages" => $pg_max,
                                            "perpage" => $this->ajax_pp,
                                            "total" => $total,
                                            "sort" => @$this->ajax_sort['sort'],
                                            "field" => @$this->ajax_sort['field']
                                         ];
            }

        if ($_GET['ajax']=='getCblLogs')
            {
            $sort = isset($this->ajax_sort['sort']) ? [$this->ajax_sort['sort']=>$this->ajax_sort['field']] : [];

            $cbl_id = (int)$this->ajax_qs['cbl_id'];
            $from = ($this->ajax_pg-1)*$this->ajax_pp;
            $logs = $this->db->uni_select('callback_logs',['cbl_id'=>$cbl_id],$sort,false,$from,$this->ajax_pp);
            $total = $this->db->last_count();
            $pg_max = ceil($total / $this->ajax_pp);

            if (count($logs)>0) foreach ($logs as $k=>$v)
                {
                $logs[$k]['send_dt_line'] = date('d.m.Y H:i',$v['send_dt']);
                $logs[$k]['send_result_line'] = orderCallbackText($v['send_result']);
                $logs[$k]['answer_line'] = clbAnswerText($v['answer']);
                }

            $this->ajax_return['data'] = $logs;
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