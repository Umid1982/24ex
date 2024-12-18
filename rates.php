<?

error_reporting(E_ALL);
session_start();
define('COINCASH',true);
if (!isset($_GET['tp'])) die();

require_once( __DIR__ . '/core/includes/init.php');

$BASE = new BASE($config['db']);

require_once( __DIR__ . '/core/includes/paysys.init.php');

// все направления обмена, внешние
$all = $BASE->getAllRatesOut();
if (count($all['rates'])==0) die();

switch ($_GET['tp'])
	{

	case 'xml':

		header('Content-type: application/xml;');
		echo '<rates>';

		foreach ($all['rates'] as $one)
			{
			$from = $all['data'][$one['from']];
			$to = $all['data'][$one['to']];

			if (@$_GET['mod']==1)
				{
				$from_name = $from['bal_title'];
				$to_name = $to['bal_title'];
				}
			else if (@$_GET['mod']==2)
				{
				$from_name = $from['bal_id'];
				$to_name = $to['bal_id'];
				}
			else
				{
				$from_name = $from['bal_name'];
				$to_name = $to['bal_name'];
				}

			echo '<item>';

			echo '<from>'.$from_name.'</from>';
			echo '<to>'.$to_name.'</to>';

			echo '<in>'.$one['in'].'</in>';
			echo '<out>'.$one['out'].'</out>';

			echo '<amount>'.$one['amount'].'</amount>';

			echo '<minamount>'.$one['min'].'</minamount>';
			echo '<maxamount>'.$one['max'].'</maxamount>';

			echo '</item>';
			}

		echo '</rates>';

	break;

	case 'json':

		header('Content-type: application/json;');

		$ret = ['rates'=>[]];

		foreach ($all['rates'] as $one)
			{
			$from = $all['data'][$one['from']];
			$to = $all['data'][$one['to']];

			if (@$_GET['mod']==1)
				{
				$from_name = $from['bal_title'];
				$to_name = $to['bal_title'];
				}
			else if (@$_GET['mod']==2)
				{
				$from_name = $from['bal_id'];
				$to_name = $to['bal_id'];
				}
			else
				{
				$from_name = $from['bal_name'];
				$to_name = $to['bal_name'];
				}

			$one_ret = [];
			$one_ret['from'] = $from_name;
			$one_ret['to'] = $to_name;

			$one_ret['in'] = $one['in'];
			$one_ret['out'] = $one['out'];

			$one_ret['amount'] = $one['amount'];
			$one_ret['in_min_amount'] = $one['min'];

			$ret['rates'][] = $one_ret;
			}

		echo json_encode($ret);

	break;

	case 'txt':

		header('Content-type: text/plain;');

		foreach ($all['rates'] as $one)
			{
			$from = $all['data'][$one['from']];
			$to = $all['data'][$one['to']];

			if (@$_GET['mod']==1)
				{
				$from_name = $from['bal_title'];
				$to_name = $to['bal_title'];
				}
			else if (@$_GET['mod']==2)
				{
				$from_name = $from['bal_id'];
				$to_name = $to['bal_id'];
				}
			else
				{
				$from_name = $from['bal_name'];
				$to_name = $to['bal_name'];
				}

			echo $from_name.';'.$to_name.';'.$one['in'].';'.$one['out'].';'.$one['amount']."\r\n";
			}

	break;

	}

?>