<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template(header,admin); ?>
            <TABLE class="tableborder" cellSpacing="1" cellPadding="4" width="100%" align="center">
              <TBODY>
              <TR>
                <TD style="PADDING-LEFT: 20px">��һ��ʹ�ã��뿴&nbsp;&nbsp;[&nbsp;<A style="COLOR: red" href="#"><U>������·��</U></A>&nbsp;]</TD>
                <TD>����汾��TipAsk <?=TIPASK_VERSION?> Release <?=TIPASK_RELEASE?>[<A href="#"><B>������</B></A>]</TD>
              </TR>
              <TR>
                <TD style="PADDING-LEFT: 20px">����ϵͳ�� PHP:<?=$serverinfo?></TD>
                <TD>����������:<?=$_SERVER['SERVER_SOFTWARE']?></TD>
              </TR>
              <TR>
                <TD style="PADDING-LEFT: 20px">MySQL �汾:<?=$dbversion?></TD>
                <TD>�ϴ�����:<?=$fileupload?></TD>
              </TR>
              <TR>
                <TD style="PADDING-LEFT: 20px">��ǰ���ݿ�ߴ�:<?=$dbsize?></TD>
                <TD>������:<?=$_SERVER['SERVER_NAME']?> (<?=$_SERVER['SERVER_ADDR']?>:<?=$_SERVER['SERVER_PORT']?>) </TD>
              </TR>
              <TR>
                <TD style="PADDING-LEFT: 20px">magic_quote_gpc:<?=$magic_quote_gpc?></TD>
                <TD>allow_url_fopen:<?=$allow_url_fopen?> </TD>
              </TR>
              </TBODY>
            </TABLE>
            <? if($verifyquestions||$verifyanswers ) { ?>            <h3 style="color:#E14300;">����������:&nbsp;&nbsp;
            <span style="font-size:12px;font-weight:normal">
            <a href="index.php?admin_question/examine<?=$setting['seo_suffix']?>">�ȴ���˵���������(<font color="red"><?=$verifyquestions?></font>)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="index.php?admin_question/examineanswer<?=$setting['seo_suffix']?>">�ȴ���˵Ļش�����(<font color="red"><?=$verifyanswers?></font>)</a>
            </span>
            </h3>
            <? } ?>            <!--���¶�̬-->
            <TABLE class="tableborder" cellSpacing="1" cellPadding="4" width="100%" align="center">
              <TBODY>
              <TR class="header"><TD colSpan="12"> <DIV class="NavaL ndt">�����Ŷ�</DIV></TD></TR>
              <TR class="altbg2"><TD>��Ȩ����:<a href="http://www.tipask.com/" target="_blank">Tipask���������� (Tipask Inc.)</a></TD></TR>
              <TR class="altbg2"><TD>�ܲ߻�����Ŀ����:<a href="mailto:tipask@qq.com" target="_blank">Tipask</a></TD></TR>
              <TR class="altbg2"><TD>�����Ŷ�:<a href="mailto:sky_php@qq.com" target="_blank">sdf_sky</a>,<a href="mailto:phpinside@163.com" target="_blank">phpinside</a></TD></TR>
              <TR class="altbg2"><TD>�ٷ���վ:<a href="http://www.tipask.com/" target="_blank">http://www.tipask.com/</a></TD></TR>
             </TBODY>
            </TABLE>
            <DIV class="c"></DIV>
            <TABLE class="tableborder" cellSpacing=1 cellPadding=4 width="100%" align="center">
              <TBODY>
              <TR class="header">
                <TD colSpan="3">Tipask�ʴ�ϵͳ�ٷ�����</TD></TR>
              <TR class="altbg2">
                <TD><A href="http://help.tipask.com" target="_blank">��������</A></TD>
                <TD><A href="http://bbs.tipask.com/?forum-4-1.html" target="_blank">��վ����</A></TD>
                <TD><A href="http://bbs.tipask.com/?forum-9-1.html" target="_blank">ģ����</A></TD>
              </TR>
              </TBODY>
            </TABLE>
<? include template(footer,admin); ?>