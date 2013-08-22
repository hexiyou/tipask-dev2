<?php

!defined('IN_TIPASK') && exit('Access Denied');

class notemodel {

    var $db;
    var $base;

    function notemodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get($id) {
        return $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "note WHERE id='$id'");
    }

    function get_list($start=0, $limit=10) {
        $notelist = array();
        $query = $this->db->query("select * from " . DB_TABLEPRE . "note order by id desc limit $start,$limit");
        while ($note = $this->db->fetch_array($query)) {
            $note['time'] = tdate($note['time'], 3, 0);
            $notelist[] = $note;
        }
        return $notelist;
    }

    function add($title, $url, $content) {
        $username = $this->base->user['username'];
        $this->db->query('INSERT INTO ' . DB_TABLEPRE . "note(title,author,url,content,time) values ('$title','$username','$url','$content','{$this->base->time}')");
        return $this->db->insert_id();
    }

    function update($id, $title, $url, $content) {
        $username = $this->base->user['username'];
        $this->db->query('update  ' . DB_TABLEPRE . "note  set title='$title',author='$username',url='$url',content='$content',time='{$this->base->time}' where id=$id ");
    }

    function remove_by_id($ids) {
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "note` WHERE `id` IN ($ids)");
    }

}

?>
