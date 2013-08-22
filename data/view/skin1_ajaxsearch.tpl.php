<? if(!defined('IN_TIPASK')) exit('Access Denied'); $setting=$this->setting; if(is_array($questionlist)) { foreach($questionlist as $question) { ?>
<tr>
<td height="24">
<div class="tiw_biaozhu f14">
<a href="<?=$question['url']?>" target="_blank"> <?=$question['title']?> ?</a><br/>
</div>
</td>
</tr>
 
<? } } ?>
