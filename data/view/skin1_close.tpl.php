<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template('header'); ?>
<div class="wrap1 ask-sub"> <span class="subleft margintop10 color06b"><a href="<?=SITE_URL?>"><?=$setting['site_name']?></a> 
    
<? if(is_array($navlist)) { foreach($navlist as $nav) { ?>
 
    &gt;<a href="<?=SITE_URL?>c-<?=$nav['id']?>.html"> <?=$nav['name']?></a> 
    
<? } } ?>
 </span> <span class="subright margintop10 color666"> <a href="http://ask.39.net/question/19235730.html">关节痛、尿酸高怎么办</a> <a href="http://ask.39.net/asklive-160.html">骨关节炎的症状和治疗</a> </span> </div>
<div class="wrap1 ask-tie">
  <div class="tie-left">
    <div class="tboxs tbstyle110">
      <div class="tbtop">
        <dl>
          <dt>已关闭问题</dt>
          <dd>（离问题结束还有：<font color="#ff6600"><?=$endtime?></font>）</dd>
        </dl>
        <ul>
          <li><a href="http://jbk.39.net/keshi/pifu/pifubing/4fac4.html" title="查看该疾病介绍">该疾病介绍</a></li>
          <li><a style="cursor: pointer;" class="margin10 blue2" href="<?=SITE_URL?>?question/addfavorite/<?=$question['id']?>/<?=$question['cid']?>.html">点击收藏</a></li>
        </ul>
      </div>
      <div class="tblef"> <span class="touxiang"> <span class="xinxibox"> <a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html" class="yisheng"><img src="<?=$question['author_avartar']?>" alt=""  /></a> <span class="xinxi"> <span class="point2"></span> </span> </span> <span class="point"></span> </span> <span class="username color06b"><a href="<?=SITE_URL?>u-<?=$question['authorid']?>.html"><?=$question['author']?></a></span> </div>
      <div class="tbrig"> <span class="tousu color999">发表时间：<b><?=$question['format_time']?></b>　</span>
        <h1><?=$question['title']?></h1>
        <span id="mydescription"><?=$question['description']?></span><br>
        <? if($supplylist) { ?> 
        
<? if(is_array($supplylist)) { foreach($supplylist as $supply) { ?>
 
        <span class="buchong margintop10">
        <p><span >补充问题：</span><b>(<?=$supply['format_time']?>)</b><br />
          <?=$supply['content']?></p>
        </span> 
        
<? } } ?>
 
        <? } ?> 
        <span class="anniu margintop10"> <a href="#as" style="display:none;" id="asropt" target="_self" class="wolai" title="我来回答"></a></span> 
        <!--文字链--> 
        <span class="clearer"></span> <span class="fenxiang margintop10">
        <div style="display:none;" id="dealQuestion"> <span class="boxtop1_title">处理提问</span><br>
          如果已获得满意的回答，请及时采纳，感谢回答者。若还没有满意的回答，可以尝试以下操作：<br>
          <span class="blue2"><a onclick="mysupply();" href="javascript:void(0);"> 补充问题</a></span> <span class="font_gray">补充提问细节，以得到更准确的答案；</span><br>
          <span class="blue2"><a onclick="addscore();" href="javascript:void(0);">提高悬赏</a></span> <span class="font_gray">提高悬赏，以提高问题的关注度；</span><br>
          <span class="blue2"><a onclick="document.closeForm.submit();return false;" href="javascript:void(0);">无满意答案</a></span> <span class="font_gray">无满意答案的回答，可以直接结束提问，关闭问题</span>
          <form name="closeForm" action="<?=SITE_URL?>?question/close.html" method="post">
            <input type="hidden" value="<?=$question['id']?>" name="qid">
          </form>
        </div>
        <!--问题管理开始-->
          <? if($user['groupid']==1) { ?>             <div id="dealQuestion">
              <span class="boxtop1_title">问题管理</span><br>
                <span class="blue2"><a onclick="managequest(1);return false;" href="javascript:void(0);">设为待解决</a></span>
                <span class="blue2"><a onclick="managequest(2);return false;" href="javascript:void(0);">删除</a></span>
              </div>
            <? } ?>         <!--问题管理结束-->
        </span> </div>
    </div>
    
    <div class="huida margintop10" id="huidabox" style="display:none"> <span class="hdleft" id="askboxtop"></span> <span class="hdright"> 
      </span> </div>
     <!--名医意见开始--> 
    
<? if(is_array($answerlist)) { foreach($answerlist as $index => $answer) { ?>
 
    <? $index++ ?>    <div class="tboxs tbstyle<?=$index?>" id="asr<?=$answer['authorid']?>">
      <div class="tbtop">
        <dl>
          <dt>其他医生回答</dt>
        </dl>
      </div>
      <div class="tblef"> <span class="touxiang"> <span class="xinxibox" onmouseover="this.className='xinxibox showxx'" onmouseout="this.className='xinxibox'"> <a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" class="yisheng" ><img src="<?=$answer['author_avartar']?>" alt=""  /></a> <span class="xinxi">
        <dl>
          <dd class="imgbox"><a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" class="yisheng" ><img src="<?=$answer['author_avartar']?>" alt="" /></a></dd>
          <dd class="name color06b"><a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" ><?=$answer['author']?></a></dd>
          <dd class="jj">原新疆兵团医院(退休)</dd>
          <dd class="jj">中西医</dd>
          <dd class="js color666">已回答过 <a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html">101971</a> 位问题， <a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html">6878</a> 个回答被患者设为采纳答案</dd>
          <dd class="tw"><a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" class="a1" title="向该医生提问">向该医生提问</a><a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" class="a2" title="查看该医生个人主页">查看该医生个人主页</a></dd>
        </dl>
        <span class="point2"></span> </span> </span> <span class="point"></span> </span> <span class="username color06b"> <a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" title="<?=$answer['author']?>"><?=$answer['author']?></a> </span> <span class="ziliao">中西医</span> <span class="ziliao color666">已回答:<a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html">101971</a>条</span> <span class="tiwen"><a href="<?=SITE_URL?>u-<?=$answer['authorid']?>.html" title="向该医生提问"></a></span> </div>
      <div class="tbrig"> <span class="tousu color999"> 发表时间：<b><?=$question['format_time']?></b> <a class="margin10 blue2"  href="javascript:inform('<?=$answer['author']?>',2);">投诉</a></span> <span id="mc<?=$index?>"><?=$answer['content']?></span> <span class="annius">
        <div id="apt_<?=$answer['id']?>" style="display:none;"> <A class=caina title=采纳答案 onclick="adoptanswer(<?=$answer['id']?>);" style="cursor:hand;" name="adoptanswer" target=_self>采纳答案</A> </div>
        <? if($answer['status'] ) { ?> 
        <span id="eaopt<?=$answer['authorid']?>" style="display:none;"> <A class=caina title=修改答案 onclick="editanswer(<?=$answer['id']?>,'mc<?=$index?>');" style="cursor:hand;" name="submit" target=_self>修改答案</A> </span> 
        <? } ?> 
        <span class="zhichi" id="pingjia"><a href="javascript:vote(<?=$question['id']?>);">支持(<span id="goods"><?=$question['goods']?></span>)</a> </span> </span><span class="clearer"></span> 
        
        <? if($user['groupid']==1) { ?> 
        <span class="fenxiang margintop10"> <span  class="boxtop1_title">回答管理</span><br>
        <span class="blue2"><a href="javascript:void(0);" onclick="editanswer(<?=$answer['id']?>,'mc<?=$index?>');return false;">编辑内容</a></span> <span class="blue2"><a href="javascript:delanswer(<?=$answer['id']?>,<?=$question['id']?>);">删除</a></span> 
        <? if(!$answer['status']) { ?> 
        <span class="blue2"><a href="javascript:verifyanswer(<?=$answer['id']?>,<?=$question['id']?>);">审核</a></span><? } ?> 
        <span class="clearer"></span></span> 
        <? } ?> 
      </div>
    </div>
    
<? } } ?>
 
    <!--医生回复结束-->
    
    <div class="ulbox">
      <div class="ultop color06b"><span class="zui">相关未解决推荐</span></div>
      <ul class="ul1 color06b">
        
<? if(is_array($solvelist)) { foreach($solvelist as $nosolve) { ?>
 
        <? if($question['id'] != $nosolve['id']) { ?>        <li><span class="tit"><a href="<?=SITE_URL?>q-<?=$nosolve['id']?>.html" title="<?=$nosolve['title']?>" target="_blank"><?=$nosolve['title']?></a></span><span class="otr"><?=$nosolve['answers']?>个回答</span></li>
        <? } ?> 
        
<? } } ?>
      </ul>
    </div>
    <!--Start普通回答--> 
   <div class="chongxin margintop10"> <span class="cx"><a href="/?question/add.html" title="点击可以重新提问"></a></span> <span class="bz color06b">想了解提问步骤？请查看　 　<a href="<?=SITE_URL?>?index/help.html#如何提问" target="_blank" title="点击查看提问帮助">提问帮助</a></span> </div>
    <div class="ulbox">
      <div class="ultop color06b"><span class="zui">相关问题推荐</span></div>
      <ul class="ul1 color06b">
        
<? if(is_array($solvelist)) { foreach($solvelist as $solve) { ?>
 
        <? if($question['id'] != $solve['id']) { ?>        <li><span class="tit"><a href="<?=SITE_URL?>q-<?=$solve['id']?>.html" title="<?=$solve['title']?>" target="_blank"><?=$solve['title']?></a></span><span class="otr"><?=$solve['answers']?>个回答</span></li>
        <? } ?> 
        
<? } } ?>
      </ul>
    </div>
    <span class="clearer"></span> </div>
  <div class="tie-right"> <span class="spboxs color06b"> <strong>推荐医生</strong> <span class="cons"> 
    <? $expertlist=$this->fromcache('expertlist'); ?> 
    
<? if(is_array($expertlist)) { foreach($expertlist as $expert) { ?>
 
    <span class="banzhu">
    <p class="p1"> <span class="imgbox"><a href="<?=SITE_URL?>u-<?=$expert['uid']?>.html"><img src="<?=$expert['avatar']?>" /></a></span> <span class="name"><a href="<?=SITE_URL?>u-<?=$expert['uid']?>.html" title=""><?=$expert['username']?></a></span> <span class="jj">擅长：<?=$expert['categoryname']?></span> <span class="tiwen"><a href="http://bft.zoosnet.net/LR/Chatpre.aspx?id=BFT68464573" title="向该医生提问" target="_blank"></a></span> <span class="clearer"></span> </p>
    <p class="p2">已回答问题数：<b><a href="<?=SITE_URL?>u-<?=$expert['uid']?>.html"><?=$expert['credit1']?></a></b> 条</p>
    </span> 
    
<? } } ?>
 
    </span> </span> <span class="looks2"> <strong>互动推荐</strong> <span class="imgbox imgbox1"> <a href="" title="最真实的分娩过程（高清图）"><img src="/xxx/im/5_261.jpg" alt="最真实的分娩过程（高清图）" /></a>
    <p class="color666 andthen"><a href="" title="最真实的分娩过程（高清图）">最真实的分娩过程（高清图）</a></p>
    </span> <span class="imgbox imgbox1"> <a href="" title="惊叹！女性私处检查全程(多图)"><img src="/xxx/im/200x90_4.jpg" alt="惊叹！女性私处检查全程(多图)" /></a>
    <p class="color666 andthen"><a href="" title="惊叹！女性私处检查全程(多图)">惊叹！女性私处检查全程(多图)</a></p>
    </span> </span> <span class="gpboxs"> <strong>热门话题</strong> <span class="cons">
    <ul class="ul4 uls">
      <? $recommendlist=$this->fromcache('recommendlist'); ?> 
      
<? if(is_array($recommendlist)) { foreach($recommendlist as $index => $recommend) { ?>
      <li><a href="<?=SITE_URL?>q-<?=$recommend['qid']?>.html"  title="<?=$recommend['title']?>" target="_blank"><? echo cutstr($recommend['title'],30); ?></a></li>
      
<? } } ?>
    </ul>
    </span> </span> </div>
</div><? if($setting['editor_on'] ) { ?><script src="<?=SITE_URL?>js/xheditor/xheditor-zh-cn.min.js" type="text/javascript"></script><? } ?><script type="text/javascript">
    //问题管理
    function managequest(num){
        switch(num){
             case 1:
                if(confirm('确定将该问题设为待解决？')){
                    document.location.href="<?=SITE_URL?>?question/nosolve/<?=$question['id']?>.html";
                }
                break;
            case 2:
                if(confirm('确定删除该问题?该操作不可返回！')){
                    document.location.href="<?=SITE_URL?>?question/delete/<?=$question['id']?>.html";
                }
                break;
            default:
                alert("非法操作！");
                break;
        }
    }
    //修改回答
    function editanswer(aid,mcdivid){
        var mc_content=$('#'+mcdivid).html();
        if('undefined'== typeof KE) mc_content=$.trim(mc_content.replaceAll("<br>","\n"));
        $.dialog({
            id:'editanswerDiv',
            position:'center',
            align:'left',
            fixed:1,
            width:600,
            title:'修改回答',
            fnOk:function(){editor.getSource();document.editanswerForm.submit();$.dialog.close('editanswerDiv')},
            fnCancel:function(){$.dialog.close('editanswerDiv')},
            content:'您要修改的回答如下: <div><form name="editanswerForm"  action="<?=SITE_URL?>?question/editanswer.html" method="post" ><textarea id="mc" name="content" style="width: 95%; padding-top: 1px; font-size: 14px;" rows="12" cols="100" >'+mc_content+'</textarea><input type="hidden"  value="<?=$question['id']?>" name="qid"/><input type="hidden" id="ma" value="'+aid+'" name="aid"/></form></div>'
                
        });
        editor=showeditor('mc');
    }

    //删除回答
    function delanswer(aid,qid){
        if(confirm("确定删除该回答?")){
            document.location.href='<?=SITE_URL?>?question/deleteanswer/'+aid+'/'+qid+'.html';
        }
    }
</script>
<script type="text/javascript">
    <!--用户排行切换-->
    function show_tabz1(num){
        for(i=0;i<100;i++){
            if(document.getElementById('zbfa0'+i)){
                document.getElementById('zbfa0'+i).className='boxtop2B';
                document.getElementById('zblia0'+i).style.display='none';
            }
        }
        document.getElementById('zbfa0'+num).className='boxtop2A';
        document.getElementById('zblia0'+num).style.display='block';
    }
    function show_tabz(num){
        for(i=0;i<100;i++){
            if(document.getElementById('zbf0'+i)){
                document.getElementById('zbf0'+i).className='boxtop2B';
                document.getElementById('zbli0'+i).style.display='none';
            }
        }
        document.getElementById('zbf0'+num).className='boxtop2A';
        document.getElementById('zbli0'+num).style.display='block';
    }

    function inform(name,type){
        var content = name+'的回答';
        if(type==1){
            content = name+'的提问';
        }
        $.dialog({
            id:'informDiv',
            position:'center',
            align:'left',
            fixed:1,
            width:500,
            title:'举报',
            fnOk:function(){document.informform.submit();$.dialog.close('informDiv')},
            fnCancel:function(){$.dialog.close('informDiv')},
            content:'<div style="display: block; border-bottom: 1px dotted rgb(136, 136, 136);" class="font_orange2">如果您发现不正当的内容或行为，请及时联系管理员！</div><form name="informform" action="<?=SITE_URL?>?inform/add.html" method="POST"> <div><p><strong>举报内容：</strong>'+content+'</p><p><strong>举报原因：</strong>(可多选)</p><p><input type="checkbox" name="informkind[]" value="0" />含有反动的内容</p><p><input type="checkbox" name="informkind[]" value="1" />含有人身攻击的内容</p><p><input type="checkbox" name="informkind[]" value="2" />含有广告性质的内容</p><p><input type="checkbox" name="informkind[]" value="3" />涉及违法犯罪的内容</p><p><input type="checkbox" name="informkind[]" value="4" />含有违背伦理道德的内容</p><p><input type="checkbox" name="informkind[]" value="5" />含色情、暴力、恐怖的内容</p><input type="checkbox" name="informkind[]" value="5" />含有恶意无聊灌水的内容</p></div><input type="hidden"  value="<?=$question['id']?>" name="qid"/><input type="hidden"  value="'+content+'" name="content"/><input type="hidden"  value="<?=$question['title']?>" name="title"/></from>'
        });
    }
</script>
<? include template('footers'); ?>
</body>
</html>