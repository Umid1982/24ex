<?

// параметры запроса
if (isset($_GET['local']))
	{
	$url = 'http://coincash.loc';
	$user_id = 2;
	$user_api_key = 'AQVR8ZEJVJLOG2TX8PR71D0Y'; // API ключ
	}
else
	{
	$url = 'http://coincash.loc';
	$user_id = 2;
	$user_api_key = 'AQVR8ZEJVJLOG2TX8PR71D0Y'; // API ключ
	}

$acts = [
			[
			'act' => 'myBals',
			'params' => []
			],
			[
			'act' => 'balsCanAdd',
			'params' => []
			],
			[
			'act' => 'balAdd',
			'params' => [
						'bal_id' => 13,
						]
			],
			[
			'act' => 'transfer',
			'params' => [
						'my_id'	=> 15,
						'value' => 10,
						'wallet' => 'NM1FS8IBWC7V',
						'paycode' => '',
						]
			],
			[
			'act' => 'payIn',
			'params' => [
						'my_id'	=> 15,
						'value' => 10,
						]
			],
			[
			'act' => 'payOut',
			'params' => [
						'my_id' => 15,
						'value' => 10,
						]
			],
			[
			'act' => 'newVoucher',
			'params' => [
						'my_id' => 15,
						'value' => 10,
						]
			],
			[
			'act' => 'change',
			'params' => [
						'my_id' => 15,
						'value' => 10,
						'to_id' => 14,
						'paycode' => '',
						]
			],
			[
			'act' => 'payInfo',
			'params' => [
						'pay_id' => '',
						]
			]
		];


foreach ($acts as $one)
	{
	echo '<h2>'.$one['act'].'</h2>';
	echo '<form method="post" target="_blank" action="'.$url.'/api.php">';
	$arHash = [
			$user_id,
			$one['act'],
			$user_api_key
			];

	if (count($one['params'])>0)
		{
		foreach ($one['params'] as $k=>$v)
			{
			$arHash[] = $v;
			echo $k . ' - <input type="text" name="'.$k.'" value="'.$v.'"><br>';
			}
		}

	$sign = strtoupper(sha1(implode(':',$arHash)));

	echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
	echo '<input type="hidden" name="act" value="'.$one['act'].'">';
	echo '<input type="hidden" name="sign" value="'.$sign.'">';
	echo '<input type="submit" value="send" />';
	echo '</form>';
	}

?>

<form method="post" action="<?=$url;?>/api.php">
	<input type="hidden" name="m_shop" value="<?=$m_shop?>">
	<input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
	<input type="hidden" name="m_amount" value="<?=$m_amount?>">
	<input type="hidden" name="m_desc" value="<?=$m_desc?>">
	<input type="hidden" name="m_sign" value="<?=$m_sign?>">
	<input type="submit" value="send" />
</form>