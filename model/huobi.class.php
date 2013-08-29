<?php

!defined('IN_TIPASK') && exit('Access Denied');

class huobimodel {

    var $db;
    var $base;

    function huobimodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function get($id) {
        return $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "link WHERE id='$id'");
    }

    function get_list($start = '', $limit = 10) {
        $huobilist = array();
        $query = $this->db->query("SELECT * FROM `" . DB_TABLEPRE . "huobi` ORDER BY `id` DESC limit $start,$limit");
        while ($row = $this->db->fetch_array($query)) {
            $row['opentext'] = $row['isopen'] == 1 ? '启用' : 禁用;
            $row['addtime'] = $row['addtime'] > 0 ? date("Y-m-d H:i") : '无';
            $row['updatetime'] = $row['updatetime'] > 0 ? date("Y-m-d H:i") : '无';
            $huobilist[] = $row;
        }
        return $huobilist;
    }

    function add($name, $url, $desrc = '', $logo = '') {
        $this->db->query('REPLACE INTO `' . DB_TABLEPRE . "link`(`name`,`url`,`description`,`logo`) values ('$name','$url','$desrc','$logo')");
        return $this->db->insert_id();
    }

    function update($array, $id) {
        $sql = "UPDATE " . DB_TABLEPRE . 'huobi Set ';
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_numeric($value)) {
                    $sql.='`' . $key . '`=' . $value . ',';
                } else {
                    $sql.='`' . $key . '`=\'' . $value . '\',';
                }
            }
            $sql = trim($sql, ",") . " WHERE id=" . $id;
        }
        return $this->db->query($sql);
    }

    function remove_by_id($ids) {
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "huobi` WHERE `id` IN ($ids)");
    }

    function order_link($id, $order) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "huobi` SET 	`displayorder` = '{$order}' WHERE `id` = '{$id}'");
    }
    
    /***
     * 检查货币名称是否重复
     */
    function checkrepeat($name=''){
        $sql = "SELECT * from ".DB_TABLEPRE.'huobi WHERE name=\''.$name.'\'';
        return $this->db->fetch_first($sql);
    }

}

?>
