var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);
function bytes(str){
    var len=0;
    for(var i=0;i<str.length;i++){
        if(str.charCodeAt(i)>127){
            len++;
        }
        len++;
    }
    return len;
}

 
String.prototype.replaceAll = function(s1,s2) {
    return this.replace(new RegExp(s1,"igm"),s2);
}

function updatecode() {
    var img = g_site_url+"index.php?user/code/"+Math.random();
    $('#verifycode').attr("src",img);
}

$.dialog.setConfig('base', g_site_url+'css/common/dialog');


function ask_submit(){
    // document.searchform.action=g_site_url+'?question/add'+g_suffix;
    // document.searchform.submit();
    $('form[name="searchform"]').attr('action','/?question/add');
    $('form[name="searchform"]').submit();
}

function search_submit(){
    word=encodeURI($('#kw').val());
    document.searchform.action=g_site_url+'?question/search/3/'+word+g_suffix;
    document.searchform.submit();
}
function hot_search(index){
    var hot = $("#hot"+index).text();
    $("#kw").val(hot);
    search_submit();
}

var copy2Clipboard=function(txt){
    if(window.clipboardData){
        window.clipboardData.clearData();
        window.clipboardData.setData("Text",txt);
    }
    else if(navigator.userAgent.indexOf("Opera")!=-1){
        window.location=txt;
    }
    else if(window.netscape){
        try{
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        }
        catch(e){
            alert("您的firefox安全限制限制您进行剪贴板操作，请打开’about:config’将signed.applets.codebase_principal_support’设置为true’之后重试，相对路径为firefox根目录/greprefs/all.js");
            return false;
        }
        var clip=Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if(!clip)return;
        var trans=Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if(!trans)return;
        trans.addDataFlavor('text/unicode');
        var str=new Object();
        var len=new Object();
        var str=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext=txt;
        str.data=copytext;
        trans.setTransferData("text/unicode",str,copytext.length*2);
        var clipid=Components.interfaces.nsIClipboard;
        if(!clip)return false;
        clip.setData(trans,null,clipid.kGlobalClipboard);
    }
}

function getcookie(name) {
    var cookie_start = document.cookie.indexOf(name);
    var cookie_end = document.cookie.indexOf(";", cookie_start);
    return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
    seconds = seconds ? seconds : 8400000;
    var expires = new Date();
    expires.setTime(expires.getTime() + seconds);
    document.cookie = escape(cookieName) + '=' + escape(cookieValue)
    + (expires ? '; expires=' + expires.toGMTString() : '')
    + (path ? '; path=' + path : '/')
    + (domain ? '; domain=' + domain : '')
    + (secure ? '; secure' : '');
}


function addfavorite(){
    if (document.all){
        window.external.addFavorite(g_site_url,document.title);
    }else if (window.sidebar){
        window.sidebar.addPanel(document.title,g_site_url, "");
    }
}

function SetHomepage(){
    if(document.all){
        document.body.style.behavior="url(#default#homepage)";
        document.body.setHomePage(g_site_url);
    }
    else if(window.sidebar){
        if(window.netscape){
            try{
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }
            catch(e){
                alert("您的浏览器未启用[设为首页]功能，开启方法：先在地址栏内输入about:config,然后将项 signed.applets.codebase_principal_support 值该为true即可");
            }
        }
        var prefs=Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);
        prefs.setCharPref("browser.startup.homepage",g_site_url);
    }
}
function checkall(name) {
    var e = is_ie ? event : checkall.caller.arguments[0];
    obj = is_ie ? e.srcElement : e.target;
    var arr = document.getElementsByName(name);
    var k = arr.length;
    for(var i=0; i<k; i++) {
        arr[i].checked = obj.checked;
    }
}

function sendmsg(username){
	code_message = '';
    if(messcode==1){
        code_message='<tr><td align="right" width="100">验证码 ：</td><td width="90%"><input maxlength="4" class="input4" name="code" size="10" /><img align="absmiddle" id="verifycode" onclick="javascript:updatecode();" src="'+g_site_url+'index.php?user/code"/>&nbsp;<a href="javascript:updatecode();">看不清，换一个</a><span id="codetip"></span></td></tr>';
    }
    $.dialog({
        id:'sendmsgDiv',
        position:'center',
        align:'left',
        fixed:1,
        width:450,
        title:'发送消息',
        fnOk:function(){
            document.sendmsgForm.submit();
            $.dialog.close('sendmsgDiv')
        },
        fnCancel:function(){
            $.dialog.close('sendmsgDiv')
        },
        content:'<form name="sendmsgForm"  action="'+g_site_url+'index.php?message/send" method="post" ><input type="hidden" name="username" value="'+username+'"><table cellspacing="4" cellpadding="0" width="100%" border="0" valign="top"><tr><td class="f14" valign="top" nowrap align="left" width="10%" height="35">接收人 :&nbsp;&nbsp; </td><td valign="top" height="35">'+username+'</td></tr><tr><td class="f14" valign="top" nowrap align="left" height="35">主题 :&nbsp;&nbsp; </td><td valign="top" width="60%" height="35"><input type="text" class="input2" size="45"   name="title"  maxlength="20"></td></tr><tr><td class="f14" valign="top" nowrap align="left" height="35"> 内容 :&nbsp;&nbsp; </td><td valign="top" height="35"><textarea name="content" style="width: 95%; padding-top: 1px; font-size: 14px;" rows="8" ></textarea></td></tr>'+code_message+'</table></form>'
    });
}

function toQzoneLogin(){
    window.location=g_site_url+"index.php?user/qqlogin";
} 

function getbyid(id) {
    return document.getElementById(id);
}

function _attachEvent(obj, evt, func) {
    if(obj.addEventListener) {
        obj.addEventListener(evt, func, false);
    } else if(obj.attachEvent) {
        obj.attachEvent("on" + evt, func);
    }
}

function getposition(obj) {
    var r = new Array();
    r['x'] = obj.offsetLeft;
    r['y'] = obj.offsetTop;
    while(obj = obj.offsetParent) {
        r['x'] += obj.offsetLeft;
        r['y'] += obj.offsetTop;
    }
    return r;
}

function _cancelBubble(e, returnValue) {
    if(!e) return ;
    if(is_ie) {
        if(!returnValue) e.returnValue = false;
        e.cancelBubble = true;
    } else {
        e.stopPropagation();
        if(!returnValue) e.preventDefault();
    }
}