<? if(!defined('IN_TIPASK')) exit('Access Denied'); global $starttime,$querynum;$mtime = explode(' ', microtime());$runtime=number_format($mtime['1'] + $mtime['0'] - $starttime,6); $setting=$this->setting;$user=$this->user;$headernavlist=$this->nav;$regular=$this->regular; $this->load('recommend'); $this->load('answer'); $this->load('question'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=TIPASK_CHARSET?>"/>
<title>性疾病,妇科疾病,男性疾病,电脑网络为一体的健康网站-58百科问答</title>
<meta name="keywords" content="<?=$metakeywords?><?=$setting['seo_keywords']?>" />
<meta name="description" content="<?=$metadescription?> <?=$setting['site_name']?> <?=$setting['seo_description']?>" />
<link href="<?=SITE_URL?>skin/css/base.css" rel="stylesheet" type="text/css" />
<link href="<?=SITE_URL?>skin/css/ask_base.css" type="text/css" rel="stylesheet" />
<link href="<?=SITE_URL?>skin/css/index.css" type="text/css" rel="stylesheet" />
<script src="<?=SITE_URL?>skin/js/some.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>skin/js/jquery.min.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>skin/js/ask_function.js" type="text/javascript"></script>
<script type="text/javascript">g_site_url='<?=SITE_URL?>';g_suffix='<?=$setting['seo_suffix']?>';</script>
<link href="<?=SITE_URL?>skin/css/tc.css" type="text/css" rel="stylesheet" />
<script language="javascript">
$(function() {
var scrtime;
$("#NewAnswer").hover(function() {
clearInterval(scrtime);
}, function() {
scrtime = setInterval(function() {
var ul = $("#NewAnswer ul");
var liHeight = ul.find("li:last").height();
ul.animate({ marginTop: liHeight + 0 + "px" }, 1000, function() {
ul.find("li:last").prependTo(ul)
ul.find("li:first").hide();
ul.css({ marginTop: 0 });
ul.find("li:first").fadeIn(1000);
});
}, 5000);
}).trigger("mouseleave");
}); 
</script>
</head>
<body>
<? include template('header'); ?>
<div class="wrap1 ask-mid">
  <div class="margintop10 mid-left"> 
    <!--左问题分类--> 
    <span class="fenlei"><em>问题分类</em></span>
    <div class="cons"> 
      <? $statistics=$this->fromcache('statistics'); ?>      <ul class="ul1">
        <li>已解决问题数：<b id="TopicCount"><?=$statistics['solves']?></b></li>
        <li>待解决问题数：<b id="AnswerCount"><?=$statistics['nosolves']?></b></li>
      </ul>
        <span class="jibin"><em>58性爱健康</em><span class="spline2"></span></span>
      <ul class="ul2 color666">
       
200*200广告位
      </ul>
     
      <span class="jibin"><em>58性爱健康</em><span class="spline2"></span></span>
      <div class="menus color666"> <span class="spline"></span>
 <? $categorylist=$this->fromcache('categorylist'); if(is_array($categorylist)) { foreach($categorylist as $category1) { ?>
            
<span class="spline"></span>
 	<dl class="nomore" onmouseover="this.className='showmore'" onmouseout="this.className='nomore'">
          <dt><strong><?=$category1['name']?></strong><span class="tips">   (<?=$category1['questions']?>)    </span></dt>
          <dd>
            <h2><a href="<?=SITE_URL?>c-<?=$category1['id']?>.html"><?=$category1['name']?></a> (<?=$category1['questions']?>)</h2> 
<? if(is_array($category1['sublist'])) { foreach($category1['sublist'] as $index => $category2) { ?>
         <ul>              
  <li><a href="<?=SITE_URL?>c-<?=$category2['id']?>.html"><?=$category2['name']?></a></li> </ul>           
            
<? } } ?>
            <span class="no-ulborder"></span> <span class="close"><a href="javascript:vold(0);" title="关闭"></a></span> </dd>
        </dl>		
 
<? } } ?>
        <span class="spline"></span> </div>
      <span class="zhuanjia">在线咨询</span> <span class="spline"></span>
      <ul class="ul44 color666">
        <li></li>
      </ul>
      <!--左问题分类end--> 
    </div>
    <!--网站帮助--> 
    <span class="help margintop10"> <span class="biaoti"><a href="<?=SITE_URL?>index/help.html#" target="_blank">网站帮助</a></span>
    <ul class="ul6 uls">
      <li><a href="<?=SITE_URL?>index/help.html#如何提问"  target="_blank">如何提问</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#如何回答" target="_blank">如何回答</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#如何处理问题" target="_blank">如何处理问题</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#如何处理过期问题" target="_blank">如何处理过期问题</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#问题为何被关闭" target="_blank">问题为何被关闭</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#如何避免问答被删" target="_blank">如何避免问答被删</a></li>
      <li><a href="<?=SITE_URL?>index/help.html#<?=$setting['site_name']?>协议" target="_blank"><?=$setting['site_name']?>协议</a></li>
    </ul>
    <p>如以上信息仍无法解决您的问题,请联系我们:</p>
    <span class="lx lx1">QQ:79682153</span> <span class="lx lx2">邮箱:admin@aixue.cc</span> <span class="lx lx3">(请把#改成@)</span> </span> 
    <!--网站帮助end--> 
  </div>
  <div class="margintop10 mid-right">
    <div class="right-top">
      <div class="topleft"> 
       <!--中焦点图-->
        <div id="focus">
          <div id="hotpic">
            <div style="display:block"><span><a href="#" target="_blank">两性生活的问题？</a></span><a href="" target="_blank"><img src="<?=SITE_URL?>skin/im/206632.jpg" alt="" /></a></div>
            <div ><span><a href="#" target="_blank">手淫次数多了怎么办？</a></span><a href="" target="_blank"><img src="<?=SITE_URL?>skin/im/206630.jpg" alt="" /></a></div>
            <div ><span><a href="#" target="_blank">结婚多年不怀孕</a></span><a href="" target="_blank"><img src="<?=SITE_URL?>skin/im/206631.jpg" alt="" id="adpic"/></a></div>
            <div ><span><a href="#" target="_blank">"乳房小怎么办啊！</a></span><a href="" target="_blank"><img src="<?=SITE_URL?>skin/im/206629.jpg" alt="" /></a></div>
            <div ><span><a href="#" target="_blank">JJ小怎么办？</a></span><a href="/" target="_blank"><img src="<?=SITE_URL?>skin/im/206636.jpg" alt="" /></a></div>
          </div>
          <script src="<?=SITE_URL?>skin/js/ask_jiaodian.js" type="text/javascript"></script> 
        </div>
        <!--中焦点图end--> 
        <!--焦点图下热点--> 
        <span class="redian"> <span>热点</span>
        <ul class="uls color06b">
          <? $recommendlist=$this->fromcache('recommendlist'); ?> 
          
<? if(is_array($recommendlist)) { foreach($recommendlist as $index => $recommend) { ?>
          <li><a href="<?=$recommend['url']?>" title="<?=$recommend['title']?>"><? echo cutstr($recommend['title'],22); ?></a></li>
          
<? } } ?>
        </ul>
        </span> 
        <!--焦点图下热点end--> 
        <span class="guanzhu margintop10">
        <div class="guanzhu"> <span>关注</span>
          <ul>
            <li><a href="">快治:乳腺纤维瘤</a></li>
            <li><a href="" title="人工受孕多少钱？">人工受孕多少钱？</a></li>
            <li><a href="" title="快速安全抗过敏">快速安全抗过敏</a></li>
          </ul>
        </div>
        <li><a href="">不孕不育成高发病</a></li>
        </span> <span class="news margintop10"> 
        <!--医生最新解答--> 
        <strong>医生最新解答</strong> <span class="cons"> <span class="newanswer" id="NewAnswer"> 
        <? $famouslist=$this->fromcache('famouslist'); ?>        <ul class="color06b">
          
<? if(is_array($famouslist)) { foreach($famouslist as $famous) { ?>
          <li><span class="newqs">医生<a target="_blank" href="<?=SITE_URL?>u-<?=$famous['uid']?>.html"><?=$famous['truename']?></a>对问题＂<a target="_blank" href="<?=SITE_URL?>q-<?=$famous['qid']?>.html"><? echo cutstr($famous['title'],20); ?></a>＂进行回复</span><b><?=$famous['time']?></b></li>
          
<? } } ?>
        </ul>
        </span> <span class="as"> <a href="/?question/add.html" class="a1" title="快速提问"><em>快速提问</em><br />
        10分钟内医生解答</a> <a href="/?question/add.html" class="a2" title="医生加入"><em>医生加入</em><br />
        成为在线仁心医生</a> <a href="#" class="a3"><em>直接咨询</em><br />
        随时随地问医生</a> </span> </span> 
        <!--医生最新解答end--> 
        </span>
        <div id="tab1" class="color06b margintop10"> 
          <!--名医在线问答--> 
          <span class="tabtop"> <strong><a href="#" title="名医在线问答">名医在线问答</a></strong> <em class="up" onMouseOver="showTab(1,1);">普通咨询</em> <em onMouseOver="showTab(1,2);">处方咨询</em> <em onMouseOver="showTab(1,3);">处方分享</em>  <em onMouseOver="showTab(1,4);">病例讨论</em> <span class="dingzhi"><span>站长：79682153</span></span> </span>
       <div class="block"> 
       <? $questionlist=$_ENV['question']->list_by_condition('ask_area=1',0,3); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
       <span class="qiuzhen" onmouseover="this.className='qiuzhen qiuzhenbg'" onmouseout="this.className='qiuzhen'"> <span class="qztop"> <a href="<?=SITE_URL?>q-<?=$question['id']?>.html" title="<?=$question['title']?>" target="_blank"><?=$question['title']?></a> <b></b> </span> <span class="qzdown">
            <p><span>来自：</span>58百科问答 <a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" target="_blank"><?=$question['author']?></a>专家</p>
            <a href="#" class="wo" title="我来回答"></a> </span> </span>
            
<? } } ?>
         </div>
          <span class="clearer"></span>
          <div> <span class="wangqi"> <span class="wqbox "> <a href="#" target="_blank"><img src="<?=SITE_URL?>skin/im/221727.jpg"/></a> <strong class="andthen"><a href="#" target="_blank" >癌症术后护理最容易忽略的问题？</a></strong> <span class="wqyy andthen">58百科问答</span>
            <p><span class="wqname andthen">张华亮</span><a href="#" title="精彩解答"></a></p>
            </span><span class="wqbox wqbox2"> <a href="#" ><img src="<?=SITE_URL?>skin/im/221717.jpg" /></a> <strong class="andthen"><a href="#" target="_blank" >癌症治疗的三大金句您了解吗？</a></strong> <span class="wqyy andthen">58百科问答</span>
            <p><span class="wqname andthen">李卫强</span><a href="#" title="精彩解答"></a></p>
            </span><span class="wqbox wqbox2"> <a href="#" ><img src="<?=SITE_URL?>skin/im/221138.jpg"  /></a> <strong class="andthen"><a href="#" target="_blank">"家有患者"的便宜必须接受的几个拷问！</a></strong> <span class="wqyy andthen">58百科问答</span>
            <p><span class="wqname andthen">任建华</span><a href="#" title="精彩解答"></a></p>
            </span> </span> <span class="suoyou"><a href="#" >《名医在线问答》所有活动回顾</a></span> </div>
          <span class="clearer"></span>
          <div> <span class="huodong1"><a href="#"  target="_blank"><img src="<?=SITE_URL?>skin/im/203048.jpg" alt=""  /></a></span> <span class="huodong2"><a href="#"  target="_blank"><img src="<?=SITE_URL?>skin/im/203039.jpg" alt="" /></a></span> <span class="huodong2 huodong3"><a href="#"  target="_blank"><img src="<?=SITE_URL?>skin/im/203044.jpg" alt="" /></a></span> <span class="huodong2 huodong3"><a href="#"  target="_blank"><img src="<?=SITE_URL?>skin/im/203045.jpg" alt="" /></a></span> </div>
          <!--名医在线问答end--> 
          <span class="clearer"></span> </div>
      </div>
      <div class="topright color666"> 
        <!--会员--> 
        <? if(0!=$user['uid']) { ?>        <UL class=color06b>
          <LI>欢迎您 <a href="<?=SITE_URL?>user/default.html"class=cs target=_blank ><?=$user['username']?></A><a href="<?=SITE_URL?>user/logout.html" target=_self> [退出]</A><a href="<?=SITE_URL?>gift.html" style="color:#F00; float:right">申请预约号</A></LI>
          <LI>有<a href="<?=SITE_URL?>user/default.html" ><?=$user['newmsg']?></A>条新短信息　进入<a href="<?=SITE_URL?>user/default.html" target=_blank>个人管理</A></LI>
        </UL>
        <? } else { ?> 
        <span id="loginState"> <span class="login"> <a  target="_self" href="<?=SITE_URL?>user/login.html" class="a1" title="登录"></a> <a href="<?=SITE_URL?>user/register.html" class="a2" title="注册"></a> </span> </span> 
        <? } ?> 
        <!--会员--end>
        <!--专家坐堂--> 
        <span class="boxs margintop10"> 
        <!--网站公告--> 
        <strong>网站公告</strong> <span class="cons"> <span class="imgbox"> <a href="/"><img src="<?=SITE_URL?>skin/im/220259.jpg" alt="" /></a><span> <a href="/">关节痛、尿酸高怎么办</a> </span> </span> 
        <? $notelist=$this->fromcache('notelist'); ?>        <ul class="ul9 uls">
          
<? if(is_array($notelist)) { foreach($notelist as $note) { ?>
          <li><a <? if($note['url']) { ?>href="<?=$note['url']?>" target="_blank" <? } else { ?> href="<?=SITE_URL?>note/view/<?=$note['id']?>.html" <? } ?> ><?=$note['title']?></a></li>
          
<? } } ?>
        </ul>
        </span> </span> 
        <!--网站公告end--> 
        <span class="boxs boxsa"> <strong>热点关键词</strong> <span class="cons">
        <ul class="ul3 color0C6">
          
<? if(is_array($wordslist)) { foreach($wordslist as $hotword) { ?>
          <li> <a <? if($hotword['qid']) { ?>href="<?=SITE_URL?>q-<?=$hotword['qid']?>.html" <? } else { ?>href="<?=SITE_URL?>question/search/3/<?=$hotword['w']?>.html"<? } ?>><?=$hotword['w']?></a></li>
          
<? } } ?>
        </ul>
        </span> </span> </div>
    </div>
    <div class="right-bottom">
      <div id="tab2" class="color06b margintop10"> 
        <!--最热问题title--> 
        <span class="tabtop"> <strong><a href="#" title="最热问题">最热问题</a></strong> <em class="up" onMouseOver="showTab(2,1);">男科</em> <em onMouseOver="showTab(2,2);">两性健康</em> <em onMouseOver="showTab(2,3);">休闲娱乐</em> <em onMouseOver="showTab(2,4);">IT电脑</em> <em onMouseOver="showTab(2,5);">包皮过长</em> <em onMouseOver="showTab(2,6);">阳痿早泄</em> <em onMouseOver="showTab(2,7);">游戏知识</em></span>
        <div class="block"> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=1',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--最热科室问题titleEND--> 
          <!--右肿瘤科推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">刘瑞云</a></span> <span class="jj">妇产科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html"><?=$questiontotal?></a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">洪文涛</a></span> <span class="jj">男科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html"><?=$questiontotal?></a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=2',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--右推荐专家end--> 
          <!--外科推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">肿瘤科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html"><?=$questiontotal?></a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">李卫强</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html"><?=$questiontotal?></a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=3',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html"><?=$questiontotal?></a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">王医生</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html"><?=$questiontotal?></a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=4',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--外科推荐专家end--> 
          <!--儿科推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html">54716</a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">李卫强</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html">61056</a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=5',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--儿科推荐专家end--> 
          <!--男科推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html">54716</a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">李卫强</a></span> <span class="jj">妇科</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html">61056</a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=6',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--男科推荐专家end--> 
          <!--传染病科推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">早泄</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html">54716</a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">李卫强</a></span> <span class="jj">阳痿</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html">61056</a></b> 条</p>
          </span></span> </div>
        <span class="clearer"></span>
        <div> <span class="divleft">
          <ul>
            <? $questionlist=$_ENV['question']->list_by_condition('cid1=7',0,8); ?> 
            
<? if(is_array($questionlist)) { foreach($questionlist as $index => $question) { ?>
            <li><span class="bt">[<a href="<?=SITE_URL?>c-<?=$question['cid']?>.html"  class="color333" title="<?=$question['category_name']?>"><?=$question['category_name']?></a>] <a href="<?=$question['url']?>" title="<?=$question['title']?>"><?=$question['title']?></a></span><span class="doctor"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" ><?=$question['author']?></a></span><span class="when"><?=$question['category_name']?></span></li>
            
<? } } ?>
          </ul>
          </span> 
          <!--症状类推荐专家end--> 
          <!--生活类推荐专家--> 
          <span class="divright"> <span class="rigtop">推荐专家</span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-2.html"><img src="<?=SITE_URL?>skin/im/201106211903391798.jpg" alt="" /></a></span> <span class="name"><a href="/?u-2.html" title="">张华亮</a></span> <span class="jj">性功能</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-2.html">54716</a></b> 条</p>
          </span> <span class="tuijian">
          <p class="p1"> <span class="imgbox"><a href="/?u-3.html"><img src="<?=SITE_URL?>skin/im/204306.jpg" alt="" /></a></span> <span class="name"><a href="/?u-3.html" title="">李卫强</a></span> <span class="jj">性功能</span> <span class="tiwen"><a href="#" title="向该医生提问"></a></span> <span class="clearer"></span> </p>
          <p class="p2">已回答问题数：<b><a href="/?u-3.html">61056</a></b> 条</p>
          </span></span> 
          <!--生活类推荐专家end--> 
        </div>
        <span class="clearer"></span> </div>
    </div>
  </div>
</div>
<!--为什么选择39健康问答--><a style="DISPLAY: none" herf="http://wwww.skin">58百科问答</a>
<div class="wrap1 ask-tips">
  <dl>
    <dt>58百科问答介绍 </dt>
    <dd class="dd1"><span>职业道理</span>58百科问答每一位医生，都经过严格的资格查证，确保都具有行医执业资格。</dd>
    <dd class="ddline"></dd>
    <dd class="dd2"><span>回答队伍</span>来自全国各医院的万名医生，依据丰富的临床经验与专业知识在线服务患者。</dd>
    <dd class="ddline"></dd>
    <dd class="dd3"><span>７x24小时服务</span>所有患者的详细病情描述及健康知识，在24小时内保证100%得到医生专业回复。</dd>
    <dd class="ddline"></dd>
    <dd class="dd4"><span>专家在线问答</span>每周联合全国最优秀的三甲医院，名院名专家进行线上公益问答活动。</dd>
    <dd class="ddline"></dd>
    <dd class="dd5"><span>免费解答</span>58百科问答上的所有提问，在线医生问答均为免费，您安座家中即可连线名医。</dd>
  </dl>
</div>
<!--为什么选择39健康问答end-->
<div class="wrap1 ask-bot">
  <div id="tab4" class="color666 margintop10"> <span class="tabtop"> <em onMouseOver="showTab(4,1);" id="em1">友情链接</em> <em class="up" onMouseOver="showTab(4,2);" id="em2">精华问答汇总</em> </span> 
    <!--友情链接--> 
    <? if('index/default'==$regular) { ?>    <div>
      <ul class="uls">
        
<? if(is_array($linklist)) { foreach($linklist as $link) { ?>
        <li><a target="_blank" href="<?=$link['url']?>" title="<?=$link['description']?>"><?=$link['name']?></a> <? } ?></li>
        
<? } } ?>
      </ul>
    </div>
    <!--友情链接end--> 
    <span class="clearer"></span> 
    <!--精华问答汇总-->
    <div class="block">
      <ul class="uls">
        <li><a href="http://skin" title="58健康问答">58健康问答</a></li>
      </ul>
    </div>
    <!--精华问答汇总end--> 
    <span class="clearer"></span> </div>

</div>
<!--脚部-->
<div class="bottominfo" id="bottominfo" style="padding-top:10px;"> <a href="">网站简介</a> | <a href="">网站地图</a> | <a href="/">友情链接</a> | <a href="">媒体报道</a> | <a href="">合作伙伴</a> | <a href="">人力资源</a> | <script src="http://s23.cnzz.com/stat.php?id=3435241&web_id=3435241" type="text/javascript"></script>| <a href="">联系方式</a> | <a href="javascript:myhomepage()" name="homepage" target="_self">设为首页</a> | <a href="javascript:addfavorite()" target="_self">加入收藏</a><br />
  Copyright &copy; 2000-2011  All Rights Reserved. 58百科问答 <a href="">版权所有</a> <font color="#ffffff">1653.6848ms</font> </div>
<!--脚部end-->

</body>
</html>