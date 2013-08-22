<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template(header,admin); ?>
<div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;">
    <div style="float:left;"><a href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main"><b>控制面板首页</b></a>&nbsp;&raquo;&nbsp;系统用户组</div>
</div><? if(isset($message)) { $type=isset($type)?$type:'correctmsg';  ?><table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
    <tr>
        <td class="<?=$type?>"><?=$message?></td>
    </tr>
</table><? } ?><table width="100%" cellspacing="1" cellpadding="4" align="center" class="tableborder">
    <tbody>
        <tr class="header" ><td colspan="3">系统用户组</td></tr>
        <tr class="header">
            <td>用户组ID</td>
            <td>用户组</td>
            <td >组权限</td>
        </tr>
        
<? if(is_array($usergrouplist)) { foreach($usergrouplist as $usergroup) { ?>
        <tr>
            <td width="100" class="altbg1"><strong><?=$usergroup['groupid']?></strong></td>
            <td width="150" class="altbg1"><?=$usergroup['grouptitle']?></td>
            <td class="altbg1"><? if(1!=$usergroup['groupid']) { ?><a href="index.php?admin_usergroup/regular/<?=$usergroup['groupid']?><?=$setting['seo_suffix']?>">设置</a><? } ?></td>
        </tr>
        
<? } } ?>
    </tbody>
</table>
<br>
<? include template(footer,admin); ?>
