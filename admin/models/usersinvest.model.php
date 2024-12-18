<?php

class UsersinvestModel extends Model
    {
    protected $rights_mask = CAN_ADMIN;

    function vars()
        {
        $this->all_vars['all_users'] = $this->db->uni_select('users');
        }

    function ajax()
        {
        global $config;

        if ($_GET['ajax']=='getUsersInvest')
            {
            $all_plans = [];

            $uis = $this->db->getUsersInvest($this->ajax_pg,$this->ajax_pp,$this->ajax_sort,@$this->ajax_qs['user_id'],@$this->ajax_qs['ui_status']);
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


                $bal_data = $this->db->getBalData($v['bal_id']);
                $uis[$k]['bal_name'] = $bal_data['bal_name'];
                $uis[$k]['bal_title'] = $bal_data['bal_title'];

                $user_data = $this->db->uni_select_one('users',['user_id'=>$v['user_id']]);
                $uis[$k]['user_email'] = $user_data['user_email'];


                $uis[$k]['ui_value_start'] = cutZeros($v['ui_value_start']);
                $uis[$k]['ui_value_now'] = cutZeros($v['ui_value_now']);

                $uis[$k]['ui_dt_start_line'] = date('d.m.y H:i',$v['ui_dt_start']);
                $uis[$k]['ui_dt_last_calc_line'] = $v['ui_dt_last_calc']==0 ? '-' : date('d.m.y H:i',$v['ui_dt_last_calc']);


                $uis[$k]['ui_status_line'] = getInvStatusText($v['ui_status']);

                if ($v['ui_status']==INV_STATUS_ON)  $uis[$k]['can_off'] = true;
                if ($v['ui_status']==INV_STATUS_OFF) $uis[$k]['can_on']  = true;
                if (in_array($v['ui_status'],[INV_STATUS_ON,INV_STATUS_OFF])) $uis[$k]['can_pay'] = true;


                $uis[$k]['ui_dt_end_line'] = date('d.m.y H:i',($v['ui_dt_start'] + $plan_data['plan_max_time']*60*60));
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

        if ($_GET['ajax']=='uiNewStatus')
            {
            $ui_id = (int)@$_POST['ui_id'];
            $new_status = (int)@$_POST['status'];

            $ui_data = $this->db->getUsersInvestOne($ui_id);
            if ($ui_data===false)
                {
                $this->ajax_return['info'] = 'Депозит не найден';
                }
            else
                {
                $ok = false;
                if ($new_status==INV_STATUS_ON && $ui_data['ui_status']==INV_STATUS_OFF) $ok = true;
                else if ($new_status==INV_STATUS_OFF && $ui_data['ui_status']==INV_STATUS_ON) $ok = true;
                else if ($new_status==INV_STATUS_PAYED && $ui_data['ui_status']!=INV_STATUS_PAYED) $ok = true;

                if (!$ok)
                    {
                    $this->ajax_return['info'] = 'Нельзя выполнить данное действие';
                    }
                else
                    {
                    if ($new_status==INV_STATUS_PAYED)
                        {
                        // переводим на баланс
                        $this->db->writeInvestLog($ui_id,INV_LOG_PAYED,$ui_data['ui_value_now']);
                        $this->db->changeUserBal($ui_data['user_id'],$ui_data['ub_id'],PS_TYPE_IN,$ui_data['ui_value_now'],REASON_INVEST_PAYED,$ui_id);
                        }

                    // меняем статус
                    $this->db->uni_update('users_invest',['ui_id'=>$ui_id],['ui_status'=>$new_status]);
                    $this->db->logWrite(LOG_USER_INVEST_UPD,$ui_id,$new_status,ACC_ADMIN,$this->adminId());  

                    $this->ajax_return['result'] = true; 
                    }
                }
            }

        }

    }