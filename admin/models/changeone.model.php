<?php

class ChangeoneModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		if (!isset($_GET['bal_id'])) goRedir('changes',true);

		$bal_id = (int)@$_GET['bal_id'];
		if ($bal_id==0) goRedir('changes',true);

		$one_bal = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);
		if ($one_bal==false) goRedir('bals',true);

		$this->all_vars['bal_title'] = $one_bal['bal_title'];
		$this->all_vars['bal_name'] = $one_bal['bal_name'];

		$ch_data = $this->db->uni_select_one('bals_changes',['bal_id'=>$bal_id]);
		if ($ch_data===false)
			{
			$this->db->uni_insert('bals_changes',['bal_id'=>$bal_id]);
			$ch_data = $this->db->uni_select_one('bals_changes',['bal_id'=>$bal_id]);
			}

		foreach ($ch_data as $k=>$v) $this->all_vars[$k] = $v;
		$this->all_vars['chs_in_arr'] = explode(',',$ch_data['ch_in_list']);
		$this->all_vars['chs_out_arr'] = explode(',',$ch_data['ch_out_list']);


		// all data
		$this->all_vars['all_chs_in'] = $this->db->getAllChs('in',$bal_id);
		$this->all_vars['all_chs_out'] = $this->db->getAllChs('out',$bal_id);

		$inout = ['in','out'];
		foreach ($inout as $one)
			{
			$elems = $this->db->uni_select('paysys',['paysys_type'=>$one,'bal_id'=>$bal_id]);
			$this->all_vars['all_pss_'.$one] = $elems;
			}

		}

	function ajax()
		{
		if ($_GET['ajax']=='saveCh')
			{
			$bal_id = (int)$_POST['bal_id'];

			$upd = [];

			$upd['ch_in_status'] = isset($_POST['ch']['ch_in_status']) ? 1 : 0;
			$upd['ch_in_list'] = @implode(',',@$_POST['ch_in_list']);
			$upd['ch_in_com'] = number_format((double)@$_POST['ch']['ch_in_com'],5,'.','');
			$upd['ch_in_auto'] = isset($_POST['ch']['ch_in_auto']) ? 1 : 0;

			$upd['ch_in_min'] = number_format((double)@$_POST['ch']['ch_in_min'],10,'.','');
			$upd['ch_in_max'] = number_format((double)@$_POST['ch']['ch_in_max'],10,'.','');

			$upd['ch_out_status'] = isset($_POST['ch']['ch_out_status']) ? 1 : 0;
			$upd['ch_out_list'] = @implode(',',@$_POST['ch_out_list']);
			$upd['ch_out_com'] = number_format((double)@$_POST['ch']['ch_out_com'],5,'.','');
			$upd['ch_out_auto'] = isset($_POST['ch']['ch_out_auto']) ? 1 : 0;

			$upd['ch_out_min'] = number_format((double)@$_POST['ch']['ch_out_min'],10,'.','');
			$upd['ch_out_max'] = number_format((double)@$_POST['ch']['ch_out_max'],10,'.','');

			$upd['ch_out_ps_in'] = (int)@$_POST['ch_out_ps_in'];
			$upd['ch_out_ps_out'] = (int)@$_POST['ch_out_ps_out'];

			$before = $this->db->uni_select_one('bals_changes',['bal_id'=>$bal_id]);
			$this->db->uni_update('bals_changes',['bal_id'=>$bal_id],$upd);
			$after = $this->db->uni_select_one('bals_changes',['bal_id'=>$bal_id]);

			$this->db->logWrite(LOG_UPDATE_CH,arrToLines($before),arrToLines($after),ACC_ADMIN,$this->adminId());

			$this->ajax_return['result'] = true;
			}

		if ($_GET['ajax']=='changeChValue')
			{
			$bal_id = (int)@$_POST['bal_id'];
			$type = @$_POST['type'];
			$value = cutZeros(number_format(round((double)@$_POST['value'],10),10,'.',''));

			$ch_result = $this->db->changeChValue($bal_id,$type,$value,$this->adminId(),true,true);

			$this->ajax_return = $ch_result;
			}
		}

	}