<?

// параметры запроса
if (isset($_GET['local']))
	{
	$url = 'http://coincash.loc';
	$m_shop = '5YPK74Y06UJDMGSB';	// ID магазина
	$m_api_key = '68bf4de97f293547e62696f356c3a4f318af9b46'; // API ключ
	}
else
	{
	$url = 'https://pio.monster';
	$m_shop = 'UCRC8NOZBQXGTUCN';	// ID магазина
	$m_api_key = '0eea604dc75e59c69522925fe7ab651d06138980'; // API ключ	
	}


$m_orderid = 'order_'.uniqid(); // номер заказа
$m_amount = '0.3'; // сумма заказа, в $
$m_desc = 'Test'; // Название товара или описание

$arHash = array(
	$m_shop,
	$m_orderid,
	$m_amount,
	$m_desc,
	$m_api_key,
);
$m_sign = strtoupper(sha1(implode(':', $arHash))); // генерируем подпись

?>

<form method="post" action="<?=$url;?>/ru/payment/neworder">
	<input type="hidden" name="m_shop" value="<?=$m_shop?>">
	<input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
	<input type="hidden" name="m_amount" value="<?=$m_amount?>">
	<input type="hidden" name="m_desc" value="<?=$m_desc?>">
	<input type="hidden" name="m_sign" value="<?=$m_sign?>">
	<input type="submit" value="send" />
</form>