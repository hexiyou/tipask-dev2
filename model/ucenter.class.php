<?php

!defined('IN_TIPASK') && exit('Access Denied');

class ucentermodel {

    var $db;
    var $base;

    function ucentermodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
        @include TIPASK_ROOT . '/data/ucconfig.inc.php';
        !defined('UC_API') && define('UC_API', '1');
        require_once TIPASK_ROOT . '/uc_client/client.php';
    }

    /* 连接ucenter服务器，生成ucconfig文件 */

    function connect($url, $password, $ip='') {
        $ucapi = preg_replace("/\/$/", '', trim($url));
        $ucip = trim($ip);
        if (!$ucip) {
            $temp = @parse_url($ucapi);
            $ucip = gethostbyname($temp['host']);
            if (ip2long($ucip) == -1 || ip2long($ucip) === FALSE) {
                $ucip = '';
            }
        }
        $ucinfo = uc_fopen2($ucapi . '/index.php?m=app&a=ucinfo&a=ucinfo', 500, '', '', 1, $ucip);
        list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);

        if ($status != 'UC_STATUS_OK') {
            exit('连接失败,请检查URL和密码是否正确！');
        }

        $postdata = "m=app&a=add&ucfounder=&ucfounderpw=" . urlencode($password) . "&apptype=" . urlencode('OTHER') . "&appname=" . urlencode($this->base->setting['site_name']) . "&appurl=" . urlencode(SITE_URL) . "&appip=&appcharset=" . TIPASK_CHARSET . '&appdbcharset=' . DB_CHARSET;
        $s = uc_fopen2($ucapi . '/index.php', 500, $postdata, '', 1, $ucip);
        if (empty($s)) {
            exit('不能连接到UCenter服务端!');
        } elseif ($s == '-1') {
            exit('UCenter密码错误!');
        } else {
            $ucs = explode('|', $s);
            if (empty($ucs[0]) || empty($ucs[1])) {
                exit('网络连接超时，不能返回数据，请稍后重试！');
            }
        }
        $ucdata = "<?php
define('UC_OPEN','1');
define('UC_CONNECT', 'uc_api_post');
define('UC_DBHOST', '$ucs[2]');
define('UC_DBUSER', '$ucs[4]');
define('UC_DBPW', '$ucs[5]');
define('UC_DBNAME', '$ucs[3]');
define('UC_DBCHARSET', '$ucs[6]');
define('UC_DBTABLEPRE', '$ucs[7]');
define('UC_KEY', '$ucs[0]');
define('UC_API', '$ucapi');
define('UC_CHARSET', '$ucs[8]');
define('UC_IP', '$ucip');
define('UC_APPID', '$ucs[1]');
?>";
        $bytes = writetofile(TIPASK_ROOT . '/data/ucconfig.inc.php', $ucdata);
        return (0 != $bytes);
    }

    /* 同步uc注册 */

    function login($username,$password) {
        //通过接口判断登录帐号的正确性，返回值为数组
        list($uid, $username, $password, $email) = uc_user_login($username, $password);
        if ($uid > 0) {
            if (!$this->db->result_first("SELECT count(*) FROM " . DB_TABLEPRE . "user WHERE uid='$uid'")) {
                $_ENV['user']->add($username, $password, $email, $uid);
            }
            $_ENV['user']->refresh($uid);
            //生成同步登录的代码
            $ucsynlogin = uc_user_synlogin($uid);
            $this->base->message('登录成功' . $ucsynlogin . '<br><a href="' . $_SERVER['PHP_SELF'] . '">继续</a>');
        } elseif ($uid == -1) {
            $this->base->message('用户不存在,或者被删除');
        } elseif ($uid == -2) {
            $this->base->message('密码错误');
        } else {
            $this->base->message('未定义');
        }
    }

    /* 同步uc注册 */

    function register() {
        $activeuser = uc_get_user($this->base->post['username']);
        if ($activeuser) {
            $this->base->message('该用户无需注册，请直接登录！<br><a href="index.php?user/login">继续</a>');
        }
        $uid = uc_user_register($this->base->post['username'], $this->base->post['password'], $this->base->post['email']);
        if ($uid <= 0) {
            if ($uid == -1) {
                $this->base->message('用户名不合法');
            } elseif ($uid == -2) {
                $this->base->message('包含要允许注册的词语');
            } elseif ($uid == -3) {
                $this->base->message('用户名已经存在');
            } elseif ($uid == -4) {
                $this->base->message('Email 格式有误');
            } elseif ($uid == -5) {
                $this->base->message('Email 不允许注册');
            } elseif ($uid == -6) {
                $this->base->message('该 Email 已经被注册');
            } else {
                $this->base->message('未定义');
            }
        } else {

            if (isset($this->base->post['access_token'])) {
                $uid = $_ENV['user']->add($this->base->post['username'], $this->base->post['password'], $this->base->post['email'], $uid, $this->base->post['access_token']);
            } else {
                $_ENV['user']->add($this->base->post['username'], $this->base->post['password'], $this->base->post['email'],$uid);
            }
            $_ENV['user']->refresh($uid);
            $ucsynlogin = uc_user_synlogin($uid);
            $this->base->message('注册成功' . $ucsynlogin . '<br><a href="' . $_SERVER['PHP_SELF'] . '">继续</a>');
        }
    }

    /* 同步uc退出系统 */

    function logout() {
        $_ENV['user']->logout();
        $ucsynlogout = uc_user_synlogout();
        $this->base->message('退出成功' . $ucsynlogout . '<br><a href="' . $_SERVER['PHP_SELF'] . '">继续</a>');
    }

    /**
     * 兑换积分
     * @param  integer $uid 用户ID
     * @param  integer $fromcredits 原积分
     * @param  integer $tocredits 目标积分
     * @param  integer $toappid 目标应用ID
     * @param  integer $amount 积分数额
     * @return boolean
     */
    function exchange($uid, $fromcredits, $tocredits, $toappid, $amount) {
        $ucresult = uc_credit_exchange_request($uid, $fromcredits, $tocredits, $toappid, $amount);
        return $ucresult;
    }

    /* 提出问题feed */

    function ask_feed($qid, $title, $description) {
        global $setting;
        $feed = array();
        $feed['icon'] = 'thread';
        $feed['title_template'] = '<b>{author} 在 {app} 发出了问题求助</b>';
        $feed["title_data"] = array(
            "author" => '<a href="space.php?uid=' . $this->base->user['uid'] . '">' . $this->base->user['username'] . '</a>',
            "app" => '<a href="' . SITE_URL . '">' . $setting['site_name'] . '</a>'
        );
        $feed['body_template'] = '<b>{subject}</b><br>{message}';
        $feed["body_data"] = array(
            "subject" => '<a href="' . SITE_URL . $setting['seo_prefix'] . 'question/view/' . $qid . $setting['seo_suffix'] . '">' . $title . '</a>',
            "message" => $description
        );
        uc_feed_add($feed['icon'], $this->base->user['uid'], $this->base->user['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data']);
    }

    /* 回答问题feed */

    function answer_feed($question, $content) {
        global $setting;
        $feed = array();
        $feed['icon'] = 'post';
        $feed['title_template'] = '<b>{author} 在 {app} 回答了{asker} 的问题</b>';
        $feed["title_data"] = array(
            "author" => '<a href="space.php?uid=' . $this->base->user['uid'] . '">' . $this->base->user['username'] . '</a>',
            "asker" => '<a href="space.php?uid=' . $question['authorid'] . '">' . $question['author'] . '</a>',
            "app" => '<a href="' . SITE_URL . '">' . $setting['site_name'] . '</a>'
        );
        $feed['body_template'] = '<b>{subject}</b><br>{message}';
        $feed["body_data"] = array(
            "subject" => '<a href="' . SITE_URL . $setting['seo_prefix'] . 'question/view/' . $question['id'] . $setting['seo_suffix'] . '">' . $question['title'] . '</a>',
            "message" => $content
        );
        uc_feed_add($feed['icon'], $this->base->user['uid'], $this->base->user['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data']);
    }

    function set_avatar($uid) {
        return uc_avatar($uid);
    }
    
    function uppass($username,$oldpw,$newpw,$email){
        uc_user_edit($username,$oldpw,$newpw,$email);
    }

}

?>
