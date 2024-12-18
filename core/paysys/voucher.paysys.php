<?

class VoucherPaysys extends Paysys
	{
	protected $name = 'voucher';

	function getOneVariants()
		{
		$variants = [
							'voucher' => [
										'title' =>	'Ваучер',
										'in'	=> true,
										'out'	=> false,
										]
							];
		return $variants;
		}

	function genPaymentIn($var_name,$pay_id,$val,$user_email,$user_name)
		{
		global $config;

		$ret = [
				'error'			=> 'ok',
				'type'			=> 'voucher',
				'system_pay_id'	=> 0,
				'amount'		=> $val,
				'timeout' 		=> 24*60*60,
				'address' 		=> '',
				'checkout_url' 	=> $config['site']['url'].'/'._LANG_.'/office/paystat',
				'status_url' 	=> $config['site']['url'].'/'._LANG_.'/office/paystat',
				'qrcode_url' 	=> '',
				];

		return $ret;		
		}
	}