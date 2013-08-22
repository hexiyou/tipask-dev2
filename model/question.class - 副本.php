<?php

!defined('IN_TIPASK') && exit('Access Denied');
require TIPASK_ROOT . '/lib/phpanalysis.class.php';

class questionmodel {

    var $db;
    var $base;
    var $sep;
    var $statustable = array(
        'all' => ' AND status<>0',
        '0' => ' AND status=0',
        '1' => ' AND status=1',
        '2' => ' AND status=2',
        '6' => ' AND status=6',
        '4' => ' AND status=1 AND price>0',
        '9' => ' AND status=9'
    );
    var $ordertable = array(
        'all' => '  AND status!=0 ORDER BY time DESC',
        '0' => ' AND status=0  ORDER BY time DESC',
        '1' => ' AND status=1  ORDER BY time DESC',
        '2' => ' AND status=2  ORDER BY time DESC',
        '6' => ' AND status=6  ORDER BY time DESC',
        '4' => ' AND status=1 AND price>0 ORDER BY price DESC,time DESC',
        '9' => ' AND status=9  ORDER BY time DESC',
    );

    function questionmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
        $this->sep = new PhpAnalysis(TIPASK_CHARSET, TIPASK_CHARSET, 1);
        $this->sep->LoadDict();
    }

    /* 获取问题信息 */

    function get($id) {
        $question = $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "question WHERE id='$id'");
        if ($question) {
            $question['format_time'] = tdate($question['time']);
            $question['ip'] = formatip($question['ip']);
            $question['author_avartar'] = get_avatar_dir($question['authorid']);
        }
        return $question;
    }

    function get_by_title($title) {
        return $this->db->fetch_first("SELECT * FROM " . DB_TABLEPRE . "question WHERE `title`='$title'");
    }

    function get_list($start = 0, $limit = 10) {
        $questionlist = array();
        $query = $this->db->query("SELECT * FROM `" . DB_TABLEPRE . "question` WHERE 1=1 limit $start , $limit");
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 获取问题标签 */

    function get_words($str, $spword=' ', $strlen=300) {
        $result = '';
        if (isset($this->base->setting['allow_fulltext']) && $this->base->setting['allow_fulltext'] || $spword == ',') {
            $this->sep->SetSource($str, $strlen, TIPASK_CHARSET, TIPASK_CHARSET);
            $this->sep->StartAnalysis(1);
            $resultstr = trim($this->sep->GetFinallyResult(' '));
            if ($resultstr) {
                $wordlist = explode(" ", $resultstr);
                $keywordlist = array();
                foreach ($wordlist as $word) {
                    (strlen($word) > 3) && $keywordlist[] = $word;
                }
                return implode($spword, $keywordlist);
            }
        }
        return $result;
    }

    /* 前台问题搜索 */

    function list_by_condition($condition, $start=0, $limit=10) {
        $questionlist = array();
        $query = $this->db->query("SELECT * FROM `" . DB_TABLEPRE . "question` WHERE $condition order by time desc limit $start , $limit");
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 后台问题数目 */

    function rownum_by_search($title='', $author='', $datestart='', $dateend='', $status='') {
        $condition = " 1=1 ";
        $title && ($condition .= " AND `title` like '$title%' ");
        $author && ($condition .= " AND `author`='$author'");
        $datestart && ($condition .= " AND `time`>= " . strtotime($datestart));
        $dateend && ($condition .=" AND `time`<= " . strtotime($dateend));
        isset($this->statustable[$status]) && $condition.=$this->statustable[$status];
        return $this->db->fetch_total('question', $condition);
    }

    /* 后台问题搜索 */

    function list_by_search($title='', $author='', $datestart='', $dateend='', $status='', $start=0, $limit=10) {
        $sql = "SELECT * FROM `" . DB_TABLEPRE . "question` WHERE 1=1 ";
        $title && ($sql .= " AND `title` like '$title%' ");
        $author && ($sql .= " AND `author`='$author'");
        $datestart && ($sql .= " AND `time` >= " . strtotime($datestart));
        $dateend && ($sql .=" AND `time` <= " . strtotime($dateend));
        isset($this->statustable[$status]) && $sql.=$this->statustable[$status];
        $sql.=" ORDER BY `time` DESC LIMIT $start,$limit";
        $questionlist = array();
        $query = $this->db->query($sql);
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    //通过标签获取同类问题
    function list_by_tag($tname, $status='1,2,6', $start=0, $limit=20) {
        $questionlist = array();
        $query = $this->db->query("SELECT * FROM `" . DB_TABLEPRE . "question` AS q," . DB_TABLEPRE . "question_tag AS t WHERE q.id=t.qid AND t.tname='$tname' AND q.status IN ($status) LIMIT $start,$limit");
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 删除问题和问题的回答 */

    function remove($qids) {
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "question` WHERE `id` IN ($qids)");
        $this->db->query("DELETE FROM `" . DB_TABLEPRE . "answer` WHERE `qid` IN ($qids)");
    }

    /* 问题列表记录数目 */

    function rownum_by_cfield_cvalue_status($cfield='cid1', $cvalue=0, $status=0) {
        $condition = " 1=1 ";
        ($cfield && $cvalue != 'all') && $condition.=" AND $cfield=$cvalue ";
        isset($this->statustable[$status]) && $condition.=$this->statustable[$status];
        return $this->db->fetch_total('question', $condition);
    }

    /* 问题列表，根据指定的分类名称，和分类id，以及status来查询 */

    function list_by_cfield_cvalue_status($cfield='cid1', $cvalue=0, $status=0, $start=0, $limit=10) {
        $questionlist = array();
        $sql = "SELECT * FROM " . DB_TABLEPRE . "question WHERE 1=1 ";
        ($cfield && $cvalue != 'all') && ($sql.=" AND $cfield=$cvalue ");
        isset($this->ordertable[$status]) && $sql.=$this->ordertable[$status];
        $sql.=" LIMIT $start,$limit";
        $query = $this->db->query($sql);
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);

            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 我的所有提问，用户中心 */

    function list_by_uid($uid, $status, $start=0, $limit=10) {
        $questionlist = array();
        $sql = 'SELECT * FROM ' . DB_TABLEPRE . 'question WHERE `authorid` = ' . $uid;
        $sql .=$this->statustable[$status] . " ORDER BY `time` DESC LIMIT $start , $limit";
        $query = $this->db->query($sql);
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            if (intval($question['endtime'])) {
                $question['format_endtime'] = tdate($question['endtime']);
            }
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 插入问题到question表 */

    function add($title, $description, $hidanswer, $price, $cid, $cid1=0, $cid2=0, $cid3=0, $status=0) {
        $overdue_days = intval($this->base->setting['overdue_days']);
        $endtime = $this->base->time + $overdue_days * 86400;
        $uid = $this->base->user['uid'];
        $username = $uid ? $this->base->user['username'] : $this->base->user['ip'];
        (!strip_tags($description, '<img>')) && $description = '';
        $search_words = '';
        /* 分词索引 */
        $search_words = $this->get_words($title . $description);
        $this->db->query("INSERT INTO " . DB_TABLEPRE . "question SET cid='$cid',cid1='$cid1',cid2='$cid2',cid3='$cid3',authorid='$uid',author='$username',title='$title',description='$description',price='$price',time='{$this->base->time}',endtime='$endtime',hidden='$hidanswer',status='$status',ip='{$this->base->ip}',search_words='$search_words'");
        $qid = $this->db->insert_id();
        $cid1 = intval($cid1);
        $cid2 = intval($cid2);
        $cid3 = intval($cid3);
        $this->db->query("UPDATE " . DB_TABLEPRE . "category SET questions=questions+1 WHERE  id IN ($cid1,$cid2,$cid3) ");
        $uid && $this->db->query("UPDATE " . DB_TABLEPRE . "user SET questions=questions+1 WHERE  uid =$uid");
        return $qid;
    }

    function update($id, $title, $description, $hidanswer, $price, $status, $cid, $cid1=0, $cid2=0, $cid3=0, $time=0) {
        $overdue_days = intval($this->base->setting['overdue_days']);
        $asktime = strtotime($time);
        $endtime = $asktime + $overdue_days * 86400;
        /* 分词模块 */
        $search_words = $this->get_words($title . $description);
        $this->db->query("UPDATE  `" . DB_TABLEPRE . "question` SET cid='$cid',cid1='$cid1',cid2='$cid2',cid3='$cid3',title='$title',description='$description',price='$price',`status`=$status , `time`= $asktime,endtime='$endtime',hidden='$hidanswer',t_words='$t_words',d_words='$d_words'  WHERE `id` = $id");
    }

    /* 更新问题状态 */

    function update_status($qid, $status=9) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set status=$status  WHERE `id` = $qid");
    }

    /* 问题补充列表 */

    function supply_list($supply) {
        $supplylist = array();
        $supply && $supplylist = tstripslashes(unserialize($supply));
        foreach ($supplylist as $index => $supply) {
            $supplylist[$index]['format_time'] = tdate($supply['time']);
        }
        return $supplylist;
    }

    /* 添加问题补充 */

    function add_supply($qid, $supply, $content, $status=0) {
        $supplylist = $this->supply_list($supply); //问题补充
        $supplylist[] = array('content' => $content, 'time' => $this->base->time);
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set supply='" . addslashes(serialize($supplylist)) . "', `status`=$status  WHERE `id` =$qid");
    }

    //添加问题感言
    function add_comment($qid, $comment) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET comment='$comment' WHERE `id`=$qid");
    }

    //添加查看次数
    function add_views($qid) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET views=views+1 WHERE `id`=$qid");
    }

    /* 更新问题顶 */

    function update_goods($qid) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set goods=goods+1  WHERE `id` =$qid");
    }

    /* 追加问题悬赏 */

    function update_score($qid, $score) {
        if ($score >= 20) {
            $overdue_days = intval($this->base->setting['overdue_days']);
            $endtime = $this->base->time + $overdue_days * 86400;
            $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set price=price+$score ,time={$this->base->time},endtime='$endtime'  WHERE `id` =$qid");
        } else {
            $threeday = 24 * 3600 * 3;
            $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set price=price+$score ,endtime=endtime+$threeday  WHERE `id` =$qid");
        }
    }

    /* 某人是否已经回答过某问题 */

    function already($qid, $uid) {
        $already = $this->db->fetch_first("SELECT qid,authorid FROM `" . DB_TABLEPRE . "answer`  WHERE `qid` =$qid and authorid=$uid");
        return is_array($already);
    }

    //问题审核
    function change_to_verify($qids) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set status=1 WHERE status=0 AND `id` in ($qids)");
    }

    //编辑问题标题
    function renametitle($qid, $title) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET `title`='$title' WHERE `id`=$qid");
        $this->db->query("UPDATE `" . DB_TABLEPRE . "answer` SET `title`='$title' WHERE `qid`=$qid");
    }

    //编辑问题内容
    function update_content($qid, $title, $content) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET `title`='$title',`description`='$content' WHERE `id`=$qid");
        $this->db->query("UPDATE `" . DB_TABLEPRE . "answer` SET `title`='$title' WHERE `qid`=$qid");
    }

    //编辑问题分类
    function update_category($qids, $cid, $cid1, $cid2, $cid3) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET `cid`=$cid,`cid1`=$cid1,`cid2`=$cid2,`cid3`=$cid3 WHERE `id`in ($qids)");
    }

    //设为待解决
    function change_to_nosolve($qids) {
        $overdue_days = intval($this->base->setting['overdue_days']);
        $endtime = $this->base->time + $overdue_days * 86400;
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` set status=1, `endtime`=$endtime WHERE (status=6 OR status=2 OR status=9) AND `id` in ($qids)");
        $this->db->query("UPDATE `" . DB_TABLEPRE . "answer` SET `adopttime`=0 WHERE `qid` in ($qids)");
    }

    //问题推荐
    function change_recommend($qids, $status1, $status2) {
        $this->db->query("UPDATE `" . DB_TABLEPRE . "question` SET `status`=$status1 WHERE `status`=$status2 AND `id` in ($qids)");
    }

    //根据标题搜索问题的结果数
    function search_title_num($title, $status='1,2,6') {
        $search_words = $this->get_words($title);
        $condition = $search_words ? " STATUS IN ($status) AND MATCH(search_words) AGAINST('$search_words') " : " STATUS IN ($status) AND title LIKE '%$title%' ";
        return $this->db->fetch_total('question', $condition);
    }

    //根据标题搜索问题
    function search_title($title, $status='1,2,6', $addbestanswer=0, $start=0, $limit=20) {
        $questionlist = array();
        $search_words = $this->get_words($title);
        $sql = $search_words ? "SELECT * FROM " . DB_TABLEPRE . "question WHERE MATCH(search_words) AGAINST('$search_words') AND status IN($status)" : "SELECT * FROM " . DB_TABLEPRE . "question WHERE STATUS IN ($status) AND title LIKE '%$title%' ";
        $sql .=" LIMIT $start,$limit";
        $query = $this->db->query($sql);
        while ($question = $this->db->fetch_array($query)) {
            $question['category_name'] = $this->base->category[$question['cid']]['name'];
            $question['format_time'] = tdate($question['time']);
            $question['url'] = url('question/view/' . $question['id'], $question['url']);
            $addbestanswer && $question['bestanswer'] = $this->db->result_first("SELECT content FROM `" . DB_TABLEPRE . "answer` WHERE qid=" . $question['id'] . " AND adopttime>0 ");
//            $question['description'] = highlight(strip_tags($question['description']), $search_words);
//            $question['title'] = highlight($question['title'], $search_words);
            $question['description'] = strip_tags($question['description']);
            $questionlist[] = $question;
        }
        return $questionlist;
    }

    /* 防采集 */

    function stopcopy() {
        $ip = $this->base->ip;
        $bengintime = $this->base->time - 60;
        $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent = strtolower($useragent);
        $allowagent = explode("\n", $this->base->setting['stopcopy_allowagent']);
        $allow = false;
        foreach ($allowagent as $agent) {
            if (false !== strpos($useragent, $agent)) {
                $allow = true;
                break;
            }
        }
        !$allow && exit('Tipask禁止不明浏览行为！');
        $stopagent = explode("\n", $this->base->setting['stopcopy_stopagent']);
        foreach ($stopagent as $agent) {
            if (false !== strpos($useragent, $agent)) {
                exit('Tipask禁止不明浏览行为！');
            }
        }
        $visits = $this->db->fetch_total('visit', " time>$bengintime AND ip='$ip' ");
        if ($visits > $this->base->setting['stopcopy_maxnum']) {
            $userip = explode(".", $ip);
            $expiration = 3600 * 24;
            $this->db->query("INSERT INTO `" . DB_TABLEPRE . "banned` (`ip1`,`ip2`,`ip3`,`ip4`,`admin`,`time`,`expiration`) VALUES ('{$userip[0]}', '{$userip[1]}', '{$userip[2]}', '{$userip[3]}', 'SYSTEM', '{$this->base->time}', '{$expiration}')");
            exit('你采集的速度太快了吧 : ) ');
        } else {
            $this->db->query("INSERT INTO " . DB_TABLEPRE . "visit (`ip`,`time`) values ('$ip','{$this->base->time}')"); //加入数据库记录visit表中
        }
    }

    function make_words() {
        $query = $this->db->query("SELECT * FROM " . DB_TABLEPRE . "question WHERE search_words='' OR search_words IS NULL LIMIT 0,1");
        $question = $this->db->fetch_array($query);
        if (!$question)
            exit("<b>所有问题全文检索设置成功!</b>");
        $search_words = $this->get_words($question['title'] . $question['description']);
        $this->db->query("UPDATE " . DB_TABLEPRE . "question SET search_words='$search_words' WHERE id=" . $question['id']);
        exit('已完成:"' . $question['title'] . '" 的全文检索索引。');
    }

}

?>
