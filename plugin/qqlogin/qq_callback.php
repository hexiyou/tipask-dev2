<?php

defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
define('TIPASK_ROOT', substr(dirname(__FILE__), 0, -15));
define(SITE_URL, 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, -30));
require_once TIPASK_ROOT . '/config.php';
require_once TIPASK_ROOT . '/lib/global.func.php';
require_once TIPASK_ROOT . '/lib/cache.class.php';
require_once TIPASK_ROOT . '/lib/db.class.php';
$db = new db(DB_HOST, DB_USER, DB_PW, DB_NAME, DB_CHARSET, DB_CONNECT);
$cache = new cache($db);
$setting = $cache->load('setting');
function qq_callback() {
    global $setting;
    if ($_REQUEST['state'] == tcookie('state')) { //csrf
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                . "client_id=" . $setting["qqlogin_appid"] . "&redirect_uri=" . urlencode(SITE_URL . "plugin/qqlogin/qq_callback.php")
                . "&client_secret=" . $setting["qqlogin_key"] . "&code=" . $_REQUEST["code"];

        $response = get_url_contents($token_url);
        if (strpos($response, "callback") !== false) {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
            $msg = json_decode($response);
            if (isset($msg->error)) {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }
        $params = array();
        parse_str($response, $params);
        header("Location:" . SITE_URL . "index.php?user/register/" . $params["access_token"]);
    } else {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}
qq_callback();

?>
