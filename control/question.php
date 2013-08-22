<?php

!defined('IN_TIPASK') && exit('Access Denied');

//0、未审核 1、待解决、2、已解决 4、悬赏的 9、 已关闭问题


class questioncontrol extends base {

    function questioncontrol(& $get, & $post) {
        $this->base(& $get, & $post);
        $this->load("question");
        $this->load("category");
        $this->load("answer");
        $this->load("expert");
        $this->load("tag");
        $this->load("userlog");
    }

    /* 提交问题 */

    function onadd() {
        $navtitle = "提出问题";
        if (isset($this->post['submit'])) {
            $title = $this->post['title'];
		$ask_area = $this->post['ask_area'];
		if (empty($ask_area)) {
				$ask_area = '';
			}
            $description = $this->post['description'];
            $cid1 = $this->post['classlevel1'];
            $cid2 = $this->post['classlevel2'];
            $cid3 = $this->post['classlevel3'];
            $cid = $this->post['cid'];
            $tags = array_filter($this->post['qtags']);
            $hidanswer = intval($this->post['hidanswer']) ? 1 : 0;
            $price = intval($this->post['givescore']);
            $askfromuid = $this->post['askfromuid'];
            //检查魅力值
            //if($this->user['credit3']<$this->user)
            $this->setting['code_ask'] && $this->checkcode(); //检查验证码
            //检查财富值
            (intval($this->user['credit3']) < $this->setting['allow_credit3']) && $this->message("你的魅力太低，禁止提问，如有问题请联系管理员!", 'BACK');
            $offerscore = $price;
            ($hidanswer) && $offerscore+=10;
            (intval($this->user['credit2']) < $offerscore) && $this->message("财富值不够!", 'BACK');
            //检查审核和内容外部URL过滤
            $status = intval(1 != (1 & $this->setting['verify_question']));
            $allow = $this->setting['allow_outer'];
            if (3 != $allow && has_outer($description)) {
                0 == $allow && $this->message("内容包含外部链接，发布失败!", 'BACK');
                1 == $allow && $status = 0;
                2 == $allow && $description = filter_outer($description);
            }
            //检查标题违禁词
            $contentarray = checkwords($title);
            1 == $contentarray[0] && $status = 0;
            2 == $contentarray[0] && $this->message("问题包含非法关键词，发布失败!", 'BACK');
            $title = $contentarray[1];

            //检查问题描述违禁词
            $descarray = checkwords($description);
            1 == $descarray[0] && $status = 0;
            2 == $descarray[0] && $this->message("问题描述包含非法关键词，发布失败!", 'BACK');
            $description = $descarray[1];

            /* 检查提问数是否超过组设置 */
            ($this->user['questionlimits'] && ($_ENV['userlog']->rownum_by_time('ask') >= $this->user['questionlimits'])) &&
                    $this->message("你已超过每小时最大提问数" . $this->user['questionlimits'] . ',请稍后再试！', 'BACK');

            $qid = $_ENV['question']->add($title, $description, $hidanswer, $price, $cid, $cid1, $cid2, $cid3, $status,$ask_area);
            $tags && $_ENV['tag']->multi_add($tags, $qid);

            //增加用户积分，扣除用户悬赏的财富
            if ($this->user['uid']) {
                $this->credit($this->user['uid'], 0, -$offerscore, 0, 'offer');
                $this->credit($this->user['uid'], $this->setting['credit1_ask'], $this->setting['credit2_ask']);
            }
            $viewurl = urlmap('question/view/' . $qid, 2);
            /* 如果是向别人提问，则需要发个消息给别人 */
            if ($askfromuid) {
                $this->load("message");
                $this->load("user");
                $touser = $_ENV['user']->get_by_uid($askfromuid);
                $_ENV['message']->add($this->user['username'], $this->user['uid'], $touser['uid'], '问题求助:' . $title, $description . '<br /> <a href="' . SITE_URL . $this->setting['seo_prefix'] . $viewurl . $this->setting['seo_suffix'] . '">点击查看问题</a>');
                sendmail($touser, '问题求助:' . $title, $description . '<br /> <a href="' . SITE_URL . $this->setting['seo_prefix'] . $viewurl . $this->setting['seo_suffix'] . '">点击查看问题</a>');
            }
            //如果ucenter开启，则postfeed
            if ($this->setting["ucenter_open"] && $this->setting["ucenter_ask"]) {
                $this->load('ucenter');
                $_ENV['ucenter']->ask_feed($qid, $title, $description);
            }
            $_ENV['userlog']->add('ask');
            if (0 == $status) {
                $this->message('问题发布成功！为了确保问答的质量，我们会对您的提问内容进行审核。请耐心等待......', 'BACK');
            } else {
                $this->message("问题发布成功!", $viewurl);
            }
        } else {
            if (0 == $this->user['uid']) {
                $this->setting["ucenter_open"] && $this->message("UCenter开启后不能匿名提问!", 'BACK');
            }
            $category_js = $_ENV['category']->get_js();
            @$word = $this->post['word'];
            $askfromuid = intval($this->get['2']);
            if ($askfromuid)
                $touser = $_ENV['user']->get_by_uid($askfromuid);
            include template('ask');
        }
    }

    /* 浏览问题 */

    function onview() {
        $this->setting['stopcopy_on'] && $_ENV['question']->stopcopy(); //是否开启了防采集功能
        $qid = $this->get[2]; //接收qid参数
        $_ENV['question']->add_views($qid); //更新问题浏览次数
        $question = $_ENV['question']->get($qid);
        empty($question) && $this->message('问题已经被删除！');
        (0 == $question['status']) && $this->message('问题正在审核中，请耐心等待！');
        /* 问题过期处理 */
        if ($question['endtime'] < $this->time && ($question['status'] == 1 || $question['status'] == 4)) {
            $question['status'] = 9;
            $_ENV['question']->update_status($qid, 9);
            $this->send($question['authorid'], $question['id'], 2);
        }
        $asktime = tdate($question['time']);
        $endtime = timeLength($question['endtime'] - $this->time);
        $solvetime = tdate($question['endtime']);
        $supplylist = $_ENV['question']->supply_list($question['supply']); //问题补充
        if (isset($this->get[3]) && $this->get[3] == 1) {
            $ordertype = 2;
            $ordertitle = '倒序查看回答';
        } else {
            $ordertype = 1;
            $ordertitle = '正序查看回答';
        }
        //回答分页        
        @$page = max(1, intval($this->get[4]));
        $pagesize = 10;
        $startindex = ($page - 1) * $pagesize;
        $rownum = $this->db->fetch_total("answer", " qid=$qid AND status>0 AND adopttime =0"); //获取总的记录数
        $answerlistarray = $_ENV['answer']->list_by_qid($qid, $this->get[3], $rownum, $startindex, $pagesize);
        $departstr = page($rownum, $pagesize, $page, "question/view/$qid/" . $this->get[3]); //得到分页字符串        
        $answerlist = $answerlistarray[0];
        $already = $answerlistarray[1]; //是否已经回答过此问题
        $solvelist = $_ENV['question']->list_by_cfield_cvalue_status('cid', $question['cid'], 2);    //获取相关已经解决问题
        $nosolvelist = $_ENV['question']->list_by_cfield_cvalue_status('cid', $question['cid'], 1); //同类待解决问题
        $navlist = $_ENV['category']->get_navigation($question['cid'], true); //获取导航
        $typearray = array('1' => 'nosolve', '2' => 'solve', '4' => 'nosolve', '6' => 'solve', '9' => 'close');
        $typedescarray = array('1' => '待解决', '2' => '已解决', '4' => '高悬赏', '6' => '已推荐', '9' => '已关闭');
        $navtitle = $question['title'];
        $dirction = $typearray[$question['status']];
        ('solve' == $dirction) && $bestanswer = $_ENV['answer']->get_best($qid);
        $catetree = $_ENV['category']->get_categrory_tree();
        $taglist = $_ENV['tag']->get_by_qid($qid);
        $support = $_ENV['answer']->get_comment_options($this->user['credit3limits'], 1);
        $against = $_ENV['answer']->get_comment_options($this->user['credit3limits'], 0);
        /* SEO */
        $curnavname = $navlist[count($navlist) - 1]['name'];
        if ($this->setting['seo_question_title']) {
            $seo_title = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_question_title']);
            $seo_title = str_replace("{wtbt}", $question['title'], $seo_title);
            $seo_title = str_replace("{wtzt}", $typedescarray[$question['status']], $seo_title);
            $seo_title = str_replace("{flmc}", $curnavname, $seo_title);
        }
        if ($this->setting['seo_question_description']) {
            $seo_description = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_question_description']);
            $seo_description = str_replace("{wtbt}", $question['title'], $seo_description);
            $seo_description = str_replace("{wtzt}", $typedescarray[$question['status']], $seo_description);
            $seo_description = str_replace("{flmc}", $curnavname, $seo_description);
        }
        if ($this->setting['seo_question_keywords']) {
            $seo_keywords = str_replace("{wzmc}", $this->setting['site_name'], $this->setting['seo_question_keywords']);
            $seo_keywords = str_replace("{wtbt}", $question['title'], $seo_keywords);
            $seo_keywords = str_replace("{wtzt}", $typedescarray[$question['status']], $seo_keywords);
            $seo_keywords = str_replace("{flmc}", $curnavname, $seo_keywords);
        }
        //++++++
		//
		//是否有销售内容
		$is_sell = false;
		$question['islookquestion'] = 0;
		$question['issell'] = 0;
		if (strpos($question['description'],"[sell")!==false && strpos($question['description'],"[/sell]")!==false) {
			$question['issell'] = 1;
			$question['sellednum'] = $_ENV['question']->countSellRenShuo($qid);
			if ($this->user['groupid']==1 || $this->user['username']==$question['author'] || $_ENV['question']->isoklookquestion($qid,$this->user['uid'])) {
				$code_num = 0;
				$question['islookquestion'] = 1;
				$question['description'] = preg_replace("/\[sell=(.+?)\](.+?)\[\/sell\]/eis","sell('\\1','\\2',1,$qid)",$question['description']);
			}else{
				$code_num = 0;
				$question['description'] = preg_replace("/\[sell=(.+?)\](.+?)\[\/sell\]/eis","sell('\\1','\\2',0,$qid)",$question['description']);
			}
		}
		//是否有隐藏内容
		
		
		if (strpos($question['description'],"[post]")!==false && strpos($question['description'],"[/post]")!==false) {
			if ($this->user['groupid']==1 || $this->user['username']==$question['author'] || $_ENV['question']->isReQuestion($qid,$this->user['username'])) {
				$question['description']=preg_replace("/\[post\](.+?)\[\/post\]/eis","post('\\1',1)",$question['description']);
			}else{
				$question['description']=preg_replace("/\[post\](.+?)\[\/post\]/eis","post('\\1',0)",$question['description']);

			}
		}

		//$question['description'] = convert($question['description']);

		//print_r($question['description']);
        //++++++
        //echo $dirction;
        if ($question['ask_area']==1){
        }elseif ($question['ask_area']==2){
        	if ($dirction=='nosolve') {
        		$dirction = "nosolve_cf_zhixun";
        	}
        }elseif ($question['ask_area']==3){
         	if ($dirction=='nosolve') {
        		$dirction = "nosolve_cf_gongxiang";
        	}
        }elseif ($question['ask_area']==4){
        	if ($dirction=='nosolve') {
        		$dirction = "nosolve_taolun";
        	}
        }
        include template($dirction);
    }

    /* 提交回答 */

    function onanswer() {

        /**
         *  统一判断是不是灌水模块，是否提交灌水答案
         * 
         */
        if((isset($this->post['mulitanswer'])&&$this->post['mulitanswer']==1)||in_array($this->post['ask_area'], array(3,4))){
            $this->onmulitanswer();
            return true;
        }else{

            print_r($this->post);
            exit;
        }



        //魅力值检查
        (intval($this->user['credit3']) < $this->setting['allow_credit3']) && $this->message("你的魅力太低，禁止回答，如有问题请联系管理员!", 'BACK');
        //只允许专家回答问题
        if (isset($this->setting['allow_expert']) && $this->setting['allow_expert'] && !$this->user['expert']) {
            $this->message('站点已设置为只允许专家回答问题，如有疑问请联系站长.');
        }
        $qid = $this->post['qid'];
        $question = $_ENV['question']->get($qid);
        if ($this->user['uid'] == $question['authorid']) {
            $this->message('提交回答失败，不能自问自答！', 'question/view/' . $qid);
        }
        $this->setting['code_ask'] && $this->checkcode(); //检查验证码
        $already = $_ENV['question']->already($qid, $this->user['uid']);
        $already && $this->message('不能重复回答同一个问题，可以修改自己的回答！', 'question/view/' . $qid);
        $title = $this->post['title'];
        $content = $this->post['content'];
        //检查审核和内容外部URL过滤
        $status = intval(2 != (2 & $this->setting['verify_question']));
        $allow = $this->setting['allow_outer'];
        if (3 != $allow && has_outer($content)) {
            0 == $allow && $this->message("内容包含外部链接，发布失败!", 'BACK');
            1 == $allow && $status = 0;
            2 == $allow && $content = filter_outer($content);
        }
        //检查违禁词
        $contentarray = checkwords($content);
        1 == $contentarray[0] && $status = 0;
        2 == $contentarray[0] && $this->message("内容包含非法关键词，发布失败!", 'BACK');
        $content = $contentarray[1];

        /* 检查提问数是否超过组设置 */
        ($this->user['answerlimits'] && ($_ENV['userlog']->rownum_by_time('answer') >= $this->user['answerlimits'])) &&
                $this->message("你已超过每小时最大回答数" . $this->user['answerlimits'] . ',请稍后再试！', 'BACK');

        $_ENV['answer']->add($qid, $title, $content, $status);
        //回答问题，添加积分
        $this->credit($this->user['uid'], $this->setting['credit1_answer'], $this->setting['credit2_answer']);
        //给提问者发送通知
        $this->send($question['authorid'], $question['id'], 0);
        //如果ucenter开启，则postfeed
        if ($this->setting["ucenter_open"] && $this->setting["ucenter_answer"]) {
            $this->load('ucenter');
            $_ENV['ucenter']->answer_feed($question, $content);
        }
        $viewurl = urlmap('question/view/' . $qid, 2);
        $_ENV['userlog']->add('answer');
        if (0 == $status) {
            $this->message('提交回答成功！为了确保问答的质量，我们会对您的回答内容进行审核。请耐心等待......', 'BACK');
        } else {
            $this->message('提交回答成功！', $viewurl);
        }
    }


     /* 提交灌水性质的回答 */

    function onmulitanswer() {
        //魅力值检查
        (intval($this->user['credit3']) < $this->setting['allow_credit3']) && $this->message("你的魅力太低，禁止回答，如有问题请联系管理员!", 'BACK');
        //只允许专家回答问题
        // if (isset($this->setting['allow_expert']) && $this->setting['allow_expert'] && !$this->user['expert']) {
        //     $this->message('站点已设置为只允许专家回答问题，如有疑问请联系站长.');
        // }
        $qid = $this->post['qid'];
        $question = $_ENV['question']->get($qid);
        // if ($this->user['uid'] == $question['authorid']) {
        //     $this->message('提交回答失败，不能自问自答！', 'question/view/' . $qid);
        // }
        $this->setting['code_ask'] && $this->checkcode(); //检查验证码
        // $already = $_ENV['question']->already($qid, $this->user['uid']);
        // $already && $this->message('不能重复回答同一个问题，可以修改自己的回答！', 'question/view/' . $qid);
        $title = $this->post['title'];
        $content = $this->post['content'];
        //检查审核和内容外部URL过滤
        $status = intval(2 != (2 & $this->setting['verify_question']));
        $allow = $this->setting['allow_outer'];
        if (3 != $allow && has_outer($content)) {
            0 == $allow && $this->message("内容包含外部链接，发布失败!", 'BACK');
            1 == $allow && $status = 0;
            2 == $allow && $content = filter_outer($content);
        }
        //检查违禁词
        $contentarray = checkwords($content);
        1 == $contentarray[0] && $status = 0;
        2 == $contentarray[0] && $this->message("内容包含非法关键词，发布失败!", 'BACK');
        $content = $contentarray[1];

        /* 检查提问数是否超过组设置 */
        ($this->user['answerlimits'] && ($_ENV['userlog']->rownum_by_time('answer') >= $this->user['answerlimits'])) &&
                $this->message("你已超过每小时最大回答数" . $this->user['answerlimits'] . ',请稍后再试！', 'BACK');

        $_ENV['answer']->add($qid, $title, $content, $status);
        //回答问题，添加积分
        $this->credit($this->user['uid'], $this->setting['credit1_answer'], $this->setting['credit2_answer']);
        //给提问者发送通知
        $this->send($question['authorid'], $question['id'], 0);
        //如果ucenter开启，则postfeed
        if ($this->setting["ucenter_open"] && $this->setting["ucenter_answer"]) {
            $this->load('ucenter');
            $_ENV['ucenter']->answer_feed($question, $content);
        }
        $viewurl = urlmap('question/view/' . $qid, 2);
        $_ENV['userlog']->add('answer');
        if (0 == $status) {
            $this->message('提交回答成功！但需要审核后才会显示', 'BACK');
        } else {
            $this->message('提交回答成功！', $viewurl);
        }
    }

    /* 采纳答案 */

    function onadopt() {

        $qid = $this->post['qid'];
        $aid = $this->post['aid'];
        $comment = $this->post['content'];
        $question = $_ENV['question']->get($qid);
        $answer = $_ENV['answer']->get($aid);
        $_ENV['answer']->adopt($qid, $answer, $comment);
        //同步名人堂
        $this->load('famous');
        //把问题的悬赏送给被采纳为答案的回答者,同时发消息通知回答者
        $this->credit($answer['authorid'], $this->setting['credit1_adopt'], intval($question['price'] + $this->setting['credit2_adopt']), 0, 'adopt');
        $this->send($answer['authorid'], $question['id'], 1);
        $viewurl = urlmap('question/view/' . $qid, 2);

        $this->message('采纳答案成功！', $viewurl);
    }

    /* 结束问题，没有满意的回答，还可直接结束提问，关闭问题。 */

    function onclose() {
        $qid = intval($this->get[2]) ? intval($this->get[2]) : $this->post['qid'];
        $_ENV['question']->update_status($qid, 9);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $this->message('关闭问题成功！', $viewurl);
    }

    /* 补充提问细节 */

    function onsupply() {
        $qid = $this->get[2] ? $this->get[2] : $this->post['qid'];
        $question = $_ENV['question']->get($qid);
        if (!$question)
            $this->message("问题不存在或已被删除!", "STOP");
        $navlist = $_ENV['category']->get_navigation($question['cid'], true);
        if (isset($this->post['submit'])) {
            $content = $this->post['content'];
            //检查审核和内容外部URL过滤
            $status = intval(1 != (1 & $this->setting['verify_question']));
            $allow = $this->setting['allow_outer'];
            if (3 != $allow && has_outer($content)) {
                0 == $allow && $this->message("内容包含外部链接，发布失败!", 'BACK');
                1 == $allow && $status = 0;
                2 == $allow && $content = filter_outer($content);
            }
            //检查违禁词
            $contentarray = checkwords($content);
            1 == $contentarray[0] && $status = 0;
            2 == $contentarray[0] && $this->message("内容包含非法关键词，发布失败!", 'BACK');
            $content = $contentarray[1];

            $question = $_ENV['question']->get($qid);
            //问题最大补充数限制
            (count(unserialize($question['supply'])) >= $this->setting['apend_question_num']) && $this->message("您已超过问题最大补充次数" . $this->setting['apend_question_num'] . ",发布失败！", 'BACK');
            $_ENV['question']->add_supply($qid, $question['supply'], $content, $status); //添加问题补充
            $viewurl = urlmap('question/view/' . $qid, 2);
            if (0 == $status) {
                $this->message('补充问题成功！为了确保问答的质量，我们会对您的提问内容进行审核。请耐心等待......', 'BACK');
            } else {
                $this->message('补充问题成功！', $viewurl);
            }
        }
        include template("addsupply");
    }

    /* 追加悬赏 */

    function onaddscore() {
        $qid = $this->get[2];
        $score = $this->post['score'];
        if ($this->user['credit2'] < $score) {
            $this->message("财富值不足!", 'BACK');
        }
        $_ENV['question']->update_score($qid, $score);
        $this->credit($this->user['uid'], 0, -$score, 0, 'offer');
        $viewurl = urlmap('question/view/' . $qid, 2);
        $this->message('追加悬赏成功！', $viewurl);
    }

    /* 修改回答 */

    function oneditanswer() {
        $navtitle = '修改回答';
        $aid = $this->get[2] ? $this->get[2] : $this->post['aid'];
        $answer = $_ENV['answer']->get($aid);
        (!$answer) && $this->message("回答不存在或已被删除！", "STOP");
        $question = $_ENV['question']->get($answer['qid']);
        $navlist = $_ENV['category']->get_navigation($question['cid'], true);
        if (isset($this->post['submit'])) {
            $content = $this->post['content'];
            $viewurl = urlmap('question/view/' . $question['id'], 2);

            //检查审核和内容外部URL过滤
            $status = intval(2 != (2 & $this->setting['verify_question']));
            $allow = $this->setting['allow_outer'];
            if (3 != $allow && has_outer($content)) {
                0 == $allow && $this->message("内容包含外部链接，发布失败!", $viewurl);
                1 == $allow && $status = 0;
                2 == $allow && $content = filter_outer($content);
            }
            //检查违禁词
            $contentarray = checkwords($content);
            1 == $contentarray[0] && $status = 0;
            2 == $contentarray[0] && $this->message("内容包含非法关键词，发布失败!", $viewurl);
            $content = $contentarray[1];

            $_ENV['answer']->update_content($aid, $content, $status);

            if (0 == $status) {
                $this->message('修改回答成功！为了确保问答的质量，我们会对您的回答内容进行审核。请耐心等待......', $viewurl);
            } else {
                $this->message('修改回答成功！', $viewurl);
            }
        }
        include template("editanswer");
    }

    /* 追问模块---追问 */

    function ontagask() {
        $this->load("message");
        $aid = $this->post['aid'];
        $qid = $this->post['qid'];
        $answer = $_ENV['answer']->get($aid);
        $question = $_ENV['question']->get($qid);
        //有新回答或者新提问发消息
        if (isset($this->post['tag_ask'])) {
            $_ENV['answer']->add_tag($aid, $this->post['tag_ask'], $answer['tag']);
            $_ENV['message']->add($question['author'], $question['authorid'], $answer['authorid'], '问题追问:' . $question['title'], $question['description'] . '<br /> <a href="' . url('question/view/' . $qid, 1) . '">点击查看问题</a>');
        } else {
            $_ENV['answer']->add_tag($aid, $this->post['tag_answer'], $answer['tag']);
            $_ENV['message']->add($answer['author'], $answer['authorid'], $question['authorid'], '问题有新回答:' . $question['title'], $question['description'] . '<br /> <a href="' . url('question/view/' . $qid, 1) . '">点击查看问题</a>');
        }
        $viewurl = urlmap('question/view/' . $qid, 2);
        isset($this->post['tag_ask']) ? $this->message('继续提问成功!', $viewurl) : $this->message('继续回答成功!', $viewurl);
    }

    /* 搜索问题 */

    function onsearch() {
        $qstatus = $status = $this->get[2];
        (3 == $status) && ($qstatus = "1,2,6");
        @$word = urldecode($this->post['word'] ? str_replace("%27", "", $this->post['word']) : $this->get[3]);
        (!trim($word)) && $this->message("搜索关键词不能为空!", 'BACK');
        $encodeword = urlencode($word);
        $navtitle = $word . '-搜索问题';
        @$page = max(1, intval($this->get[4]));
        $pagesize = $this->setting['list_default'];
        //获取记录问题检索区域
        $ask_area = intval($_POST['ask_area']);
        $startindex = ($page - 1) * $pagesize;
         $rownum = $_ENV['question']->search_title_num($word, $qstatus,$ask_area); //获取总的记录数
        $questionlist = $_ENV['question']->search_title($word, $qstatus, 0, $startindex, $pagesize,$ask_area); //问题列表数据
         $departstr = page($rownum, $pagesize, $page, "question/search/$status/$word/$ask_area"); //得到分页字符串
        $wordslist = unserialize($this->setting['hot_words']);
        include template('search');
    }

    /* 按标签搜索问题 */

    function ontag() {
        $tag = urldecode($this->get['2']);
        $encodeword = urlencode($tag);
        $navtitle = $tag . '-标签搜索';
        $qstatus = $status = intval($this->get[3]);
        (!$status) && ($qstatus = "1,2,6");
        $startindex = ($page - 1) * $pagesize;
        $rownum = $this->db->fetch_total("question_tag", " tname='$tag' ");
        $pagesize = $this->setting['list_default'];
        $questionlist = $_ENV['question']->list_by_tag($tag, $qstatus, $startindex, $pagesize);
        $departstr = page($rownum, $pagesize, $page, "question/tag/$tag/$status");
        include template('search');
    }

    /* 提问自动搜索已经解决的问题 */

    function onajaxsearch() {
        $title = urldecode($this->get[2]);
        $questionlist = $_ENV['question']->search_title($title, 2, 1, 0, 5);
        include template('ajaxsearch');
    }

    /* 顶指定问题 */

    function onajaxgood() {
        $qid = $this->get[2];
        $tgood = tcookie('good_' . $qid);
        !empty($tgood) && exit('-1');
        $_ENV['question']->update_goods($qid);
        tcookie('good_' . $qid, $qid);
        exit('1');
    }

    /* 查询图片是否需要点击放大 */

    function onajaxchkimg() {
        list($width, $height, $type, $attr) = getimagesize($this->post['imgsrc']);
        ($width > 300) && exit('1');
        exit('-1');
    }

    function ondelete() {
        $_ENV['question']->remove(intval($this->get[2]));
        $this->message('问题删除成功！');
    }

    //问题推荐
    function onrecommend() {
        $qid = intval($this->get[2]);
        $_ENV['question']->change_recommend($qid, 6, 2);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $this->message('问题推荐成功!', $viewurl);
    }

    //编辑问题
    function onedit() {
        $navtitle = '编辑问题';
        $qid = $this->get[2] ? $this->get[2] : $this->post['qid'];
        $question = $_ENV['question']->get($qid);
        if (!$question)
            $this->message("问题不存在或已被删除!", "STOP");
        $navlist = $_ENV['category']->get_navigation($question['cid'], true);
        if (isset($this->post['submit'])) {
            $viewurl = urlmap('question/view/' . $qid, 2);
            $title = trim($this->post['title']);
            (!trim($title)) && $this->message('问题标题不能为空!', $viewurl);
            $_ENV['question']->update_content($qid, $title, $this->post['content']);
            $this->message('问题编辑成功!', $viewurl);
        }
        include template("editquestion");
    }

    //编辑标签

    function onedittag() {
        $tag = trim($this->post['tag']);
        $qid = $this->post['qid'];
        $viewurl = urlmap("question/view/$qid", 2);
        $message = $tag ? "标签修改成功!" : "标签不能为空!";
        $tag && $_ENV['tag']->multi_add(explode(" ", $tag), $qid);
        $this->message($message, $viewurl);
    }

    //移动分类
    function onmovecategory() {
        if (intval($this->post['category'])) {
            $cid = intval($this->post['category']);
            $cid1 = 0;
            $cid2 = 0;
            $cid3 = 0;
            $qid = $this->post['qid'];
            $viewurl = urlmap('question/view/' . $qid, 2);
            $category = $this->cache->load('category');
            if ($category[$cid]['grade'] == 1) {
                $cid1 = $cid;
            } else if ($category[$cid]['grade'] == 2) {
                $cid2 = $cid;
                $cid1 = $category[$cid]['pid'];
            } else if ($category[$cid]['grade'] == 3) {
                $cid3 = $cid;
                $cid2 = $category[$cid]['pid'];
                $cid1 = $category[$cid2]['pid'];
            } else {
                $this->message('分类不存在，请更下缓存!', $viewurl);
            }
            $_ENV['question']->update_category($qid, $cid, $cid1, $cid2, $cid3);
            $this->message('问题分类修改成功!', $viewurl);
        }
    }

    //设为未解决
    function onnosolve() {
        $qid = intval($this->get[2]);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $_ENV['question']->change_to_nosolve($qid);
        $this->message('问题状态设置成功!', $viewurl);
    }

    function onaddfavorite() {
        $qid = intval($this->get[2]);
        $cid = intval($this->get[3]);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $message = "该问题已经收藏，不能重复收藏！";
        $this->load("favorite");
        if (!$_ENV['favorite']->get($qid)) {
            $_ENV['favorite']->add($qid, $cid);
            $message = '问题收藏成功!';
        }
        $this->message($message, $viewurl);
    }

    //前台删除问题回答
    function ondeleteanswer() {
        $qid = intval($this->get[3]);
        $aid = intval($this->get[2]);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $_ENV['answer']->remove_by_qid($aid, $qid);
        $this->message("回答删除成功!", $viewurl);
    }

    //前台审核回答
    function onverifyanswer() {
        $qid = intval($this->get[3]);
        $aid = intval($this->get[2]);
        $viewurl = urlmap('question/view/' . $qid, 2);
        $_ENV['answer']->change_to_verify($aid);
        $this->message("回答审核完成!", $viewurl);
    }

/**
 * 编辑问题标题
 * @return [type] [description]
 */
    public function onedittitle(){
       $qid=intval($this->post['qid']);
       $title=addslashes($this->post['title']);
       $query=$_ENV['question']->db->query('update '.DB_TABLEPRE.'question Set title=\''.$title.'\' Where id='.$qid);
       $viewurl = urlmap('question/view/' . $qid, 2);
       if($query){
            $this->message('成功编辑问题标题',$viewurl);
       }else{
            $this->message('编辑问题标题失败',$viewurl);

       }
    }

    function onanswercomment() {
        if (isset($this->post['credit3'])) {
            $this->load("answer_comment");
            //魅力值检查
            (intval($this->user['credit3']) < $this->setting['allow_credit3']) && $this->message("你的魅力太低，禁止回答，如有问题请联系管理员!", 'BACK');
            if ($this->post['credit3'] && trim($this->post['content'])) {
                if ($_ENV['answer_comment']->get_by_uid($this->user['uid'], $this->post['aid'])) {
                    $this->message("您已经评论过该回答了，不能重复评论！", 'BACK');
                    exit;
                }
                $_ENV['answer_comment']->add($this->post['aid'], trim($this->post['content']), intval($this->post['credit3']));
                //对被操作人进行 魅力值的处理
                $this->credit($this->post['touid'], 0, 0, intval($this->post['credit3']));
                $this->send($this->post['touid'], $this->post['qid'], 3, $this->post['aid']);
                $viewurl = urlmap('question/view/' . $this->post['qid'], 2);
                $this->message("评论该回答成功！", $viewurl);
            }
        }
    }

    function onajaxanswercomment() {
        $commentlist = $_ENV['answer']->get_comment_by_aid(intval($this->get[2]), 0, 100);
        if (!$commentlist)
            exit("还没有评分记录");
        include template("answercommentlist");
    }

}

?>