<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template('header'); ?>
<div class="box">
    <div class="box_left_nav font_gray" style="font-size: 12px;">
�� <a href="<?=SITE_URL?>"><?=$setting['site_name']?></a> &gt; ������ʾ</div>
    <!--���� begin-->
    <div class="box_allwidth">
        <table align="center" class="ask_ok_ok" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td class="boxtop2_title" height="60"><strong><?=$message?></strong></td>
            </tr>
            <? if($redirect == 'BACK') { ?>            <tr>
                <td>
                    <span>
                        <a class="question" href="javascript:history.go(-1);">����ԭ��</a>&nbsp;&nbsp;
                        <a class="question" href="<?=SITE_URL?>?user/ask.html">�ҵ�����</a>&nbsp;&nbsp;
                        <a class="question" href="<?=SITE_URL?>">�ص���ҳ</a>
                    </span>
                </td>
            </tr>
            <? } elseif($redirect) { ?>            <tr>
                <td>
                    <span>
                        ҳ�潫��3����Զ���ת����һҳ����Ҳ����ֱ�ӵ� <a href="<?=$redirect?>" >������ת</a>��
                        <script type="text/javascript">
                            function redirect(url, time) {
                                setTimeout("window.location='" + url + "'", time * 1000);
                            }
                            redirect('<?=$redirect?>', 3);
                        </script>
                    </span>
                </td>
            </tr>
            <? } ?>        </table>
    </div>
    <!--���� end-->
    <div class="clear">
    </div>
</div>
<? include template('footer'); ?>