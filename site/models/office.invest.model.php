<?php

class OfficeInvestModel extends OfficeModel
	{

	function vars2()
		{
		$all_bals = [];
		$all_plans = [];

		$plans = $this->db->uni_select('invest_plans',['plan_active'=>1]);
		if (count($plans)>0) foreach ($plans as $k=>$v)
			{
			if (!isset($all_bals[$v['bal_id']])) $all_bals[$v['bal_id']] = $this->db->getBalData($v['bal_id']);

			$bal_data = $all_bals[$v['bal_id']];
			$plans[$k]['bal_icon'] = getBalIcon($bal_data['bal_icon']);
			$plans[$k]['bal_name'] = $bal_data['bal_name'];

			$plans[$k]['plan_min'] = cutZeros($v['plan_min']);
			$plans[$k]['plan_max'] = cutZeros($v['plan_max']);
			$plans[$k]['plan_proc'] = cutZeros($v['plan_proc']);

			$all_plans[$v['plan_id']] = $v;
			}
		$this->all_vars['invest_plans'] = $plans;
		}

	function ajax2()
		{
        if ($_GET['ajax']=='getUIs')
            {
            $all_plans = [];

            $uis = $this->db->getUsersInvest($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,$this->userId(),@$this->ajax_qs['ui_status']);
            $total = $this->db->last_count();
            $pg_max = ceil($total / $this->ajax_pp);
            foreach ($uis as $k=>$v)
                {
                $uis[$k]['ui_id'] = (int)$v['ui_id'];

                if (!isset($all_plans[$v['plan_id']])) $all_plans[$v['plan_id']] = $this->db->getInvestPlanOne($v['plan_id']);
                $plan_data = $all_plans[$v['plan_id']];
                $uis[$k]['plan_name']  = $plan_data['plan_name'];
                $uis[$k]['plan_proc']  = cutZeros($plan_data['plan_proc']);
                $uis[$k]['plan_time']  = $plan_data['plan_time'];
                $uis[$k]['plan_max_time']  = $plan_data['plan_max_time'];
				$uis[$k]['plan_min'] = cutZeros($plan_data['plan_min']);
				$uis[$k]['plan_max'] = cutZeros($plan_data['plan_max']);

                $bal_data = $this->db->getBalData($v['bal_id']);
                $uis[$k]['bal_name'] = $bal_data['bal_name'];
                $uis[$k]['bal_title'] = $bal_data['bal_title'];

                $uis[$k]['ui_value_start'] = cutZeros($v['ui_value_start']);
                $uis[$k]['ui_value_now'] = cutZeros($v['ui_value_now']);

                $uis[$k]['ui_dt_start_line'] = date('d.m.y H:i',$v['ui_dt_start']);
                $uis[$k]['ui_dt_last_calc_line'] = $v['ui_dt_last_calc']==0 ? '-' : date('d.m.y H:i',$v['ui_dt_last_calc']);

                $uis[$k]['ui_status_line'] = getInvStatusText($v['ui_status']);
                $uis[$k]['ui_dt_end_line'] = date('d.m.y H:i',($v['ui_dt_start'] + $plan_data['plan_max_time']*60*60));

				$defrost_time = $v['ui_dt_start'] + ($plan_data['plan_time_defrost']*60*60);
				if ($v['ui_status']==INV_STATUS_ON && $plan_data['plan_defrost']==1 && $defrost_time<=time()) $uis[$k]['can_unfreeze'] = true;
				else $uis[$k]['can_unfreeze'] = false;

                if ($v['ui_status']==INV_STATUS_ON && $v['ui_value_start']<$plan_data['plan_max']) $uis[$k]['can_append'] = true;
				else $uis[$k]['can_append'] = false;
                }

            $this->ajax_return['data'] = $uis;
            $this->ajax_return['meta'] = [
                                            "page" => $this->ajax_pg,
                                            "pages" => $pg_max,
                                            "perpage" => $this->ajax_pp,
                                            "total" => $total,
                                            "sort" => @$this->ajax_sort['sort'],
                                            "field" => @$this->ajax_sort['field']
                                         ];
            }

		if ($_GET['ajax']=='newInvest')
			{
			$plan_id = (int)@$_POST['plan_id'];
			$val = number_format((double)@$_POST['value'],10,'.','');

			$plan_data = $this->db->getInvestPlanOne($plan_id);

			if ($plan_data===false)
				{
				$this->ajax_return['info'] = 'План не найден';
				}
			else if ($val < $plan_data['plan_min'] || $val > $plan_data['plan_max'])
				{
				$this->ajax_return['info'] = 'Сумма должна быть в диапозоне '.cutZeros($plan_data['plan_min']).'-'.cutZeros($plan_data['plan_max']);
				}
			else
				{
				$ub_data = $this->db->getUbByBal($this->userId(),$plan_data['bal_id']);
				if ($ub_data===false)
					{
					$this->ajax_return['info'] = 'У Вас нет нужного баланса';
					}
				else if ($ub_data['ub_value']<$val)
					{
					$this->ajax_return['info'] = 'Недостаточно средств на счете';
					}
				else
					{
					// всё ок

					// создаем депозит
					$ui_id = $this->db->newUserInvest($this->userId(),$plan_id,$ub_data['bal_id'],$ub_data['ub_id'],$val,
														$plan_data['plan_time'],$plan_data['plan_max_time']);

					// пишем логи депозитов
					$this->db->writeInvestLog($ui_id,INV_LOG_NEW,$val);

					// списываем деньги
					$this->db->changeUserBal($this->userId(),$ub_data['ub_id'],PS_TYPE_OUT,$val,REASON_INVEST_NEW,$ui_id);

					// логи юзера
					$this->db->logWrite(LOG_USER_INVEST_NEW,'',$ui_id,ACC_USER,$this->userId());

					// отдаем
					$this->ajax_return['result'] = true;
					}
				}
			}


		if ($_GET['ajax']=='unFreezeUI')
			{
			$ui_id = (int)@$_POST['ui_id'];

			$ui_data = $this->db->uni_select_one('users_invest',['user_id'=>$this->userId(),'ui_id'=>$ui_id]);
			if ($ui_data===false)
				{
				$this->ajax_return['info'] = 'Депозит не найден';
				}
			else if ($ui_data['ui_status']!=INV_STATUS_ON)
				{
				$this->ajax_return['info'] = 'Депозит нельзя разморозить';
				}
			else
				{
				$plan_id = $ui_data['plan_id'];
				$plan_data = $this->db->getInvestPlanOne($plan_id);

				$defrost_time = $ui_data['ui_dt_start'] + ($plan_data['plan_time_defrost']*60*60);

				if ($plan_data['plan_defrost']!=1 || $defrost_time>time())
					{
					$this->ajax_return['info'] = 'Депозит нельзя разморозить';
					}
				else
					{
					// размораживаем
					$proc_val = $ui_data['ui_value_now'] * $plan_data['plan_percent_defrost'] / 100;
					$defrost_val = $ui_data['ui_value_now'] - $proc_val;

                    // переводим на баланс
                    $this->db->writeInvestLog($ui_id,INV_LOG_DEFROST_VAL,$defrost_val);
                    $this->db->writeInvestLog($ui_id,INV_LOG_DEFROST_PROC,$proc_val);

                    $this->db->changeUserBal($ui_data['user_id'],$ui_data['ub_id'],PS_TYPE_IN,$defrost_val,REASON_INVEST_DEFROST,$ui_id);					
                    // меняем статус
                    $this->db->uni_update('users_invest',['ui_id'=>$ui_id],['ui_status'=>INV_STATUS_DEFROST]);
                    $this->db->logWrite(LOG_USER_INVEST_DEFROST,$ui_id,'',ACC_USER,$this->userId());  

                    $this->ajax_return['result'] = true;
					}
				}
			}

		if ($_GET['ajax']=='appendInvest')
			{
			$ui_id = (int)@$_POST['ui_id'];
			$val = number_format((double)@$_POST['value'],10,'.','');

			$ui_data = $this->db->getUsersInvestOne($ui_id);
			if ($ui_data===false)
				{
				$this->ajax_return['info'] = 'Депозит не найден';
				}
			else if ($ui_data['user_id']!=$this->userId())
				{
				$this->ajax_return['info'] = 'Депозит не найден';
				}
			else if ($val==0)
				{
				$this->ajax_return['info'] = 'Нулевая сумма дополнения';
				}
			else
				{
				$plan_id = $ui_data['plan_id'];

				$plan_data = $this->db->getInvestPlanOne($plan_id);
				$max = $plan_data['plan_max'] - $ui_data['ui_value_start'];
				if ($val > $max)
					{
					$this->ajax_return['info'] = 'Общая сумма больше максимума по плану';
					}
				else
					{
					$ub_data = $this->db->getUbByBal($this->userId(),$plan_data['bal_id']);
					if ($ub_data['ub_value']<$val)
						{
						$this->ajax_return['info'] = 'Недостаточно средств на счете';
						}
					else
						{
						// всё ок

						// обновляем депозит
						$upd = [
								'ui_value_start' 	=> $ui_data['ui_value_start'] + $val,
								'ui_value_now' 		=> $ui_data['ui_value_now'] + $val,
								];
						$this->db->uni_update('users_invest',['ui_id'=>$ui_id],$upd);

						// пишем логи депозитов
						$this->db->writeInvestLog($ui_id,INV_LOG_APPEND,$val);

						// списываем деньги
						$this->db->changeUserBal($this->userId(),$ub_data['ub_id'],PS_TYPE_OUT,$val,REASON_INVEST_APPEND,$ui_id);

						// логи юзера
						$this->db->logWrite(LOG_USER_INVEST_APPEND,$ui_id,$val,ACC_USER,$this->userId());

						// отдаем
						$this->ajax_return['result'] = true;
						}
					}

				}
			}

		}

	}