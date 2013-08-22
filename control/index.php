<?php

!defined('IN_TIPASK') && exit('Access Denied');

class indexcontrol extends base {

    function indexcontrol(& $get, & $post) {
        $this->base(& $get, & $post);
    }

    function ondefault() {
        $wordslist = unserialize($this->setting['hot_words']);
        $linklist = $this->cache->load('link', 'id', 'displayorder');
        /* SEO */
        $this->setting['seo_index_title'] && $seo_title = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_index_title']);
        $this->setting['seo_index_description'] && $seo_description = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_index_description']);
        $this->setting['seo_index_keywords'] && $seo_keywords = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_index_keywords']);

        include template('index');
    }

    function ontaglist() {
        $this->load("tag");
        @$page = max(1, intval($this->get[2]));
        $pagesize = 120;
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $rownum = $this->db->fetch_total('tag', " 1=1");
        $departstr = page($rownum, $pagesize, $page, "index/taglist"); //得到分页字符串
        $taglist = $_ENV['tag']->get_list($startindex, $pagesize);
        include template("taglist");
    }

    function ontagquestion() {
        $this->load("question");
        $tid = intval($this->get[2]);
        $tagname = urldecode($this->get[3]);
        @$page = max(1, intval($this->get[4]));
        $pagesize = 50;
        $startindex = ($page - 1) * $pagesize; //每页面显示$pagesize条
        $rownum = $this->db->fetch_total('question_tag', " tid=$tid");
        $departstr = page($rownum, $pagesize, $page, "index/tagquestion/$tid/$tagname"); //得到分页字符串
        $questionlist = $_ENV['question']->list_by_tid($tid, $startindex, $pagesize);
        include template("tagquestions");
    }

    function ondeletetag() {
        $this->load("tag");
        if ($this->post['tid']) {
            ($this->user['groupid'] != 1) && $this->message("您无权进行此操作！", "BACK");
            $this->post['tid'] && $_ENV['tag']->remove(implode(",", $this->post['tid']));
        }
        $this->message("标签删除成功", "index/taglist");
    }

    function onhelp() {
        $this->load('usergroup');
        $usergrouplist = $_ENV['usergroup']->get_list(2);
        include template('help');
    }

    function ondoing() {
        include template("doing");
    }

}

?>