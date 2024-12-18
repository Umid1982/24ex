<?

session_start();
define('COINCASH',true);
$gen_time_start = microtime(true);

require_once( __DIR__ . '/../core/includes/init.php');

$BASE = new BASE($config['db']);
$BASE->unlockLockedUsers();