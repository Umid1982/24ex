<?

session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');

$BASE = new BASE($config['db']);

// COINPAYMENTS
$uis = $BASE->getWorkInvestDepos();
if (count($uis)>0)
	{
	$now_time = time();
	$week_day = date('w');

	$all_plans = [];

	foreach ($uis as $one)
		{
		$plan_id = $one['plan_id'];
		if (!isset($all_plans[$plan_id])) $all_plans[$plan_id] = $BASE->getInvestPlanOne($one['plan_id']);

		$plan_data = $all_plans[$plan_id];
		if ($plan_data['plan_active']==0) continue; // план не активный

		// прошло ли время
		$end_time = $one['ui_dt_start'] + $plan_data['plan_max_time']*60*60;
		if ($end_time<$now_time) // переводим в END
			{
			if ($plan_data['plan_refund_depo']==1) // если надо вернуть деньги на счет
				{
				$new_status = INV_STATUS_PAYED;

				// переводим на баланс
				$BASE->writeInvestLog($one['ui_id'],INV_LOG_PAYED,$one['ui_value_now']);
				$BASE->changeUserBal($one['user_id'],$one['ub_id'],PS_TYPE_IN,$one['ui_value_now'],REASON_INVEST_PAYED,$one['ui_id']);
				}
			else
				{
				$new_status = INV_STATUS_END;
				}

			$BASE->uni_update('users_invest',['ui_id'=>$one['ui_id']],['ui_status'=>$new_status]);

			continue;
			}

		// проверяем дни недели
		$wds = json_decode($plan_data['plan_days'],true);
		if (count($wds)==0) continue; // ни в один день
		if (!isset($wds[$week_day])) continue; // не тот день

		// проверяем время начисления
		$time_to_check = $one['ui_dt_last_calc'] + $plan_data['plan_time']*60*60 - 5*60;
		if ($time_to_check>$now_time) continue; // рано начислять

		try
			{
			$BASE->trans_begin();

			// начисляем
			// сумма от типа процента
			if ($plan_data['plan_compound']==1) $proc_val = $one['ui_value_now'] * $plan_data['plan_proc'] / 100;
			else $proc_val = $one['ui_value_start'] * $plan_data['plan_proc'] / 100;

			$BASE->writeInvestLog($one['ui_id'],INV_LOG_PROC,$proc_val);

			// рефералка по процентам
			$BASE->refPayments($one['user_id'],$one['bal_id'],'invest_proc',$proc_val,$one['ui_id']);

			// обновляем время и сумму
			$upd = [
					'ui_dt_last_calc' => time(),
					'ui_value_now' => ($one['ui_value_now'] + $proc_val),
					];
			$BASE->uni_update('users_invest',['ui_id'=>$one['ui_id']],$upd);
			
			$BASE->trans_commit();
			}
		catch (Exception $e)
			{
			$BASE->trans_rollback();
			}

		// закончили
		}
	}
?>