<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template(header,admin); ?>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jqueryui.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#list").sortable({
update: function(){
var reorderid="";
var numValue=$("input[name='order[]']");
for(var i=0;i<numValue.length;i++){
reorderid+=$(numValue[i]).val()+",";
}
var hiddencid=$("input[name='hiddencid']").val();
$.post("index.php?admin_nav/reorder<?=$setting['seo_suffix']?>",{order:reorderid,hiddencid:hiddencid});
}
});
});
function remove(nid){
if(confirm('ɾ���õ�����ȷ������?')){
window.location="index.php?admin_nav/remove/"+nid+"<?=$setting['seo_suffix']?>";
}
}
</script>
<div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;">
  <div style="float:left;"><a href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main"><b>���������ҳ</b></a>&nbsp;&raquo;&nbsp;��������</div>
</div><? if(isset($message)) { $type=isset($type)?$type:'correctmsg';  ?><table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
<tr>
<td class="<?=$type?>"><?=$message?></td>
</tr>
</table><? } ?><table width="100%" cellspacing="1" cellpadding="4" align="center" class="tableborder">
<tbody><tr class="header"><td>�����б�&nbsp;&nbsp;&nbsp;<input onclick="document.location.href='index.php?admin_nav/add<?=$setting['seo_suffix']?>'" type="button" value="���ӵ���" /></td></tr>
<tr class="altbg1"><td>�������������ͨ������϶����ı䣬�������ĳһ��������ʱ����ס������������ƶ���</td></tr>
</tbody></table>
<table cellspacing="1" cellpadding="4" width="100%" align="center" style="border: 0 none !important; border-collapse: separate !important;empty-cells: show;margin-bottom: 0px;">
<tr class="header" align="center">
<td width="10%">����</td>
<td width="40%">���ӵ�ַ</td>
<td  width="20%">˵��</td>
<td  width="8%">�򿪷�ʽ</td>
<td  width="7%">״̬����</td>
<td  width="5%">�༭</td>
<td  width="5%">ɾ��</td>
</tr>
</table>
    <input type="hidden" name="hiddencid" value="<?=$pid?>" />
<ul id="list" style="cursor: hand; cursor: pointer;" >
<? if(is_array($navlist)) { foreach($navlist as $nav) { ?>
<li style="list-style:none;">
<table  id="table1" cellspacing="1" cellpadding="4" width="100%" align="center" style="border: 0 none !important; border-collapse: separate !important;empty-cells: show;margin-bottom: 0px;"> 
<tr align="center" class="smalltxt">
<td width="10%" class="altbg1"><input name="order[]" type="hidden" value="<?=$nav['id']?>"/><a href="<?=$nav['url']?>" target="_blank"><?=$nav['name']?></a></td>
<td width="40%" align="center" class="altbg2"><a href="<?=$nav['url']?>" target="_blank"><?=$nav['url']?></a></td>
<td width="20%" align="center" class="altbg2"><?=$nav['title']?></td>
<td width="8%" align="center" class="altbg1"><? if($nav['target']) { ?>�´���<? } else { ?>������<? } ?></td>
<td width="7%" align="center" class="altbg2"><a href="index.php?admin_nav/available/<?=$nav['id']?>/<?=$nav['available']?><?=$setting['seo_suffix']?>"><? if($nav['available']) { ?>�������<? } else { ?><font color="green">�������</font><? } ?></a></td>
<td width="5%" align="center" class="altbg1"><img src="css/common/admin/edit.png" onclick="document.location.href='index.php?admin_nav/edit/<?=$nav['id']?><?=$setting['seo_suffix']?>'"></td>
<td width="5%" align="center" class="altbg2"><? if($nav['type']) { ?>����ɾ��<? } else { ?><img src="css/common/admin/remove.png" onclick="remove(<?=$nav['id']?>)"><? } ?></td>
</tr>
</table>
</li>
<? } } ?>
</ul>
<br>
<? include template(footer,admin); ?>