<?php

class BaltypesModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		$this->all_vars['bal_types'] = $this->db->uni_select('bal_types',[],['bal_type_id'=>'ASC']);
		}

	function ajax()
		{
		if (@$_GET['ajax']=='deleteBalType')
			{
			$id = (int)@$_POST['id'];

			$test = $this->db->uni_select_one('bals',['bal_type_id'=>$id]);
			if ($test!==false)
				{
				$this->ajax_return['info'] = 'Тип баланса используется в балансах, нельзя удалить';
				}
			else
				{
				$bal_type_data = $this->db->uni_select_one('bal_types',['bal_type_id'=>$id]);

				$this->db->uni_delete('bal_types',['bal_type_id'=>$id]);
				$this->ajax_return['result'] = true;

				$this->db->logWrite(LOG_DELETE_BAL_TYPE,$bal_type_data['bal_type_title'],'',ACC_ADMIN,$this->adminId());
				}
			}

		if (@$_GET['ajax']=='getBalTypeData')
			{
			$id = (int)@$_POST['id'];

			$bal_type_data = $this->db->uni_select_one('bal_types',['bal_type_id'=>$id]);
			if ($bal_type_data!==false)
				{
				$this->ajax_return['result'] = true;
				$this->ajax_return['bal_type_title'] = $bal_type_data['bal_type_title'];
				$this->ajax_return['bal_type_id'] = $bal_type_data['bal_type_id'];
				}
			}

		if (@$_GET['ajax']=='saveBalTypeData')
			{
			$bal_type_id = (int)@$_POST['bal_type_id'];
			$bal_type_title = htmlspecialchars(@$_POST['bal_type_title']);

			$this->ajax_return['bal_type_id'] = $bal_type_id;
			$this->ajax_return['bal_type_title'] = $bal_type_title;

			if ($bal_type_title=='')
				{
				$this->ajax_return['info'] = 'Название обязательно!';
				}
			else if ($this->db->uni_select_one('bal_types',['bal_type_id'=>['eq'=>'<>','val'=>$bal_type_id],'bal_type_title'=>$bal_type_title])!==false)
				{
				$this->ajax_return['info'] = 'Данное название уже есть!';
				}
			else
				{
				$upd = [
						'bal_type_title' => $bal_type_title,
						];

				if ($bal_type_id==0) // NEW BAL TYPE
					{
					$new_bal_type_id = $this->db->uni_insert('bal_types',$upd);
					$this->ajax_return['result'] = true;
					$this->ajax_return['is_new'] = true;
					$this->ajax_return['bal_type_id'] = $bal_type_id;

					$this->db->logWrite(LOG_NEW_BAL_TYPE,'',$bal_type_title,ACC_ADMIN,$this->adminId());
					}
				else // UPDATE
					{
					$old_data = $this->db->uni_select_one('bal_types',['bal_type_id'=>$bal_type_id]);

					$this->db->uni_update('bal_types',['bal_type_id'=>$bal_type_id],$upd);
					$this->ajax_return['result'] = true;
					$this->ajax_return['bal_type_id'] = $bal_type_id;

					$new_data = $this->db->uni_select_one('bal_types',['bal_type_id'=>$bal_type_id]);

					$this->db->logWrite(LOG_UPD_BAL_TYPE,$old_data['bal_type_title'],$new_data['bal_type_title'],ACC_ADMIN,$this->adminId());
					}
				}
			}
		}

	}