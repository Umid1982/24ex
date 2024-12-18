<?

session_start();

define('COINCASH',true);

$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');
require_once( _INC_ . '/admin.init.php');

$BASE = new BASE($config['db']);

require_once( _INC_ . '/paysys.init.php');

define( '_LANG_' , 'ru' );

$page = isset($_GET['page']) ? $_GET['page'] : 'index';
$model_name = getModelName($page);

$MODEL = new $model_name($BASE,$page,$PAYSYS);
$VIEW = new View($config['lang_admin']['default']);
$PRESENT = new Presenter($MODEL,$VIEW);

$PRESENT->printPage($page);

// отладка времени геренации
$gen_time_end = microtime(true);
//echo '<p><i>Generated in '.round($gen_time_end - $gen_time_start,4).'s</i></p>';

?>