<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template(header,admin); ?>
<div style="width:100%; height:15px;color:#000;margin:0px 0px 10px;">
    <div style="float:left;"><a href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main"><b>���������ҳ</b></a>&nbsp;&raquo;&nbsp;SEO����</div>
</div><? if(isset($message)) { $type=isset($type)?$type:'correctmsg';  ?><table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
    <tr>
        <td class="<?=$type?>"><?=$message?></td>
    </tr>
</table><? } ?><form action="index.php?admin_setting/seo<?=$setting['seo_suffix']?>" method="post">
    <a name="��������"></a>
    <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
        <tr class="header">
            <td colspan="2">ȫ������</td>
        </tr>
        <tr>
            <td class="altbg1" width="45%"><b>����URL��̬��:</b><br><span class="smalltxt">����Rewrite������վ����Ҫ���˽��Լ��ķ�����/�������������Ƿ���Rewrite֧��</span></td>
            <td class="altbg2">
                <input class="radio"  type="radio"  <? if(1==$setting['seo_on'] ) { ?>checked<? } ?>  value="1" name="seo_on"><label for="yes">��</label>&nbsp;&nbsp;&nbsp;
                <input class="radio"  type="radio"  <? if(0==$setting['seo_on'] ) { ?>checked<? } ?> value="0" name="seo_on"><label for="no">��</label>
            </td>
        </tr>
        <tr>
            <td class="altbg1" width="45%"><b>ҳ���ַ��׺:</b><br><span class="smalltxt">�������޸ı��������˲���Ŀ����Ϊ�˷�ֹ������ȫ��rewrite������Ӱ��tipask�ķ���</span></td>
            <td class="altbg2"><input type="text" value="<?=$setting['seo_suffix']?>" name="seo_suffix" /></td>
        </tr>
        <tr>
            <td class="altbg1" width="45%"><b>����ͷ����Ϣ:</b><br><span class="smalltxt">���Լ���Ĭ�ϼ��ص�js����ʽ���������κε���Ϣ,��google����</span></td>
            <td class="altbg2"><textarea class="area" name="seo_headers"  style="height:100px;width:300px;"><?=$setting['seo_headers']?></textarea></td>
        </tr>
    </table>
    <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
        <tr class="header">
            <td colspan="2">SEO�Ż�����</td>
        </tr>
        <tr>
            <td colspan="2">
                ��ҳSEO�Ż����ù���<br />
                1����վ����:<?=wzmc?>��Ӧ�÷�Χ������λ�ã�<br />
                2����������:<?=flmc?>��Ӧ�÷�Χ������鿴ҳ������鿴ҳ��<br />
                3���������:<?=wtbt?>��Ӧ�÷�Χ������鿴ҳ��<br />
                4������״̬:<?=wtzt?>��Ӧ�÷�Χ������鿴ҳ��<br />
                5�����ϱ�ǩ���������������"{}"������ͨ���������������Ż�ҳ��SEO���ã������ǩ֮������ð�����ַ�"-"�����","���ǿո����������ΪĬ��SEO���ã������ǩ����Ӧ�÷�Χ������ʾ�˱�ǩ
            </td>
        </tr>
        <tr class="header">
            <td colspan="2">��ҳ</td>
        </tr>
        <tr>
            <td width="45%"><b>Title:</b><br><span class="smalltxt">�ؼ��ֽ�������ÿһ��ҳ���title����</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_index_title']?>" name="seo_index_title" /></td>
        </tr>
        <tr>
            <td width="45%"><b>Meta keywords:</b><br><span class="smalltxt">�������濴��keywords</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_index_keywords']?>" name="seo_index_keywords"></td>
        </tr>
        <tr>
            <td width="45%"><b>Meta Description:</b><br><span class="smalltxt">���������濴��Description</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_index_description']?>" name="seo_index_description"></td>
        </tr>
        <tr class="header">
            <td colspan="2">����鿴ҳ</td>
        </tr>
        <tr>
            <td width="45%"><b>����ؼ���:</b><br><span class="smalltxt">�ؼ��ֽ�������ÿһ��ҳ���title����</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_question_title']?>" name="seo_question_title" /></td>
        </tr>
        <tr>
            <td width="45%"><b>Meta keywords:</b><br><span class="smalltxt">�������濴��keywords</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_question_keywords']?>" name="seo_question_keywords"></td>
        </tr>
        <tr>
            <td width="45%"><b>Meta Description:</b><br><span class="smalltxt">���������濴��Description</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_question_description']?>" name="seo_question_description"></td>
        </tr>
        <tr class="header">
            <td colspan="2">����鿴ҳ</td>
        </tr>
        <tr>
            <td width="45%"><b>����ؼ���:</b><br><span class="smalltxt">�ؼ��ֽ�������ÿһ��ҳ���title����</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_category_title']?>" name="seo_category_title" /></td>
        </tr>
        <tr>
            <td width="45%"><b>Meta keywords:</b><br><span class="smalltxt">�������濴��keywords</span></td>
            <td><input type="text" size="60" value="<?=$setting['seo_category_keywords']?>" name="seo_category_keywords"></td>
        </tr>
        <tr>
            <td  width="45%"><b>Meta Description:</b><br><span class="smalltxt">���������濴��Description</span></td>
            <td ><input type="text" size="60" value="<?=$setting['seo_category_description']?>" name="seo_category_description"></td>
        </tr>
    </table>
    <br>
    <center><input type="submit" class="button" name="submit" value="�� ��"></center><br>
</form>
<br>
<? include template(footer,admin); ?>