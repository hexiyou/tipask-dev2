<?php
session_start();
/*the tipask entrance */
define('DEBUG', true);
if(DEBUG){
    ini_set("display_errors", "ON");
    error_reporting(E_ERROR|E_WARNING);
    //error_reporting(E_ALL);
}else{
    error_reporting(0);
}
//set_magic_quotes_runtime(0);
ini_set("magic_quotes_runtime",0);
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
define('IN_TIPASK', TRUE);
define('TIPASK_ROOT', dirname(__FILE__));
define('SITE_URL','http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-9));
include TIPASK_ROOT.'/model/tipask.class.php';
//include("../library/ZhengHe/ZhengHe.php");
$tipask = new tipask();
$tipask->run();

?>