<?php

error_reporting(E_ALL);
session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/core/includes/init.php');
require_once( _INC_ . '/site.init.php');

$config = include(__DIR__ . '/config.php');
$BASE = new BASE($config['db']);

require_once( _INC_ . '/paysys.init.php');
$PAYSYS = initializePaysys($BASE);

$params = getUrlParams();
if ($params===false) goRedir('/');

define( '_LANG_' , $params['lang'] );

$page = getPage($params['pages']);
$model_name = getModelName($page);

$MODEL = new $model_name($BASE,$page,$PAYSYS);
$VIEW = new View($config['lang']['default']);
$PRESENT = new Presenter($MODEL,$VIEW);

$PRESENT->printPage($page);

// отладка времени геренации
$gen_time_end = microtime(true);
//echo '<p><i>Generated in '.round($gen_time_end - $gen_time_start,4).'s</i></p>';


if (isset($_GET['genUserNums'])) {
	$all = $BASE->uni_select('users');
	foreach ($all as $one) {
		$BASE->uni_update('users',['user_id'=>$one['user_id']],['user_bal_num'=>$BASE->genMainUserBalNum()]);
	}
}

?>