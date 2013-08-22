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
                $.post("index.php?admin_link/reorder<?=$setting['seo_suffix']?>",{order:reorderid,hiddencid:hiddencid});
            }
        });
    });

    function remove(lid){
        if(confirm('删除该链接，确定继续?')){
            window.location="index.php?admin_link/remove/"+lid+"<?=$setting['seo_suffix']?>";;
        }
    }
</script>
<div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;">
    <div style="float:left;"><a href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main"><b>控制面板首页</b></a>&nbsp;&raquo;&nbsp;置顶消费规则设置</div>
</div><? if(isset($message)) { $type=isset($type)?$type:'correctmsg';  ?><table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
    <tr>
        <td class="<?=$type?>"><?=$message?></td>
    </tr>
</table><? } ?><table width="100%" cellspacing="1" cellpadding="4" align="center" class="tableborder">
    <tbody><tr class="header"><td>置顶消费规则列表</td></tr>
        <tr class="altbg1"><td>.</td></tr>
    </tbody>
</table>
<form action="index.php?admin_setting/topset<?=$setting['seo_suffix']?>" method="post">
    <table cellspacing="1" cellpadding="4" width="100%" align="center" style="border: 0 none !important; border-collapse: separate !important;empty-cells: show;margin-bottom: 0px;">
        <tr class="header" align="center">
            <td width="30%">参数名称</td>
            <td  width="35%">金币值</td>
        </tr>
        <tr class="smalltxt" align="center">
            <td  width="30%"  class="altbg1">当前分类置顶每日消费金币:</td>
            <td  width="35%" class="altbg2"><input value="<?=$setting['top_cat']?>" type="text" name="top_cat"></td>
        <tr class="smalltxt" align="center">
            <td  width="30%" class="altbg1">区域置顶每日消费金币:</td>
            <td  width="35%" class="altbg2"><input value="<?=$setting['top_area']?>" name="top_area" type="text" ></td>
        </tr>
        <tr class="smalltxt" align="center">
            <td  width="30%" class="altbg1">全局置顶每日消费金币:</td>
            <td  width="35%" class="altbg2"><input value="<?=$setting['top_all']?>" name="top_all" type="text" ></td>
        </tr>
        
    </table>
    <br />
    <center><input type="submit" class="button" name="submit" value="提 交"></center><br>
</form>
<br />
<? include template(footer,admin); ?>
