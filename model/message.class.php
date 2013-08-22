<?php

!defined('IN_TIPASK') && exit('Access Denied');

class messagemodel {

    var $db;
    var $base;
    //0:未读，1:个人消息不包含系统消息，2：全部系统消息,3:发件箱
    var $statustable = array(
           ' AND new=1 AND status NOT IN (2,3)',
           ' AND fromuid!=0 AND status NOT IN (2,3)',
           ' AND fromuid=0 AND status NOT IN (2,3)',
           ' AND status NOT IN (1,3)'
    );

    function messagemodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    /*读取一条消息内容*/
    function get($id) {
        $message = $this->db->fetch_first('SELECT * FROM '.DB_TABLEPRE.'message WHERE `id`='.$id);
        $message['date'] = tdate($message['time']);
        return $message;
    }

    /*发送一条消息*/
    function add($msgfrom , $msgfromid , $msgtoid  , $subject  , $message) {
        $time=$this->base->time;
        $this->db->query('INSERT INTO '.DB_TABLEPRE."message  SET `from`='".$msgfrom ."' , `fromuid`=$msgfromid , `touid`=$msgtoid  , `subject`='".$subject."' , `time`=" . $time . " , `content`='".$message ."'");
        return $this->db->insert_id();
    }

    /*获取消息列表
	 fromuid为0，表示是系统消息
	 touid为0，表示是系统通知，目前暂无此内容
	 new：1表示新消息,0为已读消息。
	 status:0都没删除；1发消息者删除；2收消息者删除；3都删除
    */
    function list_by_uid($uid ,$type ='in',$status,$start=0, $limit=20) {
        $messagelist = array();
        $direct = ('in'==$type)?'touid': 'fromuid';
        $sql='SELECT * FROM '. DB_TABLEPRE ."message WHERE $direct =".$uid.$this->statustable[$status];
        $query = $this->db->query($sql." ORDER BY `time` DESC limit $start,$limit ");
        while($message=$this->db->fetch_array($query)) {
            $message['time'] = tdate($message['time']);
            ($direct=='fromuid') && list($touser)=$this->db->fetch_row($this->db->query("SELECT username FROM ".DB_TABLEPRE."user WHERE uid=".$message['touid']));
            (isset($touser) && $touser) && $message['touser']= $touser;
            $messagelist[] = $message;
        }
        return $messagelist;
    }


    /*得到新消息总数*/
    function get_num($uid) {
        $num = $this->db->result_first("SELECT count(*) FROM ".DB_TABLEPRE."message WHERE touid='$uid' AND msgtoid>0 AND new=1 ");
        return $num;
    }

    /*删除消息*/
    function remove($id) {
        $this->db->query("DELETE FROM  ".DB_TABLEPRE."message WHERE id=".$id, 'UNBUFFERED');
    }

    /*更新消息为已读状态*/
    function update_new($id,$new=0) {
        $this->db->query("UPDATE `".DB_TABLEPRE."message` set new=$new  WHERE `id` =$id");
    }


    function update_status($id,$status='in') {
        $st1 = ('in'==$status)?1:2;
        $st2 = ('in'==$status)?2:1;
        $this->db->query("UPDATE `".DB_TABLEPRE."message` SET `status`=3 WHERE `id` IN ($id) AND status IN (SELECT status FROM `".DB_TABLEPRE."message` WHERE `id` IN ($id) AND `status` = $st1)");
        $this->db->query("UPDATE `".DB_TABLEPRE."message` SET `status`=$st2 WHERE `id`IN ($id) AND status!=3");
    }
}
?>
