<? if(!defined('IN_TIPASK')) exit('Access Denied'); ?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <? $user=$this->user; $setting=$this->setting; ?>    <HEAD><TITLE>Tipask��̨����ϵͳ</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=<?=TIPASK_CHARSET?>">
        <META http-equiv=x-ua-compatible content=ie=7><LINK href="css/admin/admin_m.css" type="text/css" rel="stylesheet">
    <BODY style="HEIGHT: 100%" scroll="yes">
        <SCRIPT type="text/javascript">
            function setTab(name,cursel,n){
                for(i=1;i<=n;i++){
                    var menu=document.getElementById(name+i);
                    var con=document.getElementById("con_"+name+"_"+i);
                    try {
                        menu.className=i==cursel?"navon":"";
                        con.style.display=i==cursel?"block":"none";
                    }catch(e){}
                }
                return false;
            }
        </SCRIPT>
        <TABLE height="100%" cellSpacing=0 cellPadding=0 width="100%" border=0>
            <TBODY>
                <TR>
                    <TD vAlign="top" colSpan="2" height="80">
                        <DIV id="header">
                            <DIV class="logo fl">
                                <DIV class="png"><img height="43" alt=" Tipask�ʴ�ϵͳ " src="css/common/logo.png" width="160"></DIV>
                                <DIV class="lun"><font size="3" color="#E68319">Tipask<?=TIPASK_VERSION?></font></DIV>
                            </DIV>
                            <!--�󵼺� -->
                            <UL class="nav">
                                <LI class="navon" id="nav1" onclick="return setTab('nav',1,8)"><EM><A href="#">���ò���</A></EM></LI>
                                <LI id="nav2" onclick="return setTab('nav',2,8)"><EM><A href="#">ϵͳ����</A></EM></LI>
                                <LI id="nav3" onclick="return setTab('nav',3,8)"><EM><A href="#">�û�����</A></EM></LI>
                                <LI id="nav4" onclick="return setTab('nav',4,8)"><EM><A href="#">���ݹ���</A></EM></LI>
                                <LI id="nav5" onclick="return setTab('nav',5,8)"><EM><A href="#">ϵͳ����</A></EM></LI>
                                <LI id="nav6" onclick="return setTab('nav',6,8)"><EM><A href="#">���ݿ����</A></EM></LI>
                                <LI id="nav7" onclick="return setTab('nav',7,8)"><EM><A href="#">ϵͳ����</A></EM></LI>
                            </UL>
                            <!--ͷ����Ϣ����-->
                            <DIV class="wei fl">�û�����<?=$user['username']?>��<A href="index.php?admin_main/logout<?=$setting['seo_suffix']?>">�˳�</A>��&nbsp;|&nbsp; <A href="index.php?admin_main/stat<?=$setting['seo_suffix']?>" target="main">���������ҳ</A> &nbsp;|&nbsp; <A  href="index.php?admin_setting/cache<?=$setting['seo_suffix']?>" target="main">��ջ���</A> &nbsp;|&nbsp; <A class="s0" style="CURSOR: pointer" href="<?=SITE_URL?>" target="_blank">������վ��ҳ</A> &nbsp;|&nbsp; <A title=���˵�ǰһҳ style="CURSOR: pointer" onclick=history.go(-1);>����һҳ</A> &nbsp;</DIV>
                            <!--ͷ����Ϣ��������-->
                        </DIV>
                    </TD>
                </TR>

                <TR>
                    <TD id="main-fl" vAlign="top">
                        <DIV id="left">
                            <DIV id="con_nav_1">
                                <H1>���ò���</H1>
                                <DIV class="cc"></DIV>
                                <UL>
                                    <li><a href="index.php?admin_setting/base<?=$setting['seo_suffix']?>" target="main">վ������</a></li>
                                    <li><a href="index.php?admin_setting/register<?=$setting['seo_suffix']?>" target="main">ע������</a></li>
                                    <li><a href="index.php?admin_user<?=$setting['seo_suffix']?>" target="main">�û�����</a> </li>
                                    <li><a href="index.php?admin_expert<?=$setting['seo_suffix']?>" target="main">ר�ҹ���</a> </li>
                                    <li><a href="index.php?admin_usergroup<?=$setting['seo_suffix']?>" target="main">�û���Ȩ��</a></li>
                                    <li><a href="index.php?admin_question<?=$setting['seo_suffix']?>" target="main">�������</a> </li>
                                    <li><a href="index.php?admin_question/searchanswer<?=$setting['seo_suffix']?>" target="main">�ش����</a></li>
                                    <li><a href="index.php?admin_note<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_nav<?=$setting['seo_suffix']?>" target="main">��������</a> </li>
                                    <li><a href="index.php?admin_link<?=$setting['seo_suffix']?>" target="main">��������</a> </li>
                                    <li><a href="index.php?admin_ad<?=$setting['seo_suffix']?>" target="main">������</a></li>
                                    <li><a href="index.php?admin_category<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_db/backup<?=$setting['seo_suffix']?>" target="main">���ݿⱸ��</a> </li>
                                    <li><a href="index.php?admin_db/sqlwindow<?=$setting['seo_suffix']?>" target="main">SQL��ѯ</a> </li>
                                    <li><a href="index.php?admin_setting/cache<?=$setting['seo_suffix']?>" target="main">���»���</a> </li>
                                </UL>
                            </DIV>
                            <DIV id="con_nav_2" style="DISPLAY: none">
                                <H1>ϵͳ����</H1>
                                <DIV class=cc></DIV>
                                <UL>
                                    <li><a href="index.php?admin_setting/base<?=$setting['seo_suffix']?>" target="main">վ������</a></li>
                                    <li><a href="index.php?admin_setting/time<?=$setting['seo_suffix']?>" target="main">ʱ������</a> </li>
                                    <li><a href="index.php?admin_setting/list<?=$setting['seo_suffix']?>" target="main">��ҳ����</a> </li>
                                    <li><a href="index.php?admin_setting/search<?=$setting['seo_suffix']?>" target="main">��������</a></li>
                                    <li><a href="index.php?admin_setting/register<?=$setting['seo_suffix']?>" target="main">ע������</a></li>
                                    <li><a href="index.php?admin_nav<?=$setting['seo_suffix']?>" target="main">��������</a> </li>
                                    <li><a href="index.php?admin_link<?=$setting['seo_suffix']?>" target="main">��������</a> </li>
                                </UL>
                                <H1>�߼�����</H1>
                                <DIV class=cc></DIV>
                                <ul>
                                    <li><a href="index.php?admin_setting/mail<?=$setting['seo_suffix']?>" target="main">�ʼ�����</a> </li>
                                    <li><a href="index.php?admin_setting/msgtpl<?=$setting['seo_suffix']?>" target="main">��Ϣģ��</a> </li>
                                    <li><a href="index.php?admin_setting/credit<?=$setting['seo_suffix']?>" target="main">��������</a> </li>
                                     <li><a href="index.php?admin_setting/topset<?=$setting['seo_suffix']?>" target="main">�ö���������</a> </li>
                                    <li><a href="index.php?admin_setting/ebank<?=$setting['seo_suffix']?>" target="main">�Ƹ���ֵ</a> </li>
                                    <li><a href="index.php?admin_setting/seo<?=$setting['seo_suffix']?>" target="main">seo����</a> </li>
                                    <li><a href="index.php?admin_setting/stopcopy<?=$setting['seo_suffix']?>" target="main">���ɼ�����</a> </li>
                                    <li><a href="index.php?admin_editor/setting<?=$setting['seo_suffix']?>" target="main">�༭������</a> </li>
                                    <li><a href="index.php?admin_setting/qqlogin<?=$setting['seo_suffix']?>" target="main">qq��������</a> </li>
                                </ul>
                            </DIV>
                            <DIV id="con_nav_3" style="DISPLAY: none">
                                <H1>�û�����</H1>
                                <DIV class=cc></DIV>
                                <ul>
                                    <li><a href="index.php?admin_user/add<?=$setting['seo_suffix']?>" target="main">�����û�</a> </li>
                                    <li><a href="index.php?admin_user<?=$setting['seo_suffix']?>" target="main">�û�����</a> </li>
                                    <li><a href="index.php?admin_banned/add<?=$setting['seo_suffix']?>" target="main">��ֹIP</a> </li>
                                    <li><a href="index.php?admin_expert<?=$setting['seo_suffix']?>" target="main">ר�ҹ���</a> </li>
                                    <li><a href="index.php?admin_usergroup<?=$setting['seo_suffix']?>" target="main">�û���</a></li>
                                    <li><a href="index.php?admin_usergroup/system<?=$setting['seo_suffix']?>" target="main">ϵͳ�û���</a></li>
                                </ul>
                            </DIV>
                            <DIV id="con_nav_4" style="DISPLAY: none">
                                <H1>���ݹ���</H1>
                                <DIV class=cc></DIV>
                                <ul>
                                    <li><a href="index.php?admin_question/examine<?=$setting['seo_suffix']?>" target="main">�ʴ����</a></li>
                                    <li><a href="index.php?admin_question<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_question/searchanswer<?=$setting['seo_suffix']?>" target="main">�ش����</a></li>
                                    <li><a href="index.php?admin_category<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_topic<?=$setting['seo_suffix']?>" target="main">ר�����</a></li>
                                    <li><a href="index.php?admin_word<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_inform<?=$setting['seo_suffix']?>" target="main">�ٱ�����</a></li>
                                    <li><a href="index.php?admin_note<?=$setting['seo_suffix']?>" target="main">�������</a></li>
                                    <li><a href="index.php?admin_ad<?=$setting['seo_suffix']?>" target="main">������</a></li>
                                </ul>
                                <H1>��Ʒ�̵�</H1>
                                <DIV class=cc></DIV>
                                <ul>
                                    <li><a href="index.php?admin_gift<?=$setting['seo_suffix']?>" target="main">��Ʒ�б�</a></li>
                                    <li><a href="index.php?admin_gift/add<?=$setting['seo_suffix']?>" target="main">������Ʒ</a></li>
                                    <li><a href="index.php?admin_gift/note<?=$setting['seo_suffix']?>" target="main">��Ʒ����</a></li>
                                    <li><a href="index.php?admin_gift/addrange<?=$setting['seo_suffix']?>" target="main">��Ʒ�۸�����</a></li>
                                    <li><a href="index.php?admin_gift/log<?=$setting['seo_suffix']?>" target="main">��Ʒ�һ���־</a></li>
                                </ul>
                            </DIV>
                            <DIV id="con_nav_5" style="DISPLAY: none">
                                <H1>ϵͳ����</H1>
                                <DIV class="cc"></DIV>
                                <ul>
                                    <li><a href="index.php?admin_setting/cache<?=$setting['seo_suffix']?>" target="main">���»���</a> </li>
                                    <li><a href="index.php?admin_datacall/default<?=$setting['seo_suffix']?>" target="main">js���ݵ���</a> </li>
                                </ul>
                            </DIV>
                            <DIV id="con_nav_6" style="DISPLAY: none">
                                <H1>���ݿ����</H1>
                                <DIV class=cc></DIV>
                                <UL>
                                    <li><a href="index.php?admin_db/backup<?=$setting['seo_suffix']?>" target="main">���ݿⱸ��</a> </li>
                                    <li><a href="index.php?admin_db/tablelist<?=$setting['seo_suffix']?>" target="main">���ݿ��Ż�</a> </li>
                                    <li><a href="index.php?admin_db/sqlwindow<?=$setting['seo_suffix']?>" target="main">SQL��ѯ</a> </li>
                                </UL>
                            </DIV>
                            <DIV id="con_nav_7" style="DISPLAY: none">
                                <H1>ϵͳ����</H1>
                                <DIV class=cc></DIV>
                                <ul>
                                    <li><a href="index.php?admin_setting/passport<?=$setting['seo_suffix']?>" target="main">ͨ��֤</a> </li>
                                    <li><a href="index.php?admin_setting/ucenter<?=$setting['seo_suffix']?>" target="main">UCenter</a> </li>
                                </ul>
                            </DIV>
                        </DIV><!--end left-->
                    </TD>
                    <TD id="mainright" style="HEIGHT: 94%" vAlign="top">
                        <IFRAME style="OVERFLOW: visible" name="main" src="index.php?admin_main/stat<?=$setting['seo_suffix']?>" frameBorder=0 width="100%" scrolling="yes" height="100%"></IFRAME>
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
    </BODY>
</HTML>