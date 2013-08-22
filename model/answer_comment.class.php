<?php

!defined('IN_TIPASK') && exit('Access Denied');

class answer_commentmodel {

    var $db;
    var $base;

    function answer_commentmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get_by_uid($uid,$aid) {
        return $this->db->fetch_first("SELECT * FROM `" . DB_TABLEPRE . "answer_comment` WHERE authorid=$uid AND aid=$aid");
    }

    function add($aid, $conmment, $credit) {
        $this->db->query('INSERT INTO `' . DB_TABLEPRE . "answer_comment`(`aid`,`authorid`,`author`,`content`,`credit`,`time`) values ($aid," . $this->base->user['uid'] . ",'" . $this->base->user['username'] . "','$conmment',$credit," . $this->base->time . ")");
        if ($credit > 0)
            $this->db->query('UPDATE `' . DB_TABLEPRE . "answer` SET support=support+1 WHERE id=$aid");
        else
            $this->db->query('UPDATE `' . DB_TABLEPRE . "answer` SET against=against+1 WHERE id=$aid");
    }

}

?>
