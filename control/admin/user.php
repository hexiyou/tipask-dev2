<?php

!defined('IN_TIPASK') && exit('Access Denied');

class admin_usercontrol extends base {

    function admin_usercontrol(& $get, & $post) {
        $this->base(& $get, & $post);
        $this->load('user');
        $this->load('usergroup');
        $this->load('famous');
    }

    function ondefault($msg='') {
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize;
        $userlist = $_ENV['user']->get_list($startindex, $pagesize);
        $usernum = $this->db->fetch_total('user');
        $departstr = page($usernum, $pagesize, $page, "admin_user/default");
        $msg && $message = $msg;
        $usergrouplist = $_ENV['usergroup']->get_list(1);
        include template('userlist', 'admin');
    }

    function onsearch() {
        $user = array();
        if (count($this->get) > 2) {
            $user['srchname'] = $this->get[2];
            $user['srchuid'] = $this->get[3];
            $user['srchemail'] = $this->get[4];
            $user['srchregdatestart'] = $this->get[5];
            $user['srchregdateend'] = $this->get[6];
            $user['srchregip'] = $this->get[7];
            $user['srchgroupid'] = $this->get[8];
        } else {
            $user = $this->post;
        }
        @$page = max(1, intval($this->get[8]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize;
        $condition = '1=1 ';
        if (isset($user['srchname']) && '' != trim($user['srchname'])) {
            $condition .=" AND `username` like '" . trim($user['srchname']) . "%' ";
        }
        if (isset($user['srchuid']) && '' != trim($user['srchuid'])) {
            $condition .= " AND `uid`=" . intval($user['srchuid']);
        }
        if (isset($user['srchemail']) && '' != trim($user['srchemail'])) {
            $condition .= " AND `email` = '" . trim($user['srchemail']) . "'";
        }
        if (isset($user['srchregdatestart']) && '' != trim($user['srchregdatestart'])) {
            $datestart = strtotime($user['srchregdatestart']);
            $condition .= " AND `regtime` >= $datestart ";
        }
        if (isset($user['srchregdateend']) && '' != trim($user['srchregdateend'])) {
            $dateend = strtotime($user['srchregdateend']);
            $condition .= " AND `regtime` <= " . $dateend;
        }
        if (isset($user['srchregip']) && '' != trim($user['srchregip'])) {
            $condition .= " AND `regip` = '" . $user['srchregip'] . "' ";
        }
        if (isset($user['srchgroupid']) && 0 != trim($user['srchgroupid'])) {
            $condition .= " AND `groupid` = '" . $user['srchgroupid'] . "' ";
        }
        $usergrouplist = $_ENV['usergroup']->get_list(1);
        $userlist = $_ENV['user']->list_by_search_condition($condition, $startindex, $pagesize);
        $usernum = $this->db->fetch_total('user', $condition);
        $departstr = page($usernum, $pagesize, $page, "admin_user/search/$user[srchname]/$user[srchuid]/$user[srchemail]/$user[srchregdatestart]/$user[srchregdateend]/$user[srchregip]/$user[srcgroupid]");
        include template('userlist', 'admin');
    }

    function onadd() {
        if (isset($this->post['submit'])) {
            if (!$_ENV['user']->get_by_username($this->post['addname'])) {
                $_ENV['user']->add($this->post['addname'], $this->post['addpassword'], $this->post['addemail']);
                $this->ondefault();
                exit;
            }
        }
        include template('adduser', 'admin');
    }

    function onremove() {
        if (isset($this->post['uid'])) {
            $uids = implode(",", $this->post['uid']);
            $all = isset($this->get[2]) ? 1 : 0;
            $_ENV['user']->remove($uids, $all);
            $this->ondefault('用户删除成功!');
        }
    }

    function onedit() {
        $uid = (isset($this->get[2])) ? intval($this->get[2]) : $this->post['uid'];
        if (isset($this->post['submit'])) {
            $type = 'errormsg';
            //需要跟新的数据
            $username = $this->post['username'];
            $password = $this->post['password'];
            $email = $this->post['email'];
            $groupid = $this->post['groupid'];
            $credits = intval($this->post['credits']);
            $credit1 = intval($this->post['credit1']);
            $credit2 = intval($this->post['credit2']);
            $gender = $this->post['gender'];
            $bday = $this->post['bday'];
            $phone = $this->post['phone'];
            $qq = $this->post['qq'];
            $msn = $this->post['msn'];
            $signature = $this->post['signature'];
            //表单检查
            $user = $_ENV['user']->get_by_uid($uid);
            if ($username && '' == $username) {
                $message = '用户名不能为空';
            } else if ($username != $user['username'] && $_ENV['user']->get_by_username($username)) {
                $message = '该用户名已经注册，请重新修改!';
            } else if ($password && $password != $this->post['confirmpw']) {
                $message = '两次密码不一致，请核实!';
            } else if ($email && !preg_match("/^[a-z'0-9]+([._-][a-z'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/", $email)) {
                $message = '邮箱地址不合法!';
            } else if ($user['email'] != $email && $_ENV['user']->get_by_email($email)) {
                $message = '该邮箱已有人使用，请修改!';
            } else {
                $password = ($password == '') ? $user['password'] : md5($password);
                $_ENV['user']->update_user($uid, $username, $password, $email, $groupid, $credits, $credit1, $credit2, $gender, $bday, $phone, $qq, $msn, $signature);
                $message = '用户资料编辑成功!';
                unset($type);
            }
        }
        $member = $_ENV['user']->get_by_uid($uid);
        $usergrouplist = $_ENV['usergroup']->get_list();
        $sysgrouplist = $_ENV['usergroup']->get_list(1);
        include template('edituser', 'admin');
    }

    function onelect() {
        if (isset($this->post['uid'])) {
            $uid = intval($this->post['uid'][0]);
            $_ENV['user']->update_elect($uid, intval($this->get[2]));
            $msg = intval($this->get[2]) ? '推荐成功!' : '取消推荐成功!';
            unset($this->get);
            $this->ondefault($msg);
        }
    }

    function onfamous() {
        if (isset($this->post['uid'])) {
            $uid = $this->post['uid'];
            $is_elect = intval($this->get[2]);
            $_ENV['user']->update_elect($uid, $is_elect);
            if ($is_elect) {
                $_ENV['famous']->add($uid, $this->post['reason']);
                $msg = '推荐成功!';
            } else {
                $_ENV['famous']->remove($uid);
                $msg = '取消推荐成功!';
            }
            unset($this->get);
            $this->ondefault($msg);
        }
    }

}

?>