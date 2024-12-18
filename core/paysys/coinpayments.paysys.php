<?

class CoinpaymentsPaysys extends Paysys
	{
	protected $name = 'coinpayments';

	function getOneVariants()
		{
		$from = [
				'ETH',
				'BTC',
				'BNB',
				'USDT.ERC20',
				'XRP',
				'TRX'
				];

		$to = $from;

		$variants = [];

		foreach ($from as $one_from)
			{
			foreach ($to as $one_to)
				{
				if ($one_from!=$one_to) continue;

				$var_name = $one_from;
				$variants[$var_name] = [
										'title' =>	'Coinpayments '.$one_from, // название для юзера
										'in'	=> true,
										'out'	=> false,
										];
				}
			}

		return $variants;
		}

	function genPaymentIn($var_name,$pay_id,$val,$user_email,$user_name)
		{
		global $config;

		//$temp = explode('-to-',$var_name);

		$params = [];
		$params['currency1'] = $var_name;
		$params['currency2'] = $var_name;
		$params['amount'] = $val;
		$params['buyer_email'] = $user_email;
		$params['buyer_name'] = $user_name;
		$params['item_name'] = 'pay_id';
		$params['item_number'] = $pay_id;
		$params['ipn_url'] = $config['site']['url'].'/'.$config['site']['callback'].'?ps=coinpayments';
		$params['success_url'] = $this->getSuccessUrl();
		$params['cancel_url'] = $this->getCancelUrl();

		$cpApi = new CoinPaymentsAPI();
		$json = $cpApi->CreateTransaction($params);

		if ($json===false) return ['error'=>'Wrong payment request','params'=>$params];
		if ($json['error']!='ok') return ['error'=>'PaySys error','error'=>$json['error']];
		
		$ret = [
				'error'			=> 'ok',
				'type'			=> 'link',
				'system_pay_id'	=> $json['result']['txn_id'],
				'amount'		=> $json['result']['amount'],
				'timeout' 		=> $json['result']['timeout'],
				'address' 		=> $json['result']['address'],
				'checkout_url' 	=> $json['result']['checkout_url'],
				'status_url' 	=> $json['result']['status_url'],
				'qrcode_url' 	=> $json['result']['qrcode_url'],
				'form'			=> '',
				];

		return $ret;
		}

	function confirmPayment()
		{
		global $config;

		$merchant_id = $config['coinpayments']['merchant_id'];
		$secret = $config['coinpayments']['ipn_secret'];

		$request = file_get_contents('php://input');

		if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) return ['result'=>false,'error'=>'no HMAC'];

		$merchant = isset($_POST['merchant']) ? $_POST['merchant'] : '';
		if ($merchant=='' || $merchant!=$merchant_id) return ['result'=>false,'error'=>'wrong marchant'];

		if ($request===FALSE || empty($request)) return ['result'=>false,'error'=>'no data'];

		$hmac = hash_hmac("sha512", $request, $secret);
		if ($hmac != $_SERVER['HTTP_HMAC']) return ['result'=>false,'error'=>'wrong HMAC'];

		$ipn_type = $_POST['ipn_type'];
		$txn_id = $_POST['txn_id'];
		$item_name = $_POST['item_name'];
		$item_number = (int)$_POST['item_number'];
		$amount1 = floatval($_POST['amount1']);
		$amount2 = floatval($_POST['amount2']);
		$currency1 = $_POST['currency1'];
		$currency2 = $_POST['currency2'];
		$status = intval($_POST['status']);
		$status_text = $_POST['status_text'];

		$pd = $this->db->uni_select_one('payments',['pay_id'=>$item_number]);
		if ($pd===false) return ['result'=>false,'error'=>'wrong payment ID'];

		$avaStatuses = [
						PAY_STATUS_NEW,
						PAY_STATUS_PENDING,
						PAY_STATUS_USER_PAYS,
						PAY_STATUS_GO_PAY
					];

		if (!in_array($pd['pay_status'], $avaStatuses)) return ['result'=>false,'error'=>'wrong payment status'];


/*
		$temp = explode('||',$pd['paysys']);
		$ps_name = $temp[0];
		if ($ps_name!='coinpayments') return ['result'=>false,'error'=>'wrong paysys'];

*/

		$pd_data = json_decode($pd['pay_sp_data'],true);
		$full_val = $pd_data['amount'];
		if ($amount1<$full_val) return ['result'=>false,'error'=>'wrong value'];


		if ($status >= 100 || $status == 2)
			{
        	// ok
			return [
			 		'result'	=>	true,
			 		'status'	=>	PAY_STATUS_PAYS,
			 		'pay_id'	=>	$pd['pay_id'],
			 		];
	    	} 
	    else if ($status < 0)
	    	{
	    	// error
			return [
					'result'	=>	true,
					'status'	=>	PAY_STATUS_REJECT,
			 		'pay_id'	=>	$pd['pay_id'],
					];
	    	}
	    else
	    	{
			// pending
			return [
					'result'	=>	true,
					'status'	=>	PAY_STATUS_PENDING,
			 		'pay_id'	=>	$pd['pay_id'],
					];
	    	}
		}

	}