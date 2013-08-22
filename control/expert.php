<?php
!defined('IN_TIPASK') && exit('Access Denied');

class expertcontrol extends base {

    function expertcontrol(& $get,& $post) {
        $this->base( & $get,& $post);
        $this->load("expert");
    }

    /*Ìí¼Ó¾Ù±¨*/
    function ondefault() {
    	$expertlist = $_ENV['expert']->get_list(1,0,100);
        $questionlist = $_ENV['expert']->get_solves();
    	include template('expertlist');
    }
}
?>