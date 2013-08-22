<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template(header,admin); ?>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/dialog.js" type="text/javascript"></script>
<script src="js/admin.js" type="text/javascript"></script>
<script src="js/calendar.js" type="text/javascript"></script>
<div id="append">
</div>
<div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;">
    <div style="float:left;"><a href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main"><b>控制面板首页</b></a>&nbsp;&raquo;&nbsp;问题管理</div>
</div><? if(isset($message)) { $type=isset($type)?$type:'correctmsg';  ?><table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
    <tr>
        <td class="<?=$type?>"><?=$message?></td>
    </tr>
</table><? } ?><form action="index.php?admin_question/searchquestion<?=$setting['seo_suffix']?>" method="post">
    <table width="100%" cellspacing="0" cellpadding="4" align="center" class="tableborder">
        <tbody>
            <tr class="header" ><td colspan="4">问题列表</td></tr>
            <tr class="altbg1"><td colspan="4">可以通过如下搜索条件，检索问题</td></tr>
            <tr>
                <td width="200"  class="altbg2">标题:<input class="txt" name="srchtitle" <? if(isset($srchtitle)) { ?>value="<?=$srchtitle?>" <? } ?>></td>
                <td  width="200" class="altbg2">提问者:<input class="txt" name="srchauthor" <? if(isset($srchauthor)) { ?>value="<?=$srchauthor?>" <? } ?>></td>
                <td  width="200" class="altbg2">状态:					
                    <select name="srchstatus">
                        <option <? if((isset($srchstatus) && '-1'==$srchstatus) ) { ?> selected <? } ?> value="-1">--不限--</option>
                        <option value="1" <? if((isset($srchstatus) && 1==$srchstatus) ) { ?> selected <? } ?>>待解决</option>
                        <option value="2" <? if((isset($srchstatus) && 2==$srchstatus) ) { ?> selected <? } ?>>已解决</option>
                        <option value="6" <? if((isset($srchstatus) && 6==$srchstatus) ) { ?> selected <? } ?>>推荐问题</option>
                        <option value="9" <? if((isset($srchstatus) && 9==$srchstatus) ) { ?> selected <? } ?>>已关闭问题</option>
                    </select>
                </td>
                <td  rowspan="2" class="altbg2"><input class="btn" type="submit" value="提 交"></td>
            </tr>
            <tr>
                <td  colspan="3" rowspan="2" class="altbg2">时间:
                    <input class="txt" onclick="showcalendar();" name="srchdatestart" <? if(isset($srchdatestart)) { ?>value="<?=$srchdatestart?>" <? } ?>>&nbsp; 到&nbsp; 
                           <input class="txt" onclick="showcalendar();" name="srchdateend" <? if(isset($srchdateend)) { ?>value="<?=$srchdateend?>" <? } ?>>
                </td>
            </tr>
        </tbody>
    </table>
</form>
[共 <font color="green"><?=$rownum?></font> 个问题]
<form name="queslist" method="POST">
    <table width="100%" border="0" cellpadding="4" cellspacing="1" class="tableborder">
        <tr class="header">
            <td width="5%"><input class="checkbox" id="chkall" onclick="checkall('qid[]')" type="checkbox" name="chkall"><label for="chkall">全选</label></td>
            <td  width="30%">标题</td>
            <td  width="15%">提问者</td>
            <td  width="5%">悬赏</td>
            <td  width="10%">回答/查看</td>
            <td  width="5%">状态</td>
            <td  width="10%">IP</td>
            <td  width="12%">提问时间</td>
            <td  width="18%">已推荐</td>
        </tr>
        <? if(isset($questionlist)) { ?> 
<? if(is_array($questionlist)) { foreach($questionlist as $question) { ?>
        <tr>
            <td class="altbg2">
                <input class="checkbox" type="checkbox" value="<?=$question['id']?>" name="qid[]" >
            </td>
            <td class="altbg2" id="title_<?=$question['id']?>"><a href="index.php?question/view/<?=$question['id']?><?=$setting['seo_suffix']?>" target="_blank"><? echo cutstr($question['title'],46,''); ?></a></td>
            <td class="altbg2"><a href="index.php?user/space/<?=$question['authorid']?><?=$setting['seo_suffix']?>" target="_blank"><?=$question['author']?></a></td>
            <td class="altbg2"><font color="#FC6603"><?=$question['price']?></font></td>
            <td class="altbg2"><?=$question['answers']?> / <?=$question['views']?></td>
            <td class="altbg2"><img src="<?=SITE_URL?>css/common/icn_<?=$question['status']?>.gif"></td>
            <td class="altbg2"><?=$question['ip']?></td>
            <td class="altbg2"><?=$question['format_time']?></td>
            <td class="altbg2"><? if($question['status']==6) { ?><img src="<?=SITE_URL?>css/common/icn_6.gif"><? } else { ?>否<? } ?></td>
        </tr>
        <? $content=htmlspecialchars($question['description']); ?>        <input type="hidden" id="cont_<?=$question['id']?>" value="<?=$content?>" >
        
<? } } ?>
        <? } ?>        <? if($departstr) { ?>        <tr class="smalltxt">
            <td class="altbg2" colspan="9" align="right"><div class="scott"><?=$departstr?></div></td>
        </tr>
        <? } ?>        <tr class="altbg1">
            <td colspan="9">
                <input name="ctrlcase" class="btn" type="button" onClick="buttoncontrol(2);" value="推荐">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="button" onClick="buttoncontrol(7);" value="添加到专题">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="submit" onClick="buttoncontrol(3);" value="取消推荐">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="button" onClick="movecate();" value="移动分类">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="submit" onClick="buttoncontrol(4);" value="关闭问题">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="submit" onClick="buttoncontrol(5);" value="设为待解决">&nbsp;&nbsp;&nbsp;
                <input name="ctrlcase" class="btn" type="submit" onClick="buttoncontrol(6);" value="删除">
            </td>
        </tr>
    </table>
</form>
<? include template(footer,admin); ?>
<script type="text/javascript">
    function buttoncontrol(num){
        if($("input[name='qid[]']:checked").length==0){
            alert('你没有选择任何要操作的问题！');
            return false;
        }else{
            switch(num){
                case 2:
                    if(confirm('确定推荐？此项操作只对已解决问题有效！')==false){
                        return false;
                    }else{
                        document.queslist.action="index.php?admin_question/recommend<?=$setting['seo_suffix']?>";
                        document.queslist.submit();
                    }
                    break;
                case 3:
                    document.queslist.action="index.php?admin_question/inrecommend<?=$setting['seo_suffix']?>";
                    document.queslist.submit();
                    break;
                case 4:
                    if(confirm('确定关闭问题？')==false){
                        return false;
                    }else{
                        document.queslist.action="index.php?admin_question/close<?=$setting['seo_suffix']?>";
                        document.queslist.submit();
                    }

                    break;
                case 5:
                    if(confirm('确定设为待解决？此项操作只对已解决和已关闭问题有效！')==false){
                        return false;
                    }else{
                        document.queslist.action="index.php?admin_question/nosolve<?=$setting['seo_suffix']?>";
                        document.queslist.submit();
                    }

                    break;
                case 6:
                    if(confirm('确定删除问题？该操作不可返回！')==false){
                        return false;
                    }else{
                        document.queslist.action="index.php?admin_question/delete<?=$setting['seo_suffix']?>";
                        document.queslist.submit();
                    }
                    break;
                case 7:
                    if($("input[name='qid[]']:checked").length == 0){
                        alert('你没有选择任何问题');
                        return false;
                    }
                    var qids = document.getElementsByName('qid[]');
                    var num='',tag='';
                    for(var i=0;i<qids.length;i++){	
                        if(qids[i].checked==true){
                            num+=tag+qids[i].value;
                            tag=",";
                        }
                    }
                    $.ajax({
                        type: "POST",
                        url: "<?=SITE_URL?>admin_topic/ajaxgetselect.html",
                        success: function(selectstr){
                            $.dialog({
                                id:'selecttopic',
                                position:'center',
                                align:'left',
                                fixed:1,
                                width:300,
                                height:100,
                                title:'添加到专题',
                                fnOk:function(){document.addtotopicForm.submit();$.dialog.close('selecttopic')},
                                fnCancel:function(){$.dialog.close('selecttopic')},
                                content:'<div class="mainbox"><form name="addtotopicForm"  action="index.php?admin_question/addtotopic<?=$setting['seo_suffix']?>" method="post" ><input type="hidden" name="qids" value="'+num+'" />'+selectstr+'</form></div>'
                            });
                        }
                    });
                    break;
                default:
                    alert("非法操作！");
                    break;	
            }
        }
    }
    function renametitle(){
        if ($("input[name='qid[]']:checked").length > 1){
            alert('只能同时对一个问题进行操作！');
            return false;
        }else if($("input[name='qid[]']:checked").length == 0){
            alert('你没有选择任何问题');
            return false;
        }else{
            var qid = $("input[name='qid[]']:checked").val();
            var title = $('#title_'+qid).text();
            $.dialog({
                id:'renametitle',
                position:'center',
                align:'left',
                fixed:1,
                width:300,
                height:100,
                title:'修改问题标题',
                fnOk:function(){document.renameForm.submit();$.dialog.close('renametitle')},
                fnCancel:function(){$.dialog.close('renametitle')},
                content:'<div class="mainbox"><form name="renameForm"  action="index.php?admin_question/renametitle<?=$setting['seo_suffix']?>" method="post" ><input type="hidden" value="'+qid+'" name="qid"/>问题标题：<br /><input type="text" name="title" value="'+title+'" class="txt" size="45" /></form></div>'
            });
        }
    }
    function movecate(){
        if($("input[name='qid[]']:checked").length == 0){
            alert('你没有选择任何问题');
            return false;
        }else{
            var qids = document.getElementsByName('qid[]');
            var num='',tag='';
            for(var i=0;i<qids.length;i++){	
                if(qids[i].checked==true){
                    num+=tag+qids[i].value;
                    tag=",";
                }
            }
            $.dialog({
                id:'editcatediv',
                position:'center',
                align:'left',
                fixed:1,
                width:300,
                height:100,
                title:'移动分类',
                fnOk:function(){document.askform.submit();$.dialog.close('editcatediv')},
                fnCancel:function(){$.dialog.close('editcatediv')},
                content:'<div class="mainbox">请选择要移动到的分类：<form name="askform" action="index.php?admin_question/movecategory<?=$setting['seo_suffix']?>" method="post" ><input type="hidden" name="qids" value="'+num+'" /><select name="category" size=1 style="width:240px" ><?=$catetree?></select></form></div>'
            });
        }
    }
</script>


