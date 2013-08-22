<?php

!defined('IN_TIPASK') && exit('Access Denied');

class messagecontrol extends base {

    function messagecontrol(& $get, & $post) {
        $this->base(& $get, & $post);
        $this->load('user');
        $this->load("message");
    }

    /* 未读消息列表 */

    function onnew() {
        global $newnum, $personalnum, $systemnum;
        $navtitle = '收件箱';
        /* 接收参数 */
        $type = 'new';
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        /* 获取数据 */
        $messagelist = $_ENV['message']->list_by_uid($this->user['uid'], 'in', 0, $startindex, $pagesize);
        $messagenum = $this->user['newmsg'];
        $departstr = page($newnum, $pagesize, $page, "message/new"); //得到分页字符串
        include template('mymsg');
    }

    /* 私人消息列表 */

    function onpersonal() {
        global $newnum, $personalnum, $systemnum;
        $navtitle = '私人消息';
        /* 接收参数 */
        $type = 'personal';
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        /* 获取数据 */
        $messagelist = $_ENV['message']->list_by_uid($this->user['uid'], 'in', 1, $startindex, $pagesize);
        $messagenum = $this->db->fetch_total('message', 'fromuid!=0 AND touid=' . $this->user['uid'] . ' AND status NOT IN (2,3)');
        $departstr = page($messagenum, $pagesize, $page, "message/personal"); //得到分页字符串
        include template('mymsg');
    }

    /* 系统消息列表 */

    function onsystem() {
        global $newnum, $personalnum, $systemnum;
        $navtitle = '系统消息';
        /* 接收参数 */
        $type = 'system';
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        /* 获取数据 */
        $messagelist = $_ENV['message']->list_by_uid($this->user['uid'], 'in', 2, $startindex, $pagesize);
        $messagenum = $this->db->fetch_total('message', 'fromuid=0 AND touid=' . $this->user['uid'] . ' AND status NOT IN (2,3)');
        $departstr = page($messagenum, $pagesize, $page, "message/system"); //得到分页字符串
        include template('mymsg');
    }

    /* 已发消息列表 */

    function onoutbox() {
        global $newnum, $personalnum, $systemnum;
        $navtitle = '已发消息';
        /* 接收参数 */
        $type = 'outbox';
        @$page = max(1, intval($this->get[2]));
        $pagesize = $this->setting['list_default'];
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $uid = $this->user['uid'];
        /* 获取数据 */
        $rownum = $this->db->fetch_total('message', ' fromuid=' . $uid . ' AND `status` NOT IN (1,3)'); //获取总的记录数
        $messagelist = $_ENV['message']->list_by_uid($uid, 'out', 3, $startindex, $pagesize);
        $departstr = page($rownum, $pagesize, $page, "message/outbox"); //得到分页字符串
        include template('mymsg');
    }

    /* 发消息 */

    function onsend() {
        $navtitle = '发站内消息';
        if (isset($this->post['username'])) {
            (!$this->post['title']) && $this->message('主题不能为空!', "message/send");
            $touser = $_ENV['user']->get_by_username(trim($this->post['username']));
            (!$touser) && $this->message('该用户不存在！', "message/send");
            $this->setting['code_message'] && $this->checkcode(); //检查验证码
            $_ENV['message']->add($this->user['username'], $this->user['uid'], $touser['uid'], $this->post['title'], $this->post['content']);
            $this->credit($this->user['uid'], $this->setting['credit1_message'], $this->setting['credit2_message']); //增加积分
            $this->message('消息发送成功！', "message/new");
        }
        include template('sendmsg');
    }

    /* 查看消息 */

    function onajaxview() {
        global $newnum, $personalnum, $systemnum;
        $navtitle = "查看消息";
        $message = $_ENV['message']->get($this->get[2]);
        $type = ($message['touid'] == $this->user['uid']) ? 'in' : 'out';
        $touser = $_ENV['user']->get_by_uid($message['touid']);
        if ($message['new'] && !isset($this->get[3])) {
            $_ENV['message']->update_new($message['id']);
        }
        include template('viewmsg');
    }

    /* 删除消息 */

    /**
     * 对于消息状态 status = 0  消息在两者都没有删除，1代表被发消息者删除，2代表被收件人删除，3代表都删除
     */
    function onremove() {
        if (isset($this->post['mid']) || isset($this->get[2])) {
            $mids = (isset($this->post['mid'])) ? implode(",", $this->post['mid']) : $this->get[2];
            $type = (isset($this->post['type'])) ? $this->post['type'] : $this->get[3];
            $_ENV['message']->update_status($mids, $type);
            if ('out' == $type)
                $this->message('消息删除成功！', 'message/outbox');
            else 
                $this->message('消息删除成功！', 'message/new');
            
        }
        $this->onpersonal();
    }

}

?>