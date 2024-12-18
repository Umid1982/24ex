<?php

class MerchantoneModel extends Model
	{
	protected $rights_mask = CAN_MODER;

	function vars()
		{
		$m_num = @$_GET['id'];
        $m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
        if ($m_data===false)
            {
            goRedir('merchants',true);
            die();
            }

        foreach ($m_data as $k=>$v)
            {
            $this->all_vars[$k] = $v;
            }

        if ($m_data['m_pss']=='') $temp_pss = [];
        else $temp_pss = explode('|',$m_data['m_pss']);

        $this->all_vars['is_confirm'] = $m_data['m_is_confirm']==1;
        $this->all_vars['m_moder_dt_line'] = date('d.m.Y H:i:s',$m_data['m_moder_dt']);

        // paysys
        $all_pss = $this->db->uni_select('paysys',['paysys_type'=>'in','paysys_merch'=>1]);
        $m_pss = [];

        if (count($all_pss)>0) foreach ($all_pss as $k=>$one_ps)
            {
            if (array_search($one_ps['paysys_id'], $temp_pss)===false) continue;

            $bal_data = $this->db->getBalData($one_ps['bal_id']);

            $one_ps['bal_rate'] = $bal_data['bal_rate'];
            $one_ps['bal_icon'] = getBalIcon($bal_data['bal_icon']);

            $m_pss[$k] = $one_ps;
            }

        $this->all_vars['m_pss'] = $m_pss;
		}

	function ajax()
		{
		if ($_GET['ajax']=='moderMerchant')
			{
			$m_num = $_POST['id'];
			$type = (int)$_POST['type'];

			$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
			if ($type==1 && $m_data['m_is_moder']!=2)
				{
				$this->ajax_return['info'] = 'Нельзя включить сайт';
				}
			else if ($type==0 && $m_data['m_is_moder']!=1)
				{
				$this->ajax_return['info'] = 'Нельзя выключить сайт';
				}
			else if (!in_array($type, [1,0]))
				{
				$this->ajax_return['info'] = 'Некорректный запрос';
				}
			else
				{
				$upd = [
						'm_is_moder' => $type,
						'm_moder_dt' => time()
						];
				$this->db->uni_update('merchants',['m_num'=>$m_num],$upd);

				$type_text = $type==0 ? 'Отключен' : 'Активирован';
				$this->db->logWrite(LOG_MERCH_MODER_CHANGE,$m_num,$type_text,ACC_ADMIN,$this->adminId());	

				$this->ajax_return['m_num'] = $m_num;
				$this->ajax_return['result'] = true;
				}

			}

		if ($_GET['ajax']=='saveMerchantPrc')
			{
			$m_num = $_POST['id'];
			$prc = $_POST['prc'];

			$m_data = $this->db->uni_select_one('merchants',['m_num'=>$m_num]);
			if ($m_data===false)
				{
				$this->ajax_return['info'] = 'Магазин не найден';
				}
			else
				{
				$this->db->uni_update('merchants',['m_num'=>$m_num],['m_prc'=>$prc]);

				$this->db->logWrite(LOG_MERCH_CHANGE_PRC,$m_num,$prc,ACC_ADMIN,$this->adminId());	

				$this->ajax_return['result'] = true;
				}

			}
		}

	}