<? if(!defined('IN_TIPASK')) exit('Access Denied'); ?>
<div class="sousuo">
    <form action="<?=SITE_URL?>?question/search/3.html" method="post">
�������������⣺<input type="text" style="vertical-align: middle;" class="ask_input"  value="<? if(isset($word)) { ?><?=$word?><? } ?>" name="word"  maxlength="100"  >
        <input type="submit" class="btn_search" value="�� ��" style="vertical-align: middle;" >
        <input type="button" class="btn_ask" value="�� ��" style="vertical-align: middle;"  onclick="ask_submit();" >
    </form>
</div><? if('index/default'==$regular) { ?><div class="friend_link">
    <div class="friend_linkl">�������ӣ�</div>
    <div class="friend_linkr">
        <ul>
            
<? if(is_array($linklist)) { foreach($linklist as $link) { ?>
            <li>
                <a target="_blank" href="<?=$link['url']?>" title="<?=$link['description']?>">
                    <? if($link['logo']) { ?><img src="<?=$link['logo']?>" alt="<?=$link['name']?>" />
                    <? } else { ?>                    <?=$link['name']?>
                    <? } ?>                </a>
            </li>
            
<? } } ?>
        </ul>
    </div>
</div><? } ?><div style="text-align: center;">
    <div style="width: 980px; height: 105px; background: none repeat scroll 0% 0% rgb(255, 255, 255); font-size: 12px; font-family: '����'; clear: both; margin: 0pt auto;" class="DivCss20091020WZiColor333">
        <div style="line-height: 20px; text-align: center; margin-top: 5px;"><br />�������ڽ����ʴ�ƽ̨�����й��ڼ����Ľ��鶼���ܴ���ִҵҽʦ���������ϡ�ҽ�����������۽���������˹۵㣬��������ģ���վ���е���ط������Ρ�<br />
            |<a href="<?=SITE_URL?>" target="_blank"><?=$setting['site_name']?></a>|<a href="mailto:<?=$setting['admin_email']?>" target="_blank">��ϵ����</a>|<a href="http://www.miibeian.gov.cn" target="_blank"><?=$setting['site_icp']?></a>|<script src="http://s23.cnzz.com/stat.php?id=3435241&web_id=3435241" type="text/javascript"></script>
        </div>
        <div style="line-height: 23px; text-align: center; position: relative;"></div><div style="font-family: Verdana; line-height: 20px; text-align: center;"></div>
    </div>
</div>
<?=$setting['site_statcode']?>

</body>
</html>