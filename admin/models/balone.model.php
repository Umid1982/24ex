<?php

class BaloneModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		//$all = $this->db->uni_select('bals');
		//foreach ($all as $one) $this->db->uni_insert('bals_changes',['bal_id'=>$one['bal_id']]);
		//die();

		if (!isset($_GET['bal_id'])) goRedir('balances',true);

		$bal_id = (int)@$_GET['bal_id'];
		if ($bal_id!=0)
			{
			$one_bal = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);
			if ($one_bal===false) goRedir('balances',true);
			}
		else
			{
			$one_bal = [];
			$temp = $this->db->get_columns_defaults('bals');
			foreach ($temp as $k=>$v) { $one_bal[$k] = $v; }

			$one_bal['bal_title'] = 'Новый баланс';
			}

		$one_bal['bal_icon'] = getBalIcon($one_bal['bal_icon']);

		foreach ($one_bal as $k=>$v) $this->all_vars[$k] = $v;

		$this->all_vars['bal_payin_arr']  = trim($one_bal['bal_payin_list'])==''  ? [] : explode(',',$one_bal['bal_payin_list']);
		$this->all_vars['bal_payout_arr'] = trim($one_bal['bal_payout_list'])=='' ? [] : explode(',',$one_bal['bal_payout_list']);

		$this->all_vars['bal_id'] = $bal_id;
		$this->all_vars['bal_types'] = $this->db->uni_select('bal_types');

		$this->all_vars['bal_payin_auto'] = $one_bal['bal_payin_auto'];

		//$this->all_vars['all_bals'] = $this->db->uni_select('bals',['bal_id'=>['eq'=>'<>','val'=>$bal_id]]);

		$inout = ['in','out'];
		foreach ($inout as $one)
			{
			$elems = $this->db->uni_select('paysys',['paysys_type'=>$one]);
			$this->all_vars['all_pss_'.$one] = $elems;
			}
		}

	function ajax()
		{
		if ($_GET['ajax']=='delBal')
			{
			$bal_id = (int)$_POST['bal_id'];
			$bal_data = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);
			if ($bal_data===false)
				{
				$this->ajax_return['info'] = 'Баланс не найден';
				}
			else
				{
				if ($this->db->uni_delete('bals',['bal_id'=>$bal_id]))
					{
					$this->ajax_return['result'] = true;
					$this->db->logWrite(LOG_DELETE_BAL,$bal_data['bal_title'],'',ACC_ADMIN,$this->adminId());
					}
				else
					{
					$this->ajax_return['info'] = 'Нельзя удалить работающий баланс, имеются связи (активные счета, история платежей и т.д.)!';
					}
				}
			}

		if ($_GET['ajax']=='saveBal')
			{
			$bal_id = (int)$_POST['bal_id'];

			$data = $_POST['bal'];
			unset($data['bal_id']);

			if ($bal_id!=0)
				{
				$old_data = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);
				}

			$data['bal_type_id'] = (int)$data['bal_type_id'];

			if (!isset($_FILES['bal_icon']))
				{
				$this->ajax_return['info'] = 'Ошибка формы';
				}
			else if ($data['bal_type_id']==0)
				{
				$this->ajax_return['info'] = 'Выберите тип баланса';
				}
			else
				{
				$file_ok = false;

				$file = $_FILES['bal_icon'];
				if ($file['error']==0)
					{
					$fn = preg_replace('#\.[^\.]+$#si','',$file['name']) . '_' . date('His').'.svg';
					$mime = mime_content_type($file['tmp_name']);
					$fld = getUploadDesc();

					if ($mime!='image/svg+xml' && $mime!='image/svg') $error_file = 'Можно загрузить только SVG';
					else if (!move_uploaded_file($file['tmp_name'], $fld.'/'.$fn)) $error_file = 'Можно загрузить только SVG';
					else { $file_ok = true; $bal_icon = preg_replace('#^.+?/uploads/#','uploads/', $fld.'/'.$fn); }
					}
				else
					{
					$file_ok = true;
					$bal_icon = @$old_data['bal_icon'];
					}

				if (!$file_ok)
					{
					$this->ajax_return['info'] = $error_file;
					}
				else
					{
					$data['bal_icon'] = $bal_icon;

					// default
					$data['bal_default'] = isset($data['bal_default']) ? 1 : 0;

					// статусы
					$data['bal_status_active'] 		= isset($data['bal_status_active']) ? 1 : 0;
					$data['bal_status_payin'] 		= isset($data['bal_status_payin']) ? 1 : 0;
					$data['bal_status_payout'] 		= isset($data['bal_status_payout']) ? 1 : 0;
					$data['bal_status_transfer'] 	= isset($data['bal_status_transfer']) ? 1 : 0;

					$data['bal_transfer_auto'] 		= isset($data['bal_transfer_auto']) ? 1 : 0;

					// списки на ввод и вывод
					$data['bal_payin_list']  = isset($_POST['bal_payin_list']) ? implode(',',@$_POST['bal_payin_list']) : '';
					$data['bal_payout_list'] = isset($_POST['bal_payout_list']) ? implode(',',@$_POST['bal_payout_list']) : '';

					// авто режим
					$data['bal_status_payout'] = isset($data['bal_status_payout']) ? 1 : 0;

					if ($data['bal_rate_from']==0) $data['bal_rate'] = $data['bal_rate_arg'];

					if ($bal_id==0)
						{
						$bal_id = $this->db->uni_insert('bals',$data);
						$this->db->uni_insert('bals_changes',['bal_id'=>$bal_id]);

						$this->db->logWrite(LOG_CREATE_BAL,'',arrToLines($data),ACC_ADMIN,$this->adminId());
						}
					else
						{
						$old_data = $this->db->uni_select_one('bals',['bal_id'=>$bal_id]);

						$this->db->uni_update('bals',['bal_id'=>$bal_id],$data);

						$this->db->logWrite(LOG_UPDATE_BAL,arrToLines($old_data),arrToLines($data),ACC_ADMIN,$this->adminId());
						}

					$this->ajax_return['result'] = true;
					$this->ajax_return['bal_id'] = $bal_id;
					}
				}
			}
		}

	}