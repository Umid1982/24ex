<?php

class OfficeMerchantstatModel extends OfficeModel
	{

	function vars2()
		{
		if (!isset($_GET['id']))
            {
            header('Location: /'._LANG_.'/office/merchants/');
            die();
            }

        $m_num = $_GET['id'];
        $m_data = $this->db->uni_select_one('merchants',['user_id'=>$this->userId(),'m_num'=>$m_num]);
        if ($m_data===false)
            {
            header('Location: /'._LANG_.'/office/merchants/');
            die();
            }

        $from = strtotime(date("Y-m-d 00:00:00", strtotime("-12 months")));
        $stat = $this->db->getMerchantStat($m_num,$from); //var_dump($stat_count); die();

        $this->all_vars['chart_count_total'] = $stat['total']['cc'];
        $this->all_vars['chart_count_done'] = $stat['done']['cc'];
        $this->all_vars['chart_count_cancel'] = $stat['cancel']['cc'];
        $this->all_vars['chart_count_wait'] = $stat['wait']['cc'];

        $this->all_vars['chart_sum_total'] = $stat['total']['sum'];
        $this->all_vars['chart_sum_done'] = $stat['done']['sum'];
        $this->all_vars['chart_sum_cancel'] = $stat['cancel']['sum'];
        $this->all_vars['chart_sum_wait'] = $stat['wait']['sum'];

        $this->all_vars['table_stat'] = $stat['table_stat'];

        $months = [];
        for ($i=12;$i>=0;$i--) $months[] = date("Y-m", strtotime("-$i months"));
        $this->all_vars['chart_months'] = $months;
		}

	function ajax2()
		{
        
		}

	}