<?php
session_start();
/*the tipask entrance */
//error_reporting(0);
ini_set("magic_quotes_runtime", 0);
$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
define('IN_TIPASK', TRUE);
define('TIPASK_ROOT', dirname(__FILE__));

//define('SITE_URL','http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-9) );
//define('SITE_URL','http://localhost/tipask/index.php' );
//include TIPASK_ROOT.'/model/tipask.class.php';
//include("../library/ZhengHe/ZhengHe.php");
//$tipask = new tipask();
//$tipask->run();

require 'config.php';
require 'lib/db.class.php';
require 'lib/global.func.php';
require 'lib/cache.class.php';
require 'model/base.class.php';
require 'model/buyjilu.class.php';
$qid = $_GET['qid'];
//echo $qid;
$base = new base($_GET,$_POST);
$_buyjilumodel = new buyjilumodel($base);
//v
$user_id = $base->user['uid'];
//print_r($base);exit();
if (!is_numeric($qid) || (!is_numeric($user_id))) {
	echo '<script language="javascript">';
	echo "window.location='index.php?q-".$qid.".html';";
	echo '</script>';exit();
}
//
$arr_questions = $_buyjilumodel->getquestionmsg($qid);
//print_r($arr_questions);exit();
if ($base->user['credit2'] < $arr_questions['price']) {
	echo '<script language="javascript">';
	echo 'alert("您的财富值不够了");';
	echo "window.location='index.php?q-".$qid.".html';";
	echo '</script>';exit();
}
//
$_buyjilumodel->add($qid,$user_id,$arr_questions['price'],$arr_questions['authorid']);
echo '<script language="javascript">';
echo "window.location='index.php?q-".$qid.".html';";
echo '</script>';exit();
?>