<?php

class InvestplanoneModel extends Model
	{
	protected $rights_mask = CAN_SUPER;

	function vars()
		{
		if (!isset($_GET['plan_id'])) goRedir('investplans',true);

		$plan_id = (int)@$_GET['plan_id'];
		if ($plan_id!=0)
			{
			$one_plan = $this->db->uni_select_one('invest_plans',['plan_id'=>$plan_id]);
			if ($one_plan===false) goRedir('investplans',true);
			}
		else
			{
			$one_plan = $this->db->get_columns_defaults('invest_plans');
			$one_plan['plan_name'] = 'Новый план';
			$one_plan['plan_days'] = json_encode(['1'=>'1','2'=>'1','3'=>'1','4'=>'1','5'=>'1','6'=>'1','7'=>'1']);
			}

		$days = json_decode($one_plan['plan_days'],true);
		if (@count($days)>0) foreach ($days as $one_day=>$trash) $this->all_vars['days_'.$one_day] = true;

		foreach ($one_plan as $k=>$v) $this->all_vars[$k] = $v;

		// реф планы инвест
		$refplans = $this->db->uni_select('ref_plans',['rp_type'=>RP_INVEST]);
		if (count($refplans)>0) foreach ($refplans as $k=>$v)
			{
			$refplans[$k]['rp_id'] = (int)$v['rp_id'];
			$refplans[$k]['prcs_line'] = '% '.implode('<span style="color:#aaa">-</span>',json_decode($v['rp_prcs'],true));
			}
		$this->all_vars['refplans'] = $refplans;

		// балансы
		$bals = $this->db->getAllBals();
		$this->all_vars['bals'] = $bals;
		}

	function ajax()
		{
		if ($_GET['ajax']=='savePlan')
			{
			$id = (int)@$_POST['plan_id'];
			$name = trim(@$_POST['name']);

			$active = isset($_POST['active']) ? 1 : 0;

			$min = @$_POST['min'];
			$max  = @$_POST['max'];
			$proc  = @$_POST['proc'];
			$compound = isset($_POST['compound']) ? 1 : 0;

			$time = @$_POST['time'];
			$max_time = @$_POST['max_time'];

			$defrost = isset($_POST['defrost']) ? 1 : 0;
			$time_defrost = (int)@$_POST['time_defrost'];
			$percent_defrost = @$_POST['percent_defrost'];

			$refund_depo = isset($_POST['refund_depo']) ? 1 : 0;

			$plan_days = isset($_POST['plan_days']) ? json_encode($_POST['plan_days']) : '[]';

			$rp_id_depo = @$_POST['rp_id_depo']=='' ? NULL : (int)@$_POST['rp_id_depo'];
			$rp_id_proc = @$_POST['rp_id_proc']=='' ? NULL : (int)@$_POST['rp_id_proc'];

			$bal_id = (int)@$_POST['bal_id'];
			$bal_data = $this->db->getBalData($bal_id);

			if ($name=='')
				{
				$this->ajax_return['info'] = 'Заполните название!';
				}
			else if ($rp_id_depo!==NULL && ($rp_data_depo = $this->db->getRefPlanOne($rp_id_depo))===false)
				{
				$this->ajax_return['info'] = 'Реферальный план депозита не найден!';
				}
			else if ($rp_id_proc!==NULL && ($rp_data_proc = $this->db->getRefPlanOne($rp_id_proc))===false)
				{
				$this->ajax_return['info'] = 'Реферальный план % не найден!';
				}
			else if ($bal_data===false)
				{
				$this->ajax_return['info'] = 'Баланс не найден';
				}
			else
				{
				$upd = [
						'plan_name'=>$name,
						'plan_active'=>$active,

						'plan_min'=>$min,
						'plan_max'=>$max,
						'plan_proc'=>$proc,
						'plan_compound'=>$compound,

						'plan_time'=>$time,
						'plan_max_time'=>$max_time,

						'plan_defrost'=>$defrost,
						'plan_time_defrost'=>$time_defrost,
						'plan_percent_defrost'=>$percent_defrost,

						'plan_refund_depo'=>$refund_depo,
						'plan_days'=>$plan_days,

						'rp_id_depo'=>$rp_id_depo,
						'rp_id_proc'=>$rp_id_proc,
						'bal_id'=>$bal_id,
						];

				if ($id==0)
					{
					$id = $this->db->uni_insert('invest_plans',$upd);

					$this->db->logWrite(LOG_INVEST_PLAN_NEW,'',$id,ACC_ADMIN,$this->adminId());		
					}
				else
					{
					$this->db->uni_update('invest_plans',['plan_id'=>$id],$upd);

					$this->db->logWrite(LOG_INVEST_PLAN_UPD,$id,'',ACC_ADMIN,$this->adminId());	
					}

				$this->ajax_return['result'] = true;
				$this->ajax_return['plan_id'] = $id;
				}
			}

		if ($_GET['ajax']=='delPlan')
			{
			$id = (int)@$_POST['id'];

			if ($id!=0)
				{
				if ($this->db->uni_delete('invest_plans',['plan_id'=>$id]))
					{
					$this->db->logWrite(LOG_INVEST_PLAN_DEL,$id,'',ACC_ADMIN,$this->adminId());	
					$this->ajax_return['result'] = true;
					}
				else
					{
					$this->ajax_return['info'] = 'Ошибка удаления, план задействован в системе';
					}
				}
			else
				{
				$this->ajax_return['info'] = 'Баланс не найден';
				}
			}

		}

	}