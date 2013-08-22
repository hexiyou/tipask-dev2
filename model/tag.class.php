<?php

!defined('IN_TIPASK') && exit('Access Denied');

class tagmodel {

    var $db;
    var $base;

    function tagmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get_by_qid($qid) {
        $taglist = array();
        $query = $this->db->query("SELECT tname FROM `" . DB_TABLEPRE . "question_tag` WHERE qid=$qid LIMIT 0,10");
        while ($tag = $this->db->fetch_array($query)) {
            $taglist[] = $tag['tname'];
        }
        return $taglist;
    }

    function get_by_name($name) {
        return $this->db->fetch_first("SELECT * FROM `" . DB_TABLEPRE . "tag` WHERE name='$name'");
    }

    function get_list($start=0, $limit=100) {
        $taglist = array();
        $query = $this->db->query("SELECT * FROM `" . DB_TABLEPRE . "tag`  ORDER BY `questions` DESC LIMIT $start,$limit");
        while ($tag = $this->db->fetch_array($query)) {
            $taglist[] = $tag;
        }
        return $taglist;
    }

    function multi_add($namelist, $qid=0) {
        if (empty($namelist))
            return false;

        $namestr = "'" . implode("','", $namelist) . "'";
        $this->db->query("DELETE FROM " . DB_TABLEPRE . "question_tag WHERE tname NOT IN ($namestr) AND qid=$qid");
        foreach ($namelist as $name) {
            if (!$name)
                continue;
            $tag = $this->get_by_name($name);
            if ($tag) {
                $this->db->query('INSERT INTO `' . DB_TABLEPRE . "question_tag`(`tid`,`qid`,`tname`) values (" . $tag['id'] . ",$qid,'$name')");
                $this->db->query('UPDATE `' . DB_TABLEPRE . "tag` SET questions=questions+1 WHERE name='$name'");
            } else {
                $letter = substr(getpinyin(cutstr($name, 4, ''), 1), 0, 1);
                $this->db->query('INSERT INTO `' . DB_TABLEPRE . "tag`(`letter`,`name`,`questions`) values ('$letter','$name',1)");
                $this->db->query('INSERT INTO `' . DB_TABLEPRE . "question_tag`(`tid`,`qid`,`tname`) values (" . $this->db->insert_id() . ",$qid,'$name')");
            }
        }
    }

    function remove($tids) {
        $this->db->query("DELETE FROM " . DB_TABLEPRE . "tag WHERE id IN ($tids)");
        $this->db->query("DELETE FROM " . DB_TABLEPRE . "question_tag WHERE tid IN ($tids)");
    }

}

?>
