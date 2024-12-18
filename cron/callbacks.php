<?

session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');

$BASE = new BASE($config['db']);

// COINPAYMENTS
$clbs = $BASE->getCallbacksToSend();
if (count($clbs)>0)
	{
	foreach ($clbs as $one)
		{
		$m_data = $BASE->uni_select_one('merchants',['user_id'=>$one['user_id'],'m_num'=>$one['m_num']]);
		$url = $m_data['m_url_callback'];

		$order_data = $BASE->uni_select_one('orders',['order_id'=>$one['order_id']]);
		$orderid = $order_data['order_id_shop'];

		$arHash = array(
			$one['m_num'],
			$orderid,
			$one['cbl_status'],
			$m_data['m_api_key'],
		);
		$sign = strtoupper(sha1(implode(':', $arHash))); // генерируем подпись

		$data = [
				'r_shop' => $one['m_num'],
				'r_orderid' => $orderid,
				'r_status' => $one['cbl_send_status'],
				'r_sign' => $sign
				];

		$send_result = sendCallbackData($url,$data);
		if ($send_result===false)
			{
			$status = ORDER_CALLBACK_ERROR;
			$answer = ANSWER_ERR_EMPTY;
			}
		else
			{
			if ($send_result['answer']!=ANSWER_OK)
				{
				$status = ORDER_CALLBACK_ERROR;
				$answer = $send_result['answer'];
				}
			else
				{
				$status = ORDER_CALLBACK_DONE;
				$answer = ANSWER_OK;
				}
			}

		$BASE->logCallback($one['cbl_id'],$status,$answer,$one['cbl_try']);
		}
	}
?>