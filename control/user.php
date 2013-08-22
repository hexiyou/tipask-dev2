<?php

!defined('IN_TIPASK') && exit('Access Denied');

class usercontrol extends base {

    var $imagetypes = array('', 'gif', 'jpg', 'png');

    function usercontrol(& $get, & $post) {
        $this->base(& $get, & $post);
        $this->load('user');
        $this->load('question');
        $this->load('answer');
        $this->load("favorite");
    }

    function ondefault() {
        $this->onscore();
    }

    function oncode() {
        ob_clean();
        $code = random(4, 2);
        $_ENV['user']->save_code(strtolower($code));
        makecode($code);
    }

    function onregister() {
        if (isset($this->get[2])) {
            $access_token = $this->get[2];
            $user = $_ENV['user']->get_by_access_token($access_token);
            if ($user) {
                //ucenter登录成功，则不会继续执行后面的代码。
                if ($this->setting["ucenter_open"]) {
                    $this->load('ucenter');
                    $_ENV['ucenter']->login($user['username'], $user['password']);
                }
                $_ENV['user']->refresh($user['uid']);
            }
            $openid = $_ENV['user']->get_openid($access_token);
            (!$openid) && $this->message('qq互联错误,请联系管理员!', 'STOP');
            $userinfo = $_ENV['user']->get_oauth_info($access_token, $openid);
        }

        $navtitle = '注册新用户';
        $avatardir = "/data/avatar/";
        // if (!$this->setting['allow_register']) {
        //     $this->message("系统注册功能暂时处于关闭状态!", 'STOP');
        // }
        if (isset($this->base->setting['max_register_num']) && $this->base->setting['max_register_num'] && !$_ENV['user']->is_allowed_register()) {
            $this->message("您的当前的IP已经超过当日最大注册数目，如有疑问请联系管理员!", 'STOP');
            exit;
        }
        $forward = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL;
        $this->setting['passport_open'] && !$this->setting['passport_type'] && $_ENV['user']->passport_client(); //通行证处理
        if (isset($this->post['submit'])) {
             // $this->message("站点临时禁止用户注册!", 'user/register');
            $username = trim($this->post['username']);
            $password = trim($this->post['password']);
            $email = $this->post['email'];
            if ('' == $username || '' == $password) {
                $this->message("用户名或密码不能为空!", 'user/register');
            } else if (!preg_match("/^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/", $email)) {
                $this->message("邮件地址不合法!", 'user/register');
            } else if ($this->db->fetch_total('user', " email='$email' ")) {
                $this->message("此邮件地址已经注册!", 'user/register');
            } else if (!$_ENV['user']->check_usernamecensor($username)) {
                $this->message("邮件地址被禁止注册!", 'user/register');
            }
            $this->setting['code_register'] && $this->checkcode(); //检查验证码
            $user = $_ENV['user']->get_by_username($username);
            $user && $this->message("用户名 $username 已经存在!", 'user/register');
            //ucenter注册成功，则不会继续执行后面的代码。
            if ($this->setting["ucenter_open"]) {
                $this->load('ucenter');
                $_ENV['ucenter']->register();
            }
            if (isset($this->post['access_token'])) {
                $uid = $_ENV['user']->add($username, $password, $email, 0, $this->post['access_token']);
                if (isset($this->post['qqavatar']) && $this->post['qqavatar']) {
                    $uid = sprintf("%09d", $uid);
                    $dir1 = $avatardir . substr($uid, 0, 3);
                    $dir2 = $dir1 . '/' . substr($uid, 3, 2);
                    $dir3 = $dir2 . '/' . substr($uid, 5, 2);
                    (!is_dir(TIPASK_ROOT . $dir1)) && forcemkdir(TIPASK_ROOT . $dir1);
                    (!is_dir(TIPASK_ROOT . $dir2)) && forcemkdir(TIPASK_ROOT . $dir2);
                    (!is_dir(TIPASK_ROOT . $dir3)) && forcemkdir(TIPASK_ROOT . $dir3);
                    $smallimg = $dir3 . "/small_" . $uid . '.jpg';
                    $avatar_dir = glob(TIPASK_ROOT . $dir3 . "/small_{$uid}.*");
                    foreach ($avatar_dir as $imgfile) {
                        unlink($imgfile);
                    }
                    get_remote_image($this->post['qqavatar'], TIPASK_ROOT . $smallimg);
                }
            } else {
                $uid = $_ENV['user']->add($username, $password, $email);
            }

            $_ENV['user']->refresh($uid);
            $this->credit($this->user['uid'], $this->setting['credit1_register'], $this->setting['credit2_register']); //注册增加积分
            //通行证处理
            $forward = isset($this->post['forward']) ? $this->post['forward'] : SITE_URL;
            $this->setting['passport_open'] && $this->setting['passport_type'] && $_ENV['user']->passport_server($forward);
            //发送邮件通知
            $subject = "恭喜你在" . $this->setting['site_name'] . "注册成功！";
            $message = '<p>现在您可以登录<a swaped="true" target="_blank" href="' . SITE_URL . '">' . $this->setting['site_name'] . '</a>自由的提问和回答问题。祝您使用愉快。</p>';
            sendmail($this->user, $subject, $message);
            $this->message('恭喜，注册成功！');
        } else {
            include template('register');
        }
    }

    function onlogin() {
        $navtitle = '登录';
        $forward = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL;
        $this->setting['passport_open'] && !$this->setting['passport_type'] && $_ENV['user']->passport_client(); //通行证处理
        if (isset($this->post['submit'])) {
            $username = trim($this->post['username']);
            // if($username!='testuser'){
            //     $this->message('站点暂停用户登录，稍候开启！', 'user/login');
            // }

            $password = md5($this->post['password']);
            $cookietime = intval($this->post['cookietime']);
            //ucenter登录成功，则不会继续执行后面的代码。
            if ($this->setting["ucenter_open"]) {
                $this->load('ucenter');
                $_ENV['ucenter']->login($username, $password);
            }
            $this->setting['code_login'] && $this->checkcode(); //检查验证码
            $user = $_ENV['user']->get_by_username($username);
            if (is_array($user) && ($password == $user['password'])) {
                $_ENV['user']->refresh($user['uid'], 1, $cookietime);
                $forward = isset($this->post['forward']) ? $this->post['forward'] : SITE_URL; //通行证处理
                $this->setting['passport_open'] && $this->setting['passport_type'] && $_ENV['user']->passport_server($forward);
                $this->credit($this->user['uid'], $this->setting['credit1_login'], $this->setting['credit2_login']); //登录增加积分
                $this->message("登录成功!");
            } else {
                $this->message('用户名或密码错误！', 'user/login');
            }
        } else {
            include template('login');
        }
    }

    /* 用于ajax登录 */

    function onajaxlogin() {
        $username = $this->post['username'];
        if (TIPASK_CHARSET == 'GBK') {
            require_once(TIPASK_ROOT . '/lib/iconv.func.php');
            $username = utf8_to_gbk($username);
        }
        $password = md5($this->post['password']);
        $user = $_ENV['user']->get_by_username($username);
        if (is_array($user) && ($password == $user['password'])) {
            $_ENV['user']->refresh($user['uid']);
            exit('1');
        }
        exit('-1');
    }

//qqlogin
    function onqqlogin() {
        $state = md5(uniqid(rand(), TRUE));
        tcookie('state', $state);
        $login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id="
                . $this->setting['qqlogin_appid'] . "&redirect_uri=" . urlencode(SITE_URL . "plugin/qqlogin/qq_callback.php")
                . "&state=" . $state
                . "&scope=get_user_info";
        header("Location:$login_url");
    }

    /* 用于ajax检测用户名是否存在 */

    function onajaxusername() {
        $username = $this->post['username'];
        if (TIPASK_CHARSET == 'GBK') {
            require_once(TIPASK_ROOT . '/lib/iconv.func.php');
            $username = utf8_to_gbk($username);
        }
        $user = $_ENV['user']->get_by_username($username);
        if (is_array($user)
        )
            exit('-1');
        $usernamecensor = $_ENV['user']->check_usernamecensor($username);
        if (FALSE == $usernamecensor)
            exit('-2');
        exit('1');
    }

    /* 用于ajax检测用户名是否存在 */

    function onajaxemail() {
        $email = $this->post['email'];
        $user = $_ENV['user']->get_by_email($email);
        if (is_array($user)
        )
            exit('-1');
        $emailaccess = $_ENV['user']->check_emailaccess($email);
        if (FALSE == $emailaccess
        )
            exit('-2');
        exit('1');
    }

    /* 用于ajax检测验证码是否匹配 */

    function onajaxcode() {
        $code = strtolower(trim($this->post['code']));
        echo( intval($code == $_ENV['user']->get_code()) );
    }

    /* 退出系统 */

    function onlogout() {
        $navtitle = '登出系统';
        //ucenter退出成功，则不会继续执行后面的代码。
        if ($this->setting["ucenter_open"]) {
            $this->load('ucenter');
            $_ENV['ucenter']->logout();
        }
        $forward = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : SITE_URL;
        $this->setting['passport_open'] && !$this->setting['passport_type'] && $_ENV['user']->passport_client(); //通行证处理
        $_ENV['user']->logout();
        $this->setting['passport_open'] && $this->setting['passport_type'] && $_ENV['user']->passport_server($forward); //通行证处理
        $this->message('成功退出！');
    }

    /* 找回密码 */

    function ongetpass() {
        $navtitle = '找回密码';
        if (isset($this->post['submit'])) {
            $email = $this->post['email'];
            $name = $this->post['username'];
            $this->checkcode(); //检查验证码
            $touser = $_ENV['user']->get_by_name_email($name, $email);
            if ($touser) {
                $authstr = strcode($touser['username'], $this->setting['auth_key']);
                $_ENV['user']->update_authstr($touser['uid'], $authstr);
                $getpassurl = SITE_URL . '?user/resetpass/' . urlencode($authstr);
                $subject = "找回您在" . $this->setting['site_name'] . "的密码";
                $message = '<p>如果是您在<a swaped="true" target="_blank" href="' . SITE_URL . '">' . $this->setting['site_name'] . '</a>的密码丢失，请点击下面的链接找回：</p><p><a swaped="true" target="_blank" href="' . $getpassurl . '">' . $getpassurl . '</a></p><p>如果直接点击无法打开，请复制链接地址，在新的浏览器窗口里打开。</p>';
                sendmail($touser, $subject, $message);
                $this->message("找回密码的邮件已经发送到你的邮箱，请查收!", 'BACK');
            }
            $this->message("用户名或邮箱填写错误，请核实!", 'BACK');
        }
        include template('getpass');
    }

    /* 重置密码 */

    function onresetpass() {
        $navtitle = '重置密码';
        @$authstr = $this->get[2] ? $this->get[2] : $this->post['authstr'];
        if (empty($authstr)
        )
            $this->message("非法提交，缺少参数!", 'BACK');
        $authstr = urldecode($authstr);
        $username = strcode($authstr, $this->setting['auth_key'], 'DECODE');
        $theuser = $_ENV['user']->get_by_username($username);
        if (!$theuser || ($authstr != $theuser['authstr']))
            $this->message("本网址已过期，请重新使用找回密码的功能!", 'BACK');
        if (isset($this->post['submit'])) {
            $password = $this->post['password'];
            $repassword = $this->post['repassword'];
            if (strlen($password) < 6) {
                $this->message("密码长度不能少于6位!", 'BACK');
            }
            if ($password != $repassword) {
                $this->message("两次密码输入不一致!", 'BACK');
            }
            $_ENV['user']->uppass($theuser['uid'], $password);
            $_ENV['user']->update_authstr($theuser['uid'], '');
            $this->message("重置密码成功，请使用新密码登录!");
        }
        include template('resetpass');
    }

    function onask() {
        $navtitle = '我的问题';
        $status = intval($this->get[2]);
        @$page = max(1, intval($this->get[3]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $questionlist = $_ENV['question']->list_by_uid($this->user['uid'], $status, $startindex, $pagesize);
        $questiontotal = intval($this->db->fetch_total('question', 'authorid=' . $this->user['uid'] . $_ENV['question']->statustable[$status]));
        $departstr = page($questiontotal, $pagesize, $page, "user/ask/$status"); //得到分页字符串
        include template('myask');
    }

    function onspace_ask() {
        $navtitle = 'TA的问题';
        $uid = intval($this->get[2]);
        $member = $_ENV['user']->get_by_uid($uid);
        //升级进度
        $membergroup = $this->usergroup[$member['groupid']];
        @$page = max(1, intval($this->get[3]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $questionlist = $_ENV['question']->list_by_uid($uid, 'all', $startindex, $pagesize);
        $questiontotal = intval($this->db->fetch_total('question', 'authorid=' . $uid . $_ENV['question']->statustable['all']));
        $departstr = page($questiontotal, $pagesize, $page, "user/space_ask/$uid"); //得到分页字符串
        include template('space_ask');
    }

    function onanswer() {
        $navtitle = '我的回答';
        $status = intval($this->get[2]);
        @$page = max(1, intval($this->get[3]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $answerlist = $_ENV['answer']->list_by_uid($this->user['uid'], $status, $startindex, $pagesize);
        $answersize = intval($this->db->fetch_total('answer', 'authorid=' . $this->user['uid'] . $_ENV['answer']->statustable[$status]));
        $departstr = page($answersize, $pagesize, $page, "user/answer/$status"); //得到分页字符串
        include template('myanswer');
    }

    function onspace_answer() {
        $navtitle = 'TA的回答';
        $uid = intval($this->get[2]);
        $member = $_ENV['user']->get_by_uid($uid);
        //升级进度
        $membergroup = $this->usergroup[$member['groupid']];
        @$page = max(1, intval($this->get[3]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $answerlist = $_ENV['answer']->list_by_uid($uid, 'all', $startindex, $pagesize);
        $answersize = intval($this->db->fetch_total('answer', 'authorid=' . $uid . $_ENV['answer']->statustable['all']));
        $departstr = page($answersize, $pagesize, $page, "user/space_answer/$uid"); //得到分页字符串
        include template('space_answer');
    }

    function onfavorite() {
        $navtitle = '我的收藏';
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $favoritelist = $_ENV['favorite']->get_list($startindex, $pagesize);
        $questiontotal = $_ENV['favorite']->rownum_by_uid();
        $departstr = page($questiontotal, $pagesize, $page, "user/favorite"); //得到分页字符串
        include template('myfavorite');
    }

    function ondelfavorite() {
        $qid = intval($this->get[2]);
        $_ENV['favorite']->remove($qid);
        $this->message("删除成功！", 'user/favorite');
    }

    function onscore() {
        $navtitle = '我的积分';
        if ($this->setting['outextcredits']) {
            $outextcredits = unserialize($this->setting['outextcredits']);
        }
        $higherneeds = intval($this->user['creditshigher'] - $this->user['credit1']);
        $adoptpercent = $_ENV['user']->adoptpercent($this->user);
        $highergroupid = $this->user['groupid'] + 1;
        isset($this->usergroup[$highergroupid]) && $nextgroup = $this->usergroup[$highergroupid];
        $credit_detail = $_ENV['user']->credit_detail($this->user['uid']);
        $detail1 = $credit_detail[0];
        $detail2 = $credit_detail[1];
        include template('myscore');
    }

    function onexchange() {
        $navtitle = '积分兑换';
        if ($this->setting['outextcredits']) {
            $outextcredits = unserialize($this->setting['outextcredits']);
        } else {
            $this->message("系统没有开启积分兑换!", 'BACK');
        }
        $exchangeamount = $this->post['exchangeamount']; //先要兑换的积分数
        $outextindex = $this->post['outextindex']; //读取相应积分配置
        $outextcredit = $outextcredits[$outextindex];
        $creditsrc = $outextcredit['creditsrc']; //积分兑换的源积分编号
        $appiddesc = $outextcredit['appiddesc']; //积分兑换的目标应用程序 ID
        $creditdesc = $outextcredit['creditdesc']; //积分兑换的目标积分编号
        $ratio = $outextcredit['ratio']; //积分兑换比率
        $needamount = $exchangeamount / $ratio; //需要扣除的积分数

        if ($needamount <= 0) {
            $this->message("兑换的积分必需大于0 !", 'BACK');
        }
        if (1 == $creditsrc) {
            $titlecredit = '经验值';
            if ($this->user['credit1'] < $needamount) {
                $this->message("{$titlecredit}不足!", 'BACK');
            }
            $this->credit($this->user['uid'], -$needamount, 0, 0, 'exchange'); //扣除本系统积分
        } else {
            $titlecredit = '财富值';
            if ($this->user['credit2'] < $needamount) {
                $this->message("{$titlecredit}不足!", 'BACK');
            }
            $this->credit($this->user['uid'], 0, -$needamount, 0, 'exchange'); //扣除本系统积分
        }
        $this->load('ucenter');
        $_ENV['ucenter']->exchange($this->user['uid'], $creditsrc, $creditdesc, $appiddesc, $exchangeamount);
        $this->message("积分兑换成功!  你在“{$this->setting[site_name]}”的{$titlecredit}减少了{$needamount}。");
    }

    /* 个人中心修改资料 */

    function onprofile() {
        $navtitle = '个人资料';
        if (isset($this->post['submit'])) {
            $gender = $this->post['gender'];
            $bday = $this->post['birthyear'] . '-' . $this->post['birthmonth'] . '-' . $this->post['birthday'];
            $phone = $this->post['phone'];
            $qq = $this->post['qq'];
            $msn = $this->post['msn'];
            $messagenotify = isset($this->post['messagenotify']) ? 1 : 0;
            $mailnotify = isset($this->post['mailnotify']) ? 2 : 0;
            $isnotify = $messagenotify + $mailnotify;
            $signature = $this->post['signature'];
            if (($this->post['email'] != $this->user['email']) && (!preg_match("/^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/", $this->post['email']) || $this->db->fetch_total('user', " email='" . $this->post['email'] . "' "))) {
                $this->message("邮件格式不正确或已被占用!", 'user/profile');
            }
            $_ENV['user']->update($this->user['uid'], $gender, $bday, $phone, $qq, $msn, $signature, $isnotify);
            isset($this->post['email']) && $_ENV['user']->update_email($this->post['email'], $this->user['uid']);
            $this->message("个人资料更新成功", 'user/profile');
        }
        include template('profile');
    }

    function onuppass() {
        $this->load("ucenter");
        $navtitle = "修改密码";
        if (isset($this->post['submit'])) {
            if (trim($this->post['newpwd']) == '') {
                $this->message("新密码不能为空！", 'user/uppass');
            } else if (trim($this->post['newpwd']) != trim($this->post['confirmpwd'])) {
                $this->message("两次输入不一致", 'user/uppass');
            } else if (trim($this->post['oldpwd']) == trim($this->post['newpwd'])) {
                $this->message('新密码不能跟当前密码重复!', 'user/uppass');
            } else if (md5(trim($this->post['oldpwd'])) == $this->user['password']) {
                $_ENV['user']->uppass($this->user['uid'], trim($this->post['newpwd']));
                $this->message("密码修改成功", 'user/uppass');
            } else {
                $this->message("旧密码错误！", 'user/uppass');
            }
        }
        include template('uppass');
    }

    // 1提问  2回答
    function onspace() {
        $navtitle = "个人空间";
        $userid = intval($this->get[2]);
        if ($userid) {
            $member = $_ENV['user']->get_by_uid($userid);
            $membergroup = $this->usergroup[$member['groupid']];
            $adoptpercent = $_ENV['user']->adoptpercent($member);
            $answerlist = $_ENV['answer']->list_by_uid($member['uid'], 'all');
            $navtitle = $member['username'] . $navtitle;
            include template('space');
        } else {
            $this->message("抱歉，该用户个人空间不存在！", 'BACK');
        }
    }

    // 0总排行、1上周排行 、2上月排行
    //user/scorelist/1/
    function onscorelist() {
        $navtitle = "经验排行榜";
        $type = isset($this->get[2]) ? $this->get[2] : 0;
        $userlist = $_ENV['user']->list_by_credit($type, 100);
        $usercount = count($userlist);
        include template('scorelist');
    }

    function onfamouslist() {
        $this->load("famous");
        @$page = max(1, intval($this->get[2]));
        $pagesize = 10;
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $rownum = $this->db->fetch_total('user', " elect>0");
        $departstr = page($rownum, $pagesize, $page, "user/famouslist"); //得到分页字符串
        $famouslist = $_ENV['famous']->get_list(10, $startindex, $pagesize);
        $questionlist = $_ENV['famous']->get_solves();
        include template("recommenduser");
    }

    function oneditimg() {
        if (isset($_FILES["userimage"])) {
            $uid = intval($this->get[2]);
            $avatardir = "/data/avatar/";
            $extname = extname($_FILES["userimage"]["name"]);
            if (!isimage($extname))
                exit('type_error');
            $upload_tmp_file = TIPASK_ROOT . '/data/tmp/user_avatar_' . $uid . '.' . $extname;
            $uid = abs($uid);
            $uid = sprintf("%09d", $uid);
            $dir1 = $avatardir . substr($uid, 0, 3);
            $dir2 = $dir1 . '/' . substr($uid, 3, 2);
            $dir3 = $dir2 . '/' . substr($uid, 5, 2);
            (!is_dir(TIPASK_ROOT . $dir1)) && forcemkdir(TIPASK_ROOT . $dir1);
            (!is_dir(TIPASK_ROOT . $dir2)) && forcemkdir(TIPASK_ROOT . $dir2);
            (!is_dir(TIPASK_ROOT . $dir3)) && forcemkdir(TIPASK_ROOT . $dir3);
            $smallimg = $dir3 . "/small_" . $uid . '.' . $extname;
            if (move_uploaded_file($_FILES["userimage"]["tmp_name"], $upload_tmp_file)) {
                $avatar_dir = glob(TIPASK_ROOT . $dir3 . "/small_{$uid}.*");
                foreach ($avatar_dir as $imgfile) {
                    if (strtolower($extname) != extname($imgfile))
                        unlink($imgfile);
                }
                if (image_resize($upload_tmp_file, TIPASK_ROOT . $smallimg, 100, 100))
                    echo 'ok';
            }
        } else {
            if ($this->setting["ucenter_open"]) {
                $this->load('ucenter');
                $imgstr = $_ENV['ucenter']->set_avatar($this->user['uid']);
            }
            include template("editimg");
        }
    }

    function onsaveimg() {
        $x1 = $this->post['x1'];
        $y1 = $this->post['y1'];
        $x2 = $this->post['x2'];
        $y2 = $this->post['y2'];
        $w = $this->post['w'];
        $h = $this->post['h'];
        $ext = $this->post['ext'];
        $upload_tmp_file = TIPASK_ROOT . "/data/tmp/" . 'bigavatar' . $this->user['uid'] . $ext;
        $avatardir = "/data/avatar/"; //图片存放目录
        $scale = 100 / $w;
        resizeThumbnailImage($smallimg, $upload_tmp_file, $w, $h, $x1, $y1, $scale);
        copy($upload_tmp_file, TIPASK_ROOT . $dir3 . '/big_' . $uid . $ext);
        is_file($upload_tmp_file) && unlink($upload_tmp_file);
        $_ENV['user']->update_avatar($smallimg);
        $this->message('头像设置成功！', 'user/editimg');
    }

    /* 用户问题查看下详细信息 */

    function onajaxuserinfo() {
        $uid = intval($this->get[2]);
        if ($uid) {
            $userinfo = $_ENV['user']->get_by_uid($uid);
            $userinfo_group = $this->usergroup[$userinfo['groupid']];
            include template("ajaxuserinfo");
        }
    }

    //积分充值
    function onrecharge() {
        include template("recharge");
    }

}

?>