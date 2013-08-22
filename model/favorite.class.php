<?php

!defined('IN_TIPASK') && exit('Access Denied');

class favoritemodel {

    var $db;
    var $base;

    function favoritemodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get($qid,$uid=0) {
        (!$uid) && $uid=$this->base->user['uid'];
        return $this->db->fetch_first("SELECT * FROM ".DB_TABLEPRE."favorite WHERE uid='$uid' AND qid=$qid");
    }

    function get_list($start=0,$limit=1000) {
        $uid = $this->base->user['uid'];
        $questionlist=array();
        $query=$this->db->query("SELECT * FROM `".DB_TABLEPRE."question` as q ,`".DB_TABLEPRE."favorite` as f  WHERE q.id=f.qid AND f.uid=$uid  LIMIT $start,$limit");
        while($question = $this->db->fetch_array($query)) {
            $question['category_name']=$this->base->category[$question['cid']]['name'];
            if(intval($question['endtime'])) {
                $question['format_endtime'] = tdate($question['endtime']);
            }
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/'.$question['id'],$question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    function rownum_by_uid($uid=0){
        (!$uid) && $uid=$this->base->user['uid'];
        $query=$this->db->query("SELECT count(*) as size  FROM `".DB_TABLEPRE."question` as q ,`".DB_TABLEPRE."favorite` as f  WHERE q.id=f.qid AND f.uid=$uid ");
        $favorite =  $this->db->fetch_array($query);
        return $favorite['size'];
    }

    function add($qid,$cid) {
        $uid=$this->base->user['uid'];
        $this->db->query('REPLACE INTO `'.DB_TABLEPRE."favorite`(`qid`,`cid`,`uid`) values ($qid,$cid,$uid)");
        return $this->db->insert_id();
    }

    function remove($qid,$uid=0) {
        (!$uid) && $uid=$this->base->user['uid'];
        $this->db->query("DELETE FROM `".DB_TABLEPRE."favorite` WHERE qid=$qid AND uid=$uid");
    }
}
?>
