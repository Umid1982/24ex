<?

class PayeerPaysys extends Paysys
	{
	protected $name = 'payeer';

	function getOneVariants()
		{
		$variants = [];

		$variants['multi'] = [
								'title' =>	'Payeer USD', // название для юзера
								'in'	=> true,
								'out'	=> false,
								];
				
			

		return $variants;
		}

	function genPaymentIn($var_name,$pay_id,$val,$user_email,$user_name)
		{
		global $config;

		$m_shop = $config['payeer']['shop_id'];
		$m_orderid = 'payeer_'.$pay_id;
		$m_amount = number_format($val, 2, '.', '');
		$m_curr = 'USD';
		$m_desc = base64_encode('pay');
		$m_key = $config['payeer']['secret'];

		$arHash = array(
			$m_shop,
			$m_orderid,
			$m_amount,
			$m_curr,
			$m_desc
		);

		
		$arParams = array(
			'success_url' 	=> $config['site']['url'].'/'._LANG_.'/office/paystat',
			'fail_url' 		=> $config['site']['url'].'/'._LANG_.'/office/paystat',
			'status_url' 	=> $config['site']['url'].'/'._LANG_.'/office/paystat',
		);

		$key = md5($config['payeer']['enc_key'].$m_orderid);

		$m_params = @urlencode(base64_encode(openssl_encrypt(json_encode($arParams), 'AES-256-CBC', $key, OPENSSL_RAW_DATA)));

		$arHash[] = $m_params;
		$arHash[] = $m_key;

		$sign = strtoupper(hash('sha256', implode(':', $arHash)));

		$form = [
				'm_shop' => $m_shop,
				'm_orderid' => $m_orderid,
				'm_amount' => $m_amount,
				'm_curr' => $m_curr,
				'm_desc' => $m_desc,
				'm_sign' => $sign,
				'm_params' => $m_params,
				'm_cipher_method' => 'AES-256-CBC',
				'lang' => _LANG_,
				];
		$gets = [];
		foreach ($form as $k=>$v) $gets[] = $k.'='.urlencode($v);
		$link = 'https://payeer.com/merchant/?'.implode('&',$gets);

		$ret = [
				'error'			=> 'ok',
				'type'			=> 'link',
				'system_pay_id'	=> $m_orderid,
				'amount'		=> $val,
				'usd_amount'	=> $m_amount,
				'timeout' 		=> '',
				'address' 		=> '',
				'checkout_url' 	=> $link,
				'status_url' 	=> $config['site']['url'].'/'._LANG_.'/office/paystat',
				'qrcode_url' 	=> '',
				'form'			=> $form,
				];

		return $ret;
		}
	}