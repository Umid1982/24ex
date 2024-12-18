<?

session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');

$BASE = new BASE($config['db']);

// COINPAYMENTS
$bals = $BASE->uni_select('bals',['bal_rate_from'=>BAL_SRC_COINPAYMENTS]);
if (count($bals)>0)
	{
	$cpApi = new CoinPaymentsAPI();
	$all_rates = $cpApi->getRatesUsd();
	if (count($all_rates)>0)
		{
		foreach ($bals as $one)
			{
			$arg = trim(strtoupper($one['bal_rate_arg']));
			if ($arg!='' && isset($all_rates[$arg]))
				{
				$rate = $all_rates[$arg];
				$rate += $rate * ($one['bal_rate_com'] / 100);
				$BASE->uni_update('bals',['bal_id'=>$one['bal_id']],['bal_rate'=>$rate]);
				}
			}
		}
	}

// CRYPTONATOR
$bals = $BASE->uni_select('bals',['bal_rate_from'=>BAL_SRC_CRYPTONATOR]);
if (count($bals)>0)
	{
	foreach ($bals as $one)
		{
		$arg = trim(strtolower($one['bal_rate_arg']));
		if ($arg!='')
			{
			$url = 'https://api.cryptonator.com/api/ticker/'.$arg.'-usd';
			$json = @json_decode(file_get_contents($url),true);
			if (isset($json['ticker']['base']))
				{
				$rate = $json['ticker']['price'];
				$rate += $rate * ($one['bal_rate_com'] / 100);
				$BASE->uni_update('bals',['bal_id'=>$one['bal_id']],['bal_rate'=>$rate]);
				}
			}
		}
	}

// CBR
$bals = $BASE->uni_select('bals',['bal_rate_from'=>BAL_SRC_CBR]);
if (count($bals)>0)
	{
	$rates = getCbrXmlUsdRates();
	if (count($rates))
		{
		foreach ($bals as $one)
			{
			$arg = trim(strtoupper($one['bal_rate_arg']));
			if (isset($rates[$arg]))
				{
				$rate = $rates[$arg];
				$rate += $rate * ($one['bal_rate_com'] / 100);
				$BASE->uni_update('bals',['bal_id'=>$one['bal_id']],['bal_rate'=>$rate]);
				}
			}
		}
	}

// PRIVAT24
$bals = $BASE->uni_select('bals',['bal_rate_from'=>BAL_SRC_PRIVAT24]);
if (count($bals)>0)
	{
	$rates = getPrivat24UsdRates();
	if (count($rates))
		{
		foreach ($bals as $one)
			{
			$arg = trim(strtoupper($one['bal_rate_arg']));
			if (isset($rates[$arg]))
				{
				$rate = $rates[$arg];
				$rate += $rate * ($one['bal_rate_com'] / 100);
				$BASE->uni_update('bals',['bal_id'=>$one['bal_id']],['bal_rate'=>$rate]);
				}
			}
		}
	}
?>