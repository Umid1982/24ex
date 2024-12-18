<?

// ваши данные
$m_shop = 'Y32WY7TGMVQCSNB5';	// ID магазина
$m_api_key = '3a938cfd5e1759c94faa594ad753657fc2e65fcc'; // API ключ

// все возможные статусы оплаты
define('PAY_SUCCESS', 0);			// оплата прошла
define('PAY_REJECT',  1);			// оплата отклонена

// все возможные коды ответов
define('ANSWER_OK', 0);					// запрос успешно обработан
define('ANSWER_ERR_SIGN', 1);			// неверная подпись
define('ANSWER_ERR_SHOP', 2);			// неверный магазин
define('ANSWER_ERR_ORDER_WRONG', 3);	// неверный заказ
define('ANSWER_ERR_ORDER_STATUS', 4);	// заказ уже был отменен или подтвержден

// получает параметры запроса
$r_shop = @$_REQUEST['r_shop']; 		// ID магазина
$r_orderid = @$_REQUEST['r_orderid']; 	// ID заказа
$r_status = @$_REQUEST['r_status'];	// статус оплаты
$r_sign = @$_REQUEST['r_sign'];		// подпись

$return = ['request'=>true,'answer'=>-1];

$arHash = array(
	$r_shop,
	$r_orderid,
	$r_status,
	$m_api_key,
);
$m_sign = strtoupper(sha1(implode(':', $arHash))); // генерируем подпись

// сравниваем подпись
if ($r_sign!=$m_sign)
	{
	$return['answer'] = ANSWER_ERR_SIGN;
	}
// сравниваем магазин
else if ($r_shop!=$m_shop)
	{
	$return['answer'] = ANSWER_ERR_SHOP;
	}
else
	{
	// для теста
	if ($r_orderid=='test') // всё ок
		{
		$return['answer'] = ANSWER_OK;
		}
	else if ($r_orderid=='fail') // заказ уже был обработан
		{
		$return['answer'] = ANSWER_ERR_ORDER_STATUS;
		}
	else // любой другой заказ
		{
		$return['answer'] = ANSWER_ERR_ORDER_WRONG;
		}
	}

header('Content-type: application/json');
die(json_encode($return));

?>