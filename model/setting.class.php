<?php

!defined('IN_TIPASK') && exit('Access Denied');

class settingmodel {

    var $db;
    var $base;

    function settingmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }


    function update($setting) {
        foreach($setting as $key=>$value) {
            $this->db->query("REPLACE INTO ".DB_TABLEPRE."setting (k,v) VALUES ('$key','$value')");
        }
        $this->base->cache->remove('setting');
    }

    /*读取view文件夹，获取模板的选项*/
    function tpl_list() {
        $tpllist=array();
        $filedir=TIPASK_ROOT.'/view';
        $handle=opendir($filedir);
        while($filename=readdir($handle)) {
            if (is_dir($filedir.'/'.$filename) && '.'!=$filename{0} && 'admin'!=$filename) {
                $tpllist[]=$filename;
            }
        }
        closedir($handle);
        return $tpllist;
    }

    function update_counter() {
        /*
		$query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."question");
        while($question = $this->db->fetch_array($query)) {
 			$category=$this->base->category[$question['cid']];
 			if(1==$category['grade']){
 				$cid1=$category['id'];$cid2=0;$cid3=0;
 			}
			if(2==$category['grade']){
 				$cid1=$category['pid'];$cid2=$category['id'];$cid3=0;
 			}
			if(3==$category['grade']){
 				$cid1=$this->base->category[$category['pid']]['pid'];$cid2=$category['pid'];$cid3=$category['id'];
 			}
            $this->db->query("UPDATE ".DB_TABLEPRE."question set cid1=$cid1,cid2=$cid3,cid3=$cid3  where id=".$question['id']);
        }
        */
        $query = $this->db->query("SELECT * FROM ".DB_TABLEPRE."category");
        while($category = $this->db->fetch_array($query)) {
            $q1=$this->db->fetch_total('question','cid1='.$category['id']);
            $q2=$this->db->fetch_total('question','cid2='.$category['id']);
            $q3=$this->db->fetch_total('question','cid3='.$category['id']);
            $questions=$q1+$q2+$q3;
            $this->db->query("UPDATE ".DB_TABLEPRE."category set questions=$questions where id=".$category['id']);
        }

    }
    function get_hot_words($hot_words) {
        $lines = explode("\n",$hot_words);
        $wordslist = array();
        foreach ($lines as $line){
            $words = explode("，",$line);
            if(is_array($words)){
                $word['w']=$words[0];
                $word['qid']=intval($words[1]);
                $wordslist[] = $word;
            }
            
        }
        return serialize($wordslist);
    }

}

?>