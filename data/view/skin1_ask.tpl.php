<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template('header'); ?>
<link rel="stylesheet" href="<?=SITE_URL?>js/ueditor/themes/default/ueditor.css"/>
<link rel="stylesheet" href="<?=SITE_URL?>js/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css"/>
<script src="<?=SITE_URL?>js/ueditor/editor_config.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>js/ueditor/editor_all.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>js/ueditor/third-party/SyntaxHighlighter/shCore.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>js/calendar.js" type="text/javascript"></script>
<!--<base id="headbase" href="<?=SITE_URL?>" /> 
<script src="<?=SITE_URL?>js/core/core.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>js/pw_ajax.js" type="text/javascript"></script>
<script> 
var imgpath = 'images';
var verifyhash = '67b52431';
var modeimg = '';
var modeBase = '';
var winduid = '1';
var windid	= 'admin';
var groupid	= '3';
var basename = '';
var temp_basename = '';
var db_shiftstyle = '1';
var pw_baseurl = "http://localhost/";
function shiftStyle(){
if(db_shiftstyle == 1){
if (getObj('widthCfg').innerHTML=='�л�������') {
if(!getObj('fullscreenStyle')) {
var l = document.createElement('link');
l.id="fullscreenStyle";
l.rel="stylesheet";
l.type="text/css";
l.href="images/fullscreen.css";
l.media="all";
document.body.appendChild(l);
}else {
getObj('fullscreenStyle').disabled = false;
}
getObj('widthCfg').innerHTML='�л���խ��';
var widthCfg = 1;
} else {
var widthCfg = 0;
getObj('fullscreenStyle').disabled=true;
getObj('widthCfg').innerHTML='�л�������';
}
SetCookie('widthCfg',widthCfg);
if(typeof goTop!="undefined"){
goTop.setStyle();
}
if(typeof messagetip!="undefined"&&typeof messagetip.db!="undefined"){
messagetip.setStyle();
messagetip.update();
}
}
};
</script>-->
<div class="box">
    <div class="box_left">
        <div class="box_left_nav font_gray" style="font-size: 12px;">��  <a href="<?=SITE_URL?>"><?=$setting['site_name']?></a> &gt;&gt; ����</div>
        <div class="box_left1 height960">
            
<form name="askform" onsubmit="return check_askform(this);" action="<?=SITE_URL?>question/add.html" method="post" >
                <div class="tiwen">
                    <div class="shur1">
                        <h2>��������:</h2> <input type="text"  maxlength="100" size="65" name="title" value="<?=$word?>" id="mytitle"  class="input1"  />
                    </div>
                    <div class="shur1">
                        <h2>�ʴ�����:</h2>
                        <select name="ask_area" id="ask_area">
                              <option value="">��ѡ��</option>
                              <option value="1">��ͨ��ѯ</option>
                              <option value="2">������ѯ</option>
                              <option value="3">��������</option>
                              <option value="4">��������</option>
                          </select>
                    </div>                    <div class="shur1">
                        <h2>���ⲹ��:</h2>
                    </div>
                    <div class="clr"></div>
                    <div class="shur4">
    <div style="margin-left: 75px;"><a href="#" onclick="showdetail();" title="�����������ϸ�ڣ�ȷ�������׼ȷ���"><b id="showtype">-</b>������������(ѡ��)</a></div>
    <script type="text/plain" id="mydescription" name="description" style="width:550px;margin-left:75px;"></script>
    <script type="text/javascript">
    var mycontent = new baidu.editor.ui.Editor(editor_options);
    mycontent.render("mydescription");
    </script>
</div>
 <div class="clr"></div>
                    <div class="shur1">
                        <h2>��ǩ(TAG):</h2>  
                        <input type="text"  maxlength="8" name="qtags[]" class="input4" value="" />
                        <input type="text"  maxlength="8" name="qtags[]" class="input4" value="" />                   
                        <input type="text"  maxlength="8" name="qtags[]" class="input4" value="" />                  
                        <input type="text"  maxlength="8" name="qtags[]" class="input4" value="" />
                        <input type="text"  maxlength="8" name="qtags[]" class="input4" value="" />

                        </div>
                    <div class="clr"></div>
                    <div class="shur shur3">
                        <h2>�������</h2>
                        <div id="classnav" name="classnav">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr valign="top">
                                    <td width="15%">

                                        <select  id="ClassLevel1" class="catselect2" size="8" name="classlevel1" >
                                            <option selected></option>
                                        </select>

                                    </td>
                                    <td align="center" valign="middle" width="7%">
                                        <div><b>��</b></div>
                                    </td>
                                    <td width="15%">                                        
                                        <select  id="ClassLevel2"  class="catselect2" size="8" name="classlevel2">
                                            <option selected></option>
                                        </select>                                        
                                    </td>
                                    <td align="center" valign="middle" width="7%">
                                        <div style="display: none;" id="jiantou"><b>��</b></div>
                                    </td>
                                    <td width="15%">
                                        <select id="ClassLevel3"  class="catselect2" size="8" onchange="getCidValue();"  name="classlevel3">
                                            <option selected></option>
                                        </select>
                                    </td>
                                    <td width="32%"></td>
                                </tr>
                                <tr valign="top">
                                    <td class="tiw_biaozhu" colspan="6" align="left" valign="middle">����ѡ����ȷ�ķ��࣬��ʹ�������⾡��õ����</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="clr"></div>

                    <div class="shur shur2">
                        <h2>��������</h2>
                        <div class="shezhi">
                            <h3>���ͷ�:</h3>                           
                            <select name="scorelist" id="scorelist" class="select1" onchange="otherscore();">
                                <option selected="selected" value="0">0</option>
                                <option value="1">1</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="other">����</option>
                            </select>&nbsp;<span class="zhusi" style="display:none;" id="showscore"><input type="text"  maxlength="8" name="givescore"  id="givescore"  class="input4"  value="0"/></span>&nbsp;�� &nbsp;<span class="zhusi">��Ŀǰ�ĲƸ�ֵ:<? if($user['credit2']) { ?><?=$user['credit2']?><? } else { ?>0<? } ?> ���ͷ�Խ�ߣ����������Խ�ܹ�ע��</span>
                            <div class="clr"></div> 
                            <h3>�����ö�:</h3> 
                            <input type="checkbox" name="istop" value="0"/>&nbsp;&nbsp;&nbsp; <span>���ͣ�</span><select name="toptype" id="scorelist" class="select1" onchange="otherscore();">
                                <option value="3" selected>�����ö�</option>
                                <option value="2">�����ö�</option>
                                <option value="1">ȫվ�ö�</option>
                            </select>&nbsp;
                            &nbsp;&nbsp;&nbsp; 
                            <span>�ö����ޣ�</span><input type="text" name="topstart" value="" onclick="showcalendar()" style="width:90px"/>&nbsp;-<input type="text" name="topend" value="" onclick="showcalendar()" style="width:90px"/><div id="append"></div>
                            <span class="zhusi">ȫ���ö�10����Ҫ����100�����</span>
                            <br/><span class="zhusi">����ʣ��������:<? if($user['credit2']) { ?><?=$user['credit2']?><? } else { ?>0<? } ?> ����һ��������ң�����ʹ�����ø����ع���ᣬ���������Խ�ܹ�ע��</span>
                            <? if($user['uid']) { ?>                            <h3>�����趨:</h3><input type="checkbox" name="hidanswer" value="1" class="checkbox1" />&nbsp;<span class="zhusi">����Ҫ�����Ƹ�ֵ10��</span>
                            <div class="clr"></div>
                            <? } ?>                            <? if($setting['code_ask']) { ?>                            <h3>��֤��:</h3><input type="text"  maxlength="8" name="code"    class="input4"  />&nbsp;<img  id="verifycode" align="absmiddle"   src="<?=SITE_URL?>user/code.html" />&nbsp;<a href="javascript:updatecode();">��һ��</a>
                            <div class="clr"></div> 
                            <? } ?>                            <input type="hidden" value="0" name="cid" />
                            <input type="hidden" value="<?=$askfromuid?>" name="askfromuid" />                                                    </div>
                    </div>
                    <div id="searchresult"></div>
                    <div class="asksubmit"><button name="submit" type="submit" class="btn_addques" ></button></div>
                    <div class="clr"></div>
                </div>

            </form>          
            
        </div>
        <div class="blank10" style="display: none;"></div>
    </div>

    <div class="boxthree">
        <div class="box_left_nav">
        </div>

        <div class="boxthree2 height800">
            <div class="boxtop">
                <div class="boxtop1_more" style="font-size: 12px; font-weight: normal;" id="linkMore"><a id="searchurl" href="javascript:void(0)" target="_blank">����&gt;&gt;</a></div>
����ѽ������
            </div>
            <div class="boxthree2down">
                <div class="bb_xjxg">
                    <div style="clear: both;">
                        <div class="login">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td colspan="3" height="50">
                                        <table id="searchresult" border="0" cellpadding="0" cellspacing="0" width="100%"></table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
 <script type="text/javascript">
     $(document).ready(function () {
        $("#mytitle").bind("blur", function(){
            searchurl="<?=SITE_URL?>?question/ajaxsearch/"+encodeURI($("#mytitle").val());
            $("#searchresult").load(searchurl);
        });
    });
    var showtagdesc = true;
    var sortobj=eval('(<?=$category_js?>)');
    var g_ClassLevel1;
    var g_ClassLevel2;
    var g_ClassLevel3;
    var class_level_1=sortobj.category1;
    var class_level_2=sortobj.category2;
    var class_level_3=sortobj.category3;
    var button_noselect="��ѡ��";

    function getCidValue()
    {
        var _cl1 = document.askform.ClassLevel1;
        var _cl2 = document.askform.ClassLevel2;
        var _cl3 = document.askform.ClassLevel3;
        var _cid = document.askform.cid;
        if(_cl1.value!=0) _cid.value = _cl1.value;
        if(_cl2.value!=0) _cid.value = _cl2.value;
        if(_cl3.value!=0) _cid.value = _cl3.value;
    }
    function FillClassLevel1(ClassLevel1)
    {
        ClassLevel1.options[0] = new Option("aa", "0");
        for(i=0; i<class_level_1.length; i++)
        {
            ClassLevel1.options[i] = new Option(class_level_1[i][1], class_level_1[i][0]);
        }
        // ClassLevel1.options[0].selected = true;
        ClassLevel1.length = i;
    }
    function FillClassLevel2(ClassLevel2, class_level_1_id)
    {
        ClassLevel2.options[0] = new Option(button_noselect, "");
        count = 1;
        for(i=0; i<class_level_2.length; i++){
            if(class_level_2[i][0].toString() == class_level_1_id) {
                ClassLevel2.options[count] = new Option(class_level_2[i][2], class_level_2[i][1]);
                count = count+1;}
        }
        ClassLevel2.options[0].selected = true;
        ClassLevel2.length = count;
    }
    function FillClassLevel3(ClassLevel3, class_level_2_id)
    {
        ClassLevel3.options[0] = new Option(button_noselect, "");
        count = 1;
        for(i=0; i<class_level_3.length; i++) {
            if(class_level_3[i][0].toString() == class_level_2_id) {
                ClassLevel3.options[count] = new Option(class_level_3[i][2], class_level_3[i][1]);
                count = count+1;}
        }
        ClassLevel3.options[0].selected = true;
        ClassLevel3.length = count;
    }
    function ClassLevel2_onchange()
    {
        getCidValue();
        FillClassLevel3(g_ClassLevel3, g_ClassLevel2.value);
        if (g_ClassLevel3.length <= 1) {
            g_ClassLevel3.style.display = "none";
            document.getElementById("jiantou").style.display = "none";
        }
        else {
            g_ClassLevel3.style.display = "";
            document.getElementById("jiantou").style.display = "";
        }
    }
 
    function ClassLevel1_onchange()
    {
        getCidValue();
        FillClassLevel2(g_ClassLevel2, g_ClassLevel1.value);
        ClassLevel2_onchange();

    }
    function InitClassLevelList(ClassLevel1, ClassLevel2, ClassLevel3)
    {
        g_ClassLevel1=ClassLevel1;
        g_ClassLevel2=ClassLevel2;
        g_ClassLevel3=ClassLevel3;
        g_ClassLevel1.onchange = Function("ClassLevel1_onchange();");
        g_ClassLevel2.onchange = Function("ClassLevel2_onchange();");
        FillClassLevel1(g_ClassLevel1);
        ClassLevel1_onchange();
    }
    InitClassLevelList(document.askform.ClassLevel1, document.askform.ClassLevel2, document.askform.ClassLevel3);

    var selected_id_list="0"
    var blank_pos = selected_id_list.indexOf(" ");
    var find_blank = true;
    if (blank_pos == -1) {
        find_blank = false;
        blank_pos = selected_id_list.length;
    }
    var id_str = selected_id_list.substr(0, blank_pos);
    g_ClassLevel1.value = id_str;
    ClassLevel1_onchange();

    if (find_blank == true) {
        selected_id_list = selected_id_list.substr(blank_pos + 1,   selected_id_list.length - blank_pos - 1);
        blank_pos = selected_id_list.indexOf(" ");
        if (blank_pos == -1) {
            find_blank = false;
            blank_pos = selected_id_list.length;
        }
        id_str = selected_id_list.substr(0, blank_pos);
        g_ClassLevel2.value = id_str;
        ClassLevel2_onchange();

        if (find_blank == true) {
            selected_id_list = selected_id_list.substr(blank_pos + 1,  selected_id_list.length - blank_pos - 1);
            blank_pos = selected_id_list.indexOf(" ");
            if (blank_pos == -1) {
                find_blank = false;
                blank_pos = selected_id_list.length;
            }
            id_str = selected_id_list.substr(0, blank_pos);
            g_ClassLevel3.value = id_str;
        }
    }

    /*���*/
    function check_askform(obj){
        if (bytes($.trim(obj.title.value)) < 8 ||  bytes($.trim(obj.title.value))>120) {
            alert("������ⳤ�Ȳ�������4���֣����ܳ���30�֣�");
            obj.title.focus();
            return false;
        }
        if (bytes(obj.ask_area.value)=='') {
            alert("��ѡ���ʴ�����");
            obj.ask_area.focus();
            return false;
        }
        if(obj.classlevel1.selectedIndex==-1){
            alert("û��ѡ�����!");
            return false;
        }
        <? if($user['uid']) { ?>        //���Ƹ�ֵ�Ƿ���
        var offerscore=0;
        var selectsocre = $("#scorelist").val();
        if(selectsocre != 'other')
            $("#givescore").val(selectsocre);
        if(obj.hidanswer.checked)offerscore+=10;
        offerscore+=parseInt(obj.givescore.value);
        if(offerscore><?=$user['credit2']?>){
            alert("��ĲƸ�ֵ����!");
            return false;
        }
        <? } ?>        if(showtagdesc)
            $("#qtags").val("");
return parseContent();
    }

function parseContent(){

var content=mycontent.getContent();
var sell=/<p prize="(\d+)([^\d]?[\d\w]*)"[^>]+>([^>]+)<\/p>/ig;
var hide = /<p replyhidden=".*">([^>]+)<\/p>/ig;
content=content.replace(sell,"[sell=$1,$2]$3[/sell]");
content=content.replace(hide,"[post]$1[/post]");
//alert(content);
mycontent.setContent(content);
return true;
}


    function otherscore(){
        if($("#scorelist").val() == 'other')
            $("#showscore").show();
        else
            $("#showscore").hide();
   
    }
    /*��ʾ����������ϸ�ڱ༭��*/
    function showdetail(){
        var showtype = $("#showtype").html();
        if('+' == showtype){
            $("#mydescription").show(); //����
            $("#showtype").html('-');
        }else{
            $("#mydescription").hide(); //����
            $("#showtype").html('+');            
        }
    }
    /*���ر�ǩ����*/
    function hidetagdesc(){       
        if(showtagdesc){
            $("#qtags").val('');
            showtagdesc = false;
        }           
    }
    function ajaxgettags(){
        var qtitle = $.trim($("#mytitle").val());
        if('' == qtitle)
            return false;
        $.post("<?=SITE_URL?>question/ajaxtags.html",{qtitle:qtitle},function(result){
            $("#qtags").val(result);
            showtagdesc = false;
        });
    }   
</script>
<? include template('footer'); ?>