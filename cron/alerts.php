<?

session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');

$BASE = new BASE($config['db']);

// COINPAYMENTS
$alerts = $BASE->getAlertsToSend();
if (count($alerts)>0)
	{
	$TG = new TG($config['tg']['token']);

	$accs = [];

	foreach ($alerts as $one)
		{
		$kk = $one['acc_type'].'_'.$one['acc_id'];
		if (!isset($accs[$kk])) $accs[$kk] = $BASE->getAcc($one['acc_type'],$one['acc_id']);
		if ($accs[$kk]===false)
			{
			$BASE->uni_update('alerts',['a_id'=>$one['a_id']],['a_status'=>A_STATUS_ERR]);
			}
		else
			{
			$TG->sendMess($accs[$kk]['tg_chat_id'],$one['a_mess']);
			$BASE->uni_update('alerts',['a_id'=>$one['a_id']],['a_status'=>A_STATUS_SEND,'a_dt_send'=>time()]);
			}
		}
	}
?>