<?

class HandPaysys extends Paysys
	{
	protected $name = 'hand';

	function getOneVariants()
		{
		$variants = [
							'simple' => [
										'title' =>	'В ручном режиме',	// название для юзера
										'in'	=> false,
										'out'	=> true,
										]
							];
		return $variants;
		}

	function genPaymentOut($var_name,$pay_id,$val,$user_email='',$user_name='')
		{
		global $config;

		$ret = [
				'error'	=> 'ok',
				];

		return $ret;		
		}
	}