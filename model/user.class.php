<?php

!defined('IN_TIPASK') && exit('Access Denied');

class usermodel {

    var $db;
    var $base;

    function usermodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get_by_uid($uid, $loginstatus=1) {
        $user = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user WHERE uid='$uid'");
        $user['avatar'] = get_avatar_dir($uid);
        $user['lastlogin'] = tdate($user['lastlogin']);
        $loginstatus && $user['islogin'] = $this->is_login($uid);
        return $user;
    }

    function get_by_username($username) {
        $user = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user WHERE username='$username'");
        return $user;
    }

    function get_by_email($email) {
        $user = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user WHERE email='$email'");
        return $user;
    }

    //找回密码
    function get_by_name_email($name, $email) {
        $user = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user WHERE email='$email' AND `username`='$name'");
        return $user;
    }

    /* 采纳率 */

    function adoptpercent($user) {
        $adoptpercent = 0;
        if (0 != $user['answers']) {
            $adoptpercent = round(($user['adopts'] / $user['answers']), 3) * 100;
        }
        return $adoptpercent;
    }

    function get_list($start=0, $limit=10) {
        $userlist = array();
        $query = $this->db->query("select * from " . DB_TABLEPRE . "user  order by uid DESC limit $start,$limit");
        while ($user = $this->db->fetch_array($query)) {
            $user['lastlogintime'] = tdate($user['lastlogin']);
            $user['regtime'] = tdate($user['regtime']);
            $userlist[] = $user;
        }
        return $userlist;
    }

    function list_by_search_condition($condition, $start=0, $limit=10) {
        $userlist = array();
        $query = $this->db->query('SELECT * FROM ' . DB_TABLEPRE . "user WHERE $condition ORDER BY `uid` DESC LIMIT $start , $limit");
        while ($user = $this->db->fetch_array($query)) {
            $user['regtime'] = tdate($user['regtime']);
            $user['lastlogintime'] = tdate($user['lastlogin']);
            $userlist[] = $user;
        }
        return $userlist;
    }

    /* 根据用户的一段时间的积分排序，只取前100名。 */

    function list_by_credit($type=0, $limit=100) {
        $userlist = array();
        $starttime = 0;
        if (1 == $type) {
            $starttime = $this->base->time - 7 * 24 * 3600;
        }
        if (2 == $type) {
            $starttime = $this->base->time - 30 * 24 * 3600;
        }
        $sqlarray = array(
            'SELECT u.uid,u.groupid, u.username,u.gender,u.lastlogin,u.avatar,u.credit1,u.questions,u.answers,u.adopts FROM ' . DB_TABLEPRE . "user  u ORDER BY `credit1` DESC LIMIT 0,$limit",
            "SELECT u.uid,u.groupid, u.username,u.gender,u.lastlogin,u.avatar,sum( c.credit1 ) credit1,u.questions,u.answers,u.adopts FROM " . DB_TABLEPRE . "user u," . DB_TABLEPRE . "credit c   WHERE u.uid=c.uid AND c.time>$starttime   GROUP BY u.uid ORDER BY credit1  DESC LIMIT 0,$limit",
            "SELECT u.uid,u.groupid, u.username,u.gender,u.lastlogin,u.avatar,sum( c.credit1 ) credit1,u.questions,u.answers,u.adopts  FROM " . DB_TABLEPRE . "user u," . DB_TABLEPRE . "credit c   WHERE u.uid=c.uid AND c.time>$starttime   GROUP BY u.uid ORDER BY credit1  DESC LIMIT 0,$limit"
        );
        $query = $this->db->query($sqlarray[$type]);
        while ($user = $this->db->fetch_array($query)) {
            $user['gender'] = (1 == $user['gender']) ? '男' : '女';
            $user['lastlogin'] = tdate($user['lastlogin']);
            $user['grouptitle'] = $this->base->usergroup[$user['groupid']]['grouptitle'];
            $user['avatar'] = get_avatar_dir($user['uid']);
            $userlist[] = $user;
        }
        return $userlist;
    }

    function refresh($uid, $islogin=1, $cookietime=0) {
        @$sid = tcookie('sid');
        $this->base->user = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user u," . DB_TABLEPRE . "usergroup g WHERE u.uid=$uid AND u.groupid=g.groupid");
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `lastlogin`={$this->base->time}  WHERE `uid`=$uid"); //更新最后登录时间
        $this->db->query("REPLACE INTO " . DB_TABLEPRE . "session (sid,uid,islogin,ip,`time`) VALUES ('$sid',$uid,$islogin,'{$this->base->ip}',{$this->base->time})");
        $password = $this->base->user['password'];
        $auth = strcode("$uid\t$password", $this->base->setting['auth_key'], 'ENCODE');
        if ($cookietime)
            tcookie('auth', $auth, $cookietime);
        else
            tcookie('auth', $auth);

        tcookie('loginuser', '');
        $this->base->user['newmsg'] = 0;
    }

    function refresh_session_time($sid, $uid) {
        $lastrefresh = tcookie("lastrefresh");
        if (!$lastrefresh) {
            if ($uid)
                $this->db->query("UPDATE " . DB_TABLEPRE . "session SET `time` = {$this->base->time} WHERE sid='$sid'");
            else
                $this->db->query("REPLACE INTO " . DB_TABLEPRE . "session (sid,`ip`,`time`) VALUES ('$sid','{$this->base->ip}',{$this->base->time})");
            tcookie("lastrefresh", '1', 60);
        }
    }

    /* 添加用户，本函数需要返回uid */

    function add($username, $password, $email='', $uid=0, $access_token='none') {
        $password = md5($password);
        if ($uid) {
            $this->db->query("REPLACE INTO  " . DB_TABLEPRE . "user (uid,username,password,email,regip,`lastlogin`,`access_token`) VALUES ('$uid','$username','$password','$email','" . getip() . "',{$this->base->time},'$access_token')");
        } else {
            $this->db->query("INSERT INTO " . DB_TABLEPRE . "user(username,password,email,regip,regtime,`lastlogin`,`access_token`) values ('$username','$password','$email','" . getip() . "',{$this->base->time},{$this->base->time},'$access_token')");
            $uid = $this->db->insert_id();
        }
        return $uid;
    }

    //ip地址限制
    function is_allowed_register() {
        $starttime = strtotime("-1 day");
        $endtime = strtotime("+1 day");
        $usernum = $this->db->result_first("SELECT count(*) FROM " . DB_TABLEPRE . "user WHERE regip='{$this->base->ip}' AND regtime>$starttime AND regtime<$endtime ");
        if ($usernum >= $this->base->setting['max_register_num']) {
            return false;
        }
        return true;
    }

    /* 修改用户密码 */

    function uppass($uid, $password) {
        $password = md5($password);
        $this->db->query('UPDATE ' . DB_TABLEPRE . "user SET `password`='" . $password . "' WHERE `uid`=$uid ");
    }

    /* 更新用户信息 */

    function update($uid, $gender, $bday, $phone, $qq, $msn, $signature, $isnotify=1) {
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `gender`='$gender',`bday`='$bday',`phone`='$phone',`qq`='$qq',`msn`='$msn',`signature`='$signature',`isnotify`='$isnotify'  WHERE `uid`=$uid");
    }

    function update_email($email, $uid) {
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `email`='$email' WHERE `uid`=$uid");
    }

    /* 礼品兑换用户信息 */

    function update_gift($uid, $realname, $email, $phone, $qq) {
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `realname`='$realname',`email`='$email',`phone`='$phone',`qq`='$qq' WHERE `uid`=$uid");
    }

    /* 后台更新用户信息 */

    function update_user($uid, $username, $passwd, $email, $groupid, $credits, $credit1, $credit2, $gender, $bday, $phone, $qq, $msn, $signature) {
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `username`='$username',`password`='$passwd',`email`='$email',`groupid`='$groupid',`credits`=$credits,`credit1`=$credit1,`credit2`=$credit2,`gender`='$gender',`bday`='$bday',`phone`='$phone',`qq`='$qq',`msn`='$msn',`signature`='$signature'  WHERE `uid`=$uid");
    }

    /* 更新authstr */

    function update_authstr($uid, $authstr) {
        $this->db->query("UPDATE " . DB_TABLEPRE . "user SET `authstr`='$authstr'  WHERE `uid`=$uid");
    }

    /* 删除用户 */

    function remove($uids, $all=0) {
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "user` WHERE `uid` IN ($uids)");
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "expert` WHERE `uid` IN ($uids)");
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "famous` WHERE `uid` IN ($uids)");
        /* 删除问题和回答 */
        if ($all) {
            $this->db->query("DELETE FROM `" . DB_TABLEPRE . "question` WHERE `authorid` IN ($uids)");
            $this->db->query("DELETE FROM `" . DB_TABLEPRE . "answer` WHERE `authorid` IN ($uids)");
            $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET answers=answers-1 WHERE `authorid` IN ($uids)");
        }
    }

    function logout() {
        $sid = $this->base->user['sid'];
        tcookie('sid', '', 0);
        tcookie('auth', '', 0);
        tcookie('loginuser', '', 0);
        if ($sid) {
            $this->db->query('DELETE FROM ' . DB_TABLEPRE . 'session WHERE sid=\'' . $sid . '\'');
        }
    }

    function save_code($code) {
        $uid = $this->base->user['uid'];
        $sid = $this->base->user['sid'];
        $islogin = $this->db->result_first("SELECT islogin FROM " . DB_TABLEPRE . "session WHERE sid='$sid'");
        $islogin = $islogin ? $islogin : 0;
        $this->db->query("REPLACE INTO " . DB_TABLEPRE . "session (sid,uid,code,islogin,`time`) VALUES ('$sid',$uid,'$code','$islogin',{$this->base->time})");
    }

    function get_code() {
        $sid = $this->base->user['sid'];
        return $this->db->result_first("SELECT code FROM " . DB_TABLEPRE . "session WHERE sid='$sid'");
    }

    function is_login($uid=0) {
        (!$uid) && $uid = $this->base->user['uid'];
        $onlinetime = $this->base->time - intval($this->base->setting['sum_onlineuser_time']) * 60;;
        $islogin = $this->db->result_first("SELECT islogin FROM " . DB_TABLEPRE . "session WHERE uid=$uid AND time>$onlinetime");
        if ($islogin && $uid > 0) {
            return $islogin;
        }
        return false;
    }

    /* 客服端通行证 */

    function passport_client() {
        $passport_action = 'passport_' . $this->base->get[1]; //login、logout、register
        $location = $this->base->setting['passport_server'] . '/' . $this->base->setting[$passport_action];
        $forward = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL;
        header('location:' . $location . (false === strpos($location, '?') ? '?' : '&') . 'forward=' . $forward);
        exit;
    }

    /* 服务端通行证 */

    function passport_server($forward) {
        $action = $this->base->get[1];
        ('register' == $action) && $action = 'login';
        $member['username'] = $this->base->user['username'];
        $member['password'] = $this->base->user['password'];
        $member['email'] = $this->base->user['email'];
        $member['cktime'] = $this->base->time + (60 * 60 * 24 * 365);
        $userstr = 'time=' . $this->base->time;
        foreach ($member as $key => $val) {
            $userstr .= "&$key=$val";
        }
        $userdb = strcode($userstr, $this->base->setting['passport_key'], 'ENCODE');
        $verify = md5($action . $userdb . $forward . $this->base->setting['passport_key']);
        $location = $this->base->setting['passport_client'] . '?action=' . $action . '&userdb=' . urlencode($userdb) . '&forward=' . urlencode($forward) . '&verify=' . $verify;
        header('location:' . $location);
        exit;
    }

    /* 用户积分明细 */

    function credit_detail($uid) {
        $detail1 = $detail2 = $detail3 = array('reward' => 0, 'punish' => 0, 'offer' => 0, 'adopt' => 0, 'other' => 0);
        $query = $this->db->query("SELECT * FROM " . DB_TABLEPRE . "credit c  where c.uid=" . $uid);
        while ($credit = $this->db->fetch_array($query)) {
            switch ($credit['operation']) {
                case 'reward'://奖励得分
                    $detail1['reward']+=$credit['credit1'];
                    $detail2['reward']+=$credit['credit2'];
                    $detail3['reward']+=$credit['credit3'];
                    break;
                case 'punish'://处罚得分
                    $detail1['punish']+=$credit['credit1'];
                    $detail2['punish']+=$credit['credit2'];
                    $detail3['punish']+=$credit['credit3'];
                    break;
                case 'offer'://悬赏付出
                    $detail2['offer']+=$credit['credit2'];
                    break;
                case 'adopt'://回答的问题被采纳为答案
                    $detail2['adopt']+=$credit['credit2'];
                    break;
                default:
                    $detail1['other']+=$credit['credit1'];
                    $detail2['other']+=$credit['credit2'];
                    $detail3['other']+=$credit['credit3'];
                    break;
            }
        }
        return array($detail1, $detail2);
    }

    /* 检测用户名合法性 */

    function check_usernamecensor($username) {
        $censorusername = $this->base->setting['censor_username'];
        $censorexp = '/^(' . str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censorusername = trim($censorusername)), '/')) . ')$/i';
        if ($censorusername && preg_match($censorexp, $username)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /* 检测邮件地址合法性 */

    function check_emailaccess($email) {
        $setting = $this->base->setting;
        $accessemail = $setting['access_email'];
        $censoremail = $setting['censor_email'];
        $accessexp = '/(' . str_replace("\r\n", '|', preg_quote(trim($accessemail), '/')) . ')$/i';
        $censorexp = '/(' . str_replace("\r\n", '|', preg_quote(trim($censoremail), '/')) . ')$/i';
        if ($accessemail || $censoremail) {
            if (($accessemail && !preg_match($accessexp, $email)) || ($censoremail && preg_match($censorexp, $email))) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    function update_elect($uid, $elect) {
        $elect && $elect = $this->base->time;
        $this->db->query("UPDATE `" . DB_TABLEPRE . "user` SET `elect`=$elect WHERE `uid`=$uid");
    }

    function update_avatar($avatar='', $uid=0) {
        (!$uid) && $uid = $this->base->user['uid'];
        $this->db->query("UPDATE `" . DB_TABLEPRE . "user` SET `avatar`='$avatar' WHERE `uid`=$uid");
    }

    /* 获取所有注册用户数目 */

    function rownum_alluser() {
        return array($this->db->fetch_total('user', ' 1=1'));
    }

    /* 获取所有在线用户数目 */

    function rownum_onlineuser() {
        $end = $this->base->time - intval($this->base->setting['sum_onlineuser_time']) * 60;
        return array($this->db->result_first("SELECT COUNT(DISTINCT `ip`) FROM " . DB_TABLEPRE . "session WHERE time>$end"));
    }

    function get_openid($code) {
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token="
                . $code;

        $str = get_url_contents($graph_url);
        if (strpos($str, "callback") !== false) {
            $lpos = strpos($str, "(");
            $rpos = strrpos($str, ")");
            $str = substr($str, $lpos + 1, $rpos - $lpos - 1);
        }

        $user = json_decode($str);
        if (isset($user->error)) {
            return false;
        }
        return $user->openid;
    }

    function get_oauth_info($code, $openid) {
        $get_user_info = "https://graph.qq.com/user/get_user_info?"
                . "access_token=" . $code
                . "&oauth_consumer_key=" . $this->base->setting['qqlogin_appid']
                . "&openid=" . $openid
                . "&format=json";
        $info = get_url_contents($get_user_info);
        $arr = json_decode($info, true);
        return $arr;
    }

    /* 第三方登陆 */

    function get_by_access_token($access_token) {
        return $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "user WHERE `access_token`='$access_token'");
    }

}

?>