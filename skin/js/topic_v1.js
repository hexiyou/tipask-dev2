var StateID = 0;
var blCode = false;
var blMainBodyOnClick = false;
var clickCount = 0;
var reload = false;
var bestid = "";

window.onload = function() {
    //$('tbCode').onclick = tbCodeClick;
    var watcher = new FormSubmitWatcher('msgform');
   // LoadAd();
}

var FormSubmitWatcher = Class.create();

FormSubmitWatcher.prototype = {

    initialize: function(cForm) {
        this.cForm = $(cForm);
        var elements = Form.getElements(cForm);
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            if (element.type.toLowerCase() == "textarea") {
                this.cText = element;
                this.cText.onkeypress = this.SubmitOnce.bindAsEventListener(this);
            }
        }
    },

    SubmitOnce: function(evt) {
        var x = evt.keyCode;
        var q = evt.ctrlKey;
        if (q && (x == 13 || x == 10)) {
            var mainbody = $('mainbody').value;
            if (mainbody == '') {
                $('tiptitle').innerHTML = '提示';
                $('tip').innerHTML = '回复内容不能为空！';
                messbox.Show();
                return;
            }
            if (mainbody.length <= 19) { ShowError('<span>回复内容不能少于20个汉字！</span>'); return; }
            if (mainbody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) { ShowError('<span>回复内容不能少于20个汉字！</span>'); return; }

            if (getck("username") == '') {
                box.Show();
                choosebtn(true);
                return false;
            }

            if (clickCount == 0) {
                clickCount++;
                document.getElementById('btnReply').disabled = 'false';
                this.cForm.submit();
            }


        }
    }
}

function messboxClose(messid, mid) {
    document.getElementById("MessageInboxHtml").style.display = "none";
    var url = '/web/topic.asmx/CloseMessageInbox';
    var pars = 'messid=' + messid + '&mid=' + mid;
    var myAjax = new Ajax.Request(
    url,
    {
        method: 'post',
        parameters: pars,
        onSuccess: function(originalRequest) {
            var Result = originalRequest.responseText;
            var regExp = />([{][^<]{0,}[}])</;
            regExp.exec(Result);
            Result = RegExp.$1;
            var Json = Result.evalJSON();
            if (Json.Success) {
                // alert('设置成功！');
            }
        }
    });
}

function openShutManager(oSourceObj, oTargetObj, shutAble, oOpenTip, oShutTip) {
    var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;
    var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;
    var openTip = oOpenTip || "";
    var shutTip = oShutTip || "";
    if (targetObj.style.display != "none") {
        if (shutAble) return;
        targetObj.style.display = "none";
        if (openTip && shutTip) {
            sourceObj.innerHTML = shutTip;
        }
    } else {
        targetObj.style.display = "block";
        if (openTip && shutTip) {
            sourceObj.innerHTML = openTip;
        }
    }
}
function CloseMessBox() {
    messbox.Close();
    if (reload) {
        reload = false;
        location.reload();
    }
}


//function LoadAd() {
//    try {
//        $('IFrame1000').src = $('tb1000').value;
//    } catch (e) { }
//    try {
//        $('IFrame2000').src = $('tb2000').value;
//    } catch (e) { }
//    try {
//        $('IFrame3000').src = $('tb3000').value;
//    } catch (e) { }
//}

function QuickReply() {
    if ($('huidabox').style.display == "none") {
        var shtmls = $('askboxbotton').innerHTML;
        $('askboxtop').innerHTML = shtmls;
        $('askboxbotton').innerHTML = '';
        $('huidabox').style.display = "block";
        $('askbox_Reply').style.display = "none";
        var watcher = new FormSubmitWatcher('msgform');
    }
    else {
        var html = $('askboxtop').innerHTML;
        $('askboxbotton').innerHTML = html;
        $('askboxtop').innerHTML = '';
        $('huidabox').style.display = "none";
        $('askbox_Reply').style.display = "block";
        var watcher = new FormSubmitWatcher('msgform');
    }
}


var acookie = document.cookie.split("; ");
function getck(sname) {
    for (var i = 0; i < acookie.length; i++) {
        var arr = acookie[i].split("=");
        if (sname == arr[0]) {
            if (arr.length > 1)
                return unescape(arr[1]);
            else
                return "";
        }
    }
    return "";
}
//===============================ctrl+enter
function login(form) {
    var username = $('uname').value;
    var pwd = $('pwd').value;
    if (username == '') { alert('用户名不能为空!'); return false; }
    if (pwd == '') { alert('密码不能为空!'); return false; }
    var url = '/web/Member.asmx/Login';
    var pars = 'loginName=' + username + '&password=' + pwd + '';
    var myAjax = new Ajax.Request(
    url,
    {
        method: 'post',
        parameters: pars,
        asynchronous: false,
        onSuccess: function(originalRequest) {
            var Result = originalRequest.responseText;
            var regExp = />([{][^<]{0,}[}])</;
            regExp.exec(Result);
            Result = RegExp.$1;
            var Json = Result.evalJSON();
            if (Json.Success) {

                if (clickCount == 0) {
                    clickCount++;
                    document.getElementById('loginbtn').disabled = 'false';
                    mysubmit();
                }

                // location.reload();//页面刷新
            }
            else { alert(Json.Info); }
        }
    });
}

function choosebtn(x) {
    if (x == true) {
        $('loginbtn').innerHTML = '<label><input id="Submit1" type="button" id="loginbtn" name="Submit1" onclick="login()" value="登录" class="inputbut" onmouseover="this.className=\'inputbut inputbut2\'" onmouseout="this.className=\'inputbut\'" /></label><span class="titw"><a href="http://my.39.net/find_password.aspx">忘记密码?</a></span>';
    }
    else {
        $('loginbtn').innerHTML = '<label><input id="Submit1" type="Submit" value="登录" class="inputbut" onmouseover="this.className=\'inputbut inputbut2\'" onmouseout="this.className=\'inputbut\'" /></label><span class="titw"><a href="http://my.39.net/find_password.aspx">忘记密码?</a></span>';
    }
}

function mysubmit() {
    var username = $('uname').value;
    var pwd = $('pwd').value;
    var url = '/post.ashx';
    var pars = 'action=login&app=1&ref=&uname=' + username + '&pwd=' + pwd + '&time=' + Date();
    var myAjax = new Ajax.Request(
  url,
  {
      method: 'post',
      parameters: pars,
      asynchronous: false,
      onSuccess: function(originalRequest) {
          var Result = originalRequest.responseText;
          this.cForm = $(msgform);
          this.cForm.submit();
      }
  });
    this.cForm = document.getElementById('msgform');
    this.cForm.submit();

}


function ReplySubmit(form) {
    var mainbody = $('mainbody').value;
    if (mainbody == '') {
        $('tiptitle').innerHTML = '提示';
        $('tip').innerHTML = '回复内容不能为空！';
        messbox.Show();
        return false; }
        if (mainbody.length <= 19) { ShowError('<span>回复内容不能少于20个汉字！</span>'); return false; }
    if (mainbody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) {
        ShowError('<span>回复内容不能少于20个汉字！</span>');
        return false;
    }
    if (getck("username") == '') {
        $('logmess').innerHTML = '(要登录后才能回答问题)';
        box.Show();
        choosebtn(true);
        return false;
    }
    if (getck("pid") == $('MemberID').value) {
        ShowError('<span>对不起！您不可以回答自己的问题！</span>');
        return false;
    }
    $('btnReply').disabled = true;
    return true;
}
function ShowError(mess) {
    $('errormsg').innerHTML = mess;
    $('errormsg').style.display = "block";
}

function HideError() {
    $('errormsg').style.display = "none";
}
//补充问题
function Expand(obj, tid) {
    if (getck("username") == '') {
        box.Show();
        return false;
    }
    openShutManager(obj, 'suppbox');
    if (getck("pid") != $('MemberID').value) {
        $('expandmess').innerHTML = '对不起，此问题不是您提交的问题，你无权补充该问题的内容！';
        return false;
    }

}
function AddQuestionSubmit() {
    var mymainBody = $('expandbody').value;
    if (mymainBody == "") {
        $('tiptitle').innerHTML = '补充提问提示';
        $('tip').innerHTML = '补充提问内容不能为空！'
        messbox.Show();
        return false;
    }
    else if (mymainBody.length < 10) {
    $('tiptitle').innerHTML = '补充提问提示';
    $('tip').innerHTML = '补充问题内容长度不能小于10个字符！'
     messbox.Show();
        return false;
    }
    var strmainBody = encodeURIComponent($('expandbody').value);
    var re = /<[\s\S]*>[\s\S]*<\/(.)*>|<[a-zA-Z](.)*>/g;
    if (re.test($('expandbody').value)) {
        $('tiptitle').innerHTML = '补充提问提示';
        $('tip').innerHTML = '对不起,您的补充提问内容包含非法代码！'
        messbox.Show();
        return false;
    }
}

function ShowBest(obj, tid, rid) {
    if (bestid != "" && $(bestid).innerHTML != '') {
        $(bestid).innerHTML = '';
        openShutManager(obj, bestid);
    }
    bestid = "bestbox_" + rid + "";
    var besthtml = "";
    besthtml += "<span class=\"cbtop\"><span class=\"cbbt\"><strong>采纳答案确认提示</strong>（您的一声谢谢是对回答者最大的肯定!）</span>";
    besthtml += "<span class=\"guanbi\"><a href=\"javascript:void(0);\" target=\"_self\" title=\"关闭\" onclick=\"CloseBox(this, '" + bestid + "')\"></a></span></span>";
    besthtml += "<span class=\"cbdown\">";
    besthtml += "<textarea id=\"bestbody\" name=\"bestbody\"></textarea><span class=\"cbbuts\">";
    besthtml += "<label><input value=\"确认\" id=\"btnAddBest\" name=\"btnAddBest\" type=\"button\" onclick=\"return AddBest(" + tid + "," + rid + ");\" class=\"cbbut cbbut1\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
    besthtml += "<label><input value=\"取消\" type=\"button\" class=\"cbbut cbbut1\" onclick=\"CloseBox(this, '" + bestid + "')\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
    besthtml += "</span></span></span>";
    $(bestid).innerHTML = besthtml;
    openShutManager(obj, bestid);
}
function CloseBox(obj, id) {
    bestid = '';
    $(id).innerHTML = '';
    openShutManager(obj, id);
}
//设置最佳答案
function AddBest(tid, rid) {
    
    var ThankDoctor = $('bestbody').value;
    var MainBody = encodeURIComponent($('bestbody').value);
    if (MainBody == '') {
        $('tiptitle').innerHTML = '设置最佳答案提示';
        $('tip').innerHTML = '对医生感谢的内容不能为空！';
        messbox.Show();
        return false;
    }
    document.getElementById('btnAddBest').disabled = "disabled";
    var url = '/post.aspx?tid=' + tid;
    var pars = 'action=addreplybest&rid=' + rid + '&ThankDoctor=' + ThankDoctor+'&tid=' + tid;
    var myAjax = new Ajax.Request(
       url,
       {
           method: 'post',
           parameters: pars,
           onSuccess: function(originalRequest) {
               var Result = originalRequest.responseText;
               //alert(Result);
               $('tiptitle').innerHTML = '成功采纳提示';
               $('tip').innerHTML = Result;
               messbox.Show();
               reload = true;
               return false;
               //location.reload();
           }
       });
   }
//修改回复
function ShowEditReply(obj, fid, tid, rid) {
    if (bestid != "" && $(bestid).innerHTML != '') {
        $(bestid).innerHTML = '';
        openShutManager(obj, bestid);
    }
    bestid = "ReplyBox_" + rid + "";
    var url = '/web/topic.asmx/GetReply';
    var pars = 'rid=' + rid + '&tid=' + tid;
    new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (typeof (Json) != "undefined") {

               var besthtml = "";
               besthtml += "<span class=\"cbtop\"><span class=\"cbbt\"><strong>修改回答</strong></span>";
               besthtml += "<span class=\"guanbi\"><a href=\"javascript:void(0);\" target=\"_self\" title=\"关闭\" onclick=\"CloseBox(this, '" + bestid + "')\"></a></span></span>";
               besthtml += "<span class=\"cbdown\">";
               //besthtml += "<form method=\"post\"  action=\"/post.aspx?rid=" + rid + "&tid=" + tid + "&fid=" + fid + "\" target=\"_self\">";
               besthtml += "<input type=\"hidden\" name=\"action\" value=\"editmyreply\" />";
               besthtml += "<textarea id=\"updatebody\" name=\"updatebody\" style=\" height:100px;OVERFLOW-X:auto;OVERFLOW:scroll;overflow-x:hidden\">" + Json.MainBody + "</textarea><span class=\"cbbuts\">";
               besthtml += "<label><input value=\"确认\" id=\"btnEdit\" name=\"btnEdit\" type=\"button\" onclick=\"return CheckAddIssue(" + tid + "," + rid + ");\" class=\"cbbut cbbut1\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
               besthtml += "<label><input value=\"取消\" type=\"button\" class=\"cbbut cbbut1\" onclick=\"CloseBox(this, '" + bestid + "')\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
               //besthtml += "</form>";
               besthtml += "</span></span></span>";
               $(bestid).innerHTML = besthtml;
               openShutManager(obj, bestid);
               //   location.href = '#' + bestid;
           }
           else {
               bestid = "";
               $('tiptitle').innerHTML = '出错提示';
               $('tip').innerHTML = '对不起，网络出错！';
               messbox.Show();
           }

       }
   });
    
    
}
function CheckAddIssue( tid, rid) {
    var mainbody = $('updatebody').value;
    if (mainbody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) {
        //alert('回复内容不能少于20个汉字！');
        $('tiptitle').innerHTML = '修改回复提示';
        $('tip').innerHTML = '修改回复内容不能少于20个汉字！';
        messbox.Show();
        return false;
    }
    document.getElementById('btnEdit').disabled = "disabled";
   var url = '/post.aspx?tid=' + tid;
    var pars = 'action=editmyreply&rid=' + rid + '&mainbody=' + mainbody + '&tid=' + tid;
    var myAjax = new Ajax.Request(
       url,
       {
           method: 'post',
           parameters: pars,
           onSuccess: function(originalRequest) {
               $('tiptitle').innerHTML = '成功修改提示';
               $('tip').innerHTML = '您已成功修改回答！';
               messbox.Show();
               reload = true;
               return false;
           },
           onFailure: function() {
               bestid = "";
               $('tiptitle').innerHTML = '出错提示';
               $('tip').innerHTML = '对不起，网络出错！';
               messbox.Show();
           }
       });
    
}
//继续追问
function ShowInquire(obj, tid, rid) {
    var inquirecount = 0;
    var url = '/web/topic.asmx/InquireCount';
    var pars = 'rid=' + rid + '';
    var myAjax = new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (Json.Success) {
               inquirecount = Json.Info;
               if (inquirecount >= 3) {
                   $('tiptitle').innerHTML = '追问提示';
                   $('tip').innerHTML = '您已使用了3次追问机会！';
                   messbox.Show();
                   return false;
               }
               var title = "您共有3次追问机会";
               inquirecount = 3 - inquirecount;
               if (inquirecount < 3) {
                   title = "您还有" + inquirecount + "次追问机会";
               }

               if (bestid != "" && $(bestid).innerHTML != '') {
                   $(bestid).innerHTML = '';
                   openShutManager(obj, bestid);
               }
               bestid = "bestbox_" + rid + "";
               var besthtml = "";
               besthtml += "<span class=\"cbtop\"><span class=\"cbbt\"><strong>继续追问</strong><span>（" + title + "）</span></span>";
               besthtml += "<span class=\"guanbi\"><a href=\"javascript:void(0);\" target=\"_self\" title=\"关闭\" onclick=\"CloseBox(this, '" + bestid + "')\"></a></span></span>";
               besthtml += "<span class=\"cbdown\">";
               besthtml += "<textarea id=\"inquirebody\" name=\"inquirebody\" style=\" height:100px;OVERFLOW-X:auto;OVERFLOW:scroll;overflow-x:hidden\" onfocus=\"HideInquireError();\"></textarea><span class=\"cbbuts\">";
               besthtml += "<label><input value=\"确认\" type=\"button\" id=\"btnInquire\" name=\"btnInquire\"  onclick=\"return AddInquire(" + tid + "," + rid + ");\" class=\"cbbut cbbut1\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
               besthtml += "<label><input value=\"取消\" type=\"button\" class=\"cbbut cbbut1\" onclick=\"CloseBox(this, '" + bestid + "')\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
               besthtml += "</span>";
               besthtml += "<span class=\"cuowu\" style=\"display: none;\" id=\"inquireMsg\"><span>您输入的内容少于20个中文字，请输入后重新提交!</span></span>";
               besthtml += "</span></span>";
               $(bestid).innerHTML = besthtml;
               openShutManager(obj, bestid);

           }
           else {
               $('tiptitle').innerHTML = '追问提示';
               $('tip').innerHTML = '您还没有登录,请登录后再追问!';
               messbox.Show();
               return false;
           }

       }
   });
    

}

function HideInquireError() {
    $('inquireMsg').style.display = "none";
}
function RepalceMark(content) {
    content = content.replace(/[?'?']/g, "？");
    return content;
}

function AddInquire(tid, rid) {
    var inquirebody = $('inquirebody').value;
    var MainBody = encodeURIComponent($('inquirebody').value);
    if (MainBody == '') {
        $('tiptitle').innerHTML = '追问提示';
        $('tip').innerHTML = '追问内容不能为空！';
        messbox.Show();
        return false;
    }
    if (inquirebody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) {
        $('inquireMsg').style.display = "block";
        return false;
    }
    inquirebody = RepalceMark(inquirebody);
    document.getElementById('btnInquire').disabled = "disabled";
   var url = '/post.aspx?tid=' + tid;
    var pars = 'action=inquireask&rid=' + rid + '&mainbody=' + inquirebody +'&tid=' + tid ;
    var myAjax = new Ajax.Request(
       url,
       {
           method: 'post',
           parameters: pars,
           onSuccess: function(originalRequest) {
           var Json = originalRequest.responseText.evalJSON();
               if (Json.Success) {
                   $('tiptitle').innerHTML = '追问成功提示';
                   if (Json.TypeID < 3) {
                       $('tip').innerHTML = '您的追问已成功提交！';
                   }
                   else {
                       $('tip').innerHTML = '追问成功！您的3次追问机会已用完，谢谢追问！';
                   }
                   messbox.Show();
                   reload = true;
                   return false;
               }
               else {
                   bestid = "";
                   $('tiptitle').innerHTML = '出错提示';
                   $('tip').innerHTML = Json.Info;
                   messbox.Show();
               }
           },
           onFailure: function() {
               bestid = "";
               $('tiptitle').innerHTML = '出错提示';
               $('tip').innerHTML = '对不起，网络出错！';
               reload = true;
               messbox.Show();
               return false;
           }
       });
 
}
//回复追问
function ShowReplyInquire(obj, tid, rid, qid) {
    if (bestid != "" && $(bestid).innerHTML != '') {
        $(bestid).innerHTML = '';
        openShutManager(obj, bestid);
    }
    bestid = "InquireBox_" + qid + "";
    var besthtml = "";
    besthtml += "<span class=\"cbtop\"><span class=\"cbbt\"><strong>回复追问</strong>（字数为20个中文字以上!）</span>";
    besthtml += "<span class=\"guanbi\"><a href=\"javascript:void(0);\" target=\"_self\" title=\"关闭\" onclick=\"CloseBox(this, '" + bestid + "')\"></a></span></span>";
    besthtml += "<span class=\"cbdown\">";
    //besthtml += "<input type=\"hidden\" name=\"inquieID\" value=\""+ qid +"\" />";
    besthtml += "<textarea id=\"inquireReplybody\" name=\"inquireReplybody\" style=\" height:100px;OVERFLOW-X:auto;OVERFLOW:scroll;overflow-x:hidden\" onfocus=\"HideInquireError();\"></textarea><span class=\"cbbuts\">";
    besthtml += "<label><input value=\"确认\" id=\"btnInquireReply\" name=\"btnInquireReply\" type=\"button\" onclick=\"return AddReplyInquire(" + tid + "," + rid + ","+qid +");\" class=\"cbbut cbbut1\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
    besthtml += "<label><input value=\"取消\" type=\"button\" class=\"cbbut cbbut1\" onclick=\"CloseBox(this, '" + bestid + "')\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label></span>";
    besthtml += "<span class=\"cuowu\" style=\"display: none;\" id=\"inquireMsg\"><span>您输入的内容少于20个中文字，请输入后重新提交!</span></span>";
    besthtml += "</span></span>";
    $(bestid).innerHTML = besthtml;
    openShutManager(obj, bestid);
}
function AddReplyInquire(tid, rid,qid) {
    
    var inquirebody = $('inquireReplybody').value;
    var MainBody = encodeURIComponent($('inquireReplybody').value);
    if (MainBody == '') {
        $('tiptitle').innerHTML = '回复追问提示';
        $('tip').innerHTML = '回复追问内容不能为空！';
        messbox.Show();
        return false;
    }
    if (inquirebody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) {
        $('inquireMsg').style.display = "block";
        return false;
    }
    inquirebody = RepalceMark(inquirebody);
    document.getElementById('btnInquireReply').disabled = "disabled";
    var url = '/post.aspx?tid=' + tid;
    var pars = 'action=inquirereply&rid=' + rid + '&inquireid=' + qid + '&mainbody=' + inquirebody + '&tid=' + tid ;
    var myAjax = new Ajax.Request(
       url,
       {
           method: 'post',
           parameters: pars,
           onSuccess: function(originalRequest) {
           var Json = originalRequest.responseText.evalJSON();
               if (Json.Success) {
                   $('tiptitle').innerHTML = '回复追问成功提示';
                   $('tip').innerHTML = '回复追问成功！';
                   messbox.Show();
                   reload = true;
                   return false;
               }
               else {
                   $('tiptitle').innerHTML = '回复追问出错提示';
                   $('tip').innerHTML = Json.Info;
                   messbox.Show();
                   reload = false;
                   return false;
               }
           }
       });

   }

   //修改追问回复
   function ShowEditInquire(obj, tid, rid, qid) {
       if (bestid != "" && $(bestid).innerHTML != '') {
           $(bestid).innerHTML = '';
           openShutManager(obj, bestid);
       }
       bestid = "InquireBox_" + qid + "";
       var url = '/web/topic.asmx/GetInquireReply';
       var pars = 'qid=' + qid;
       new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (typeof (Json) != "undefined") {
               var besthtml = "";
               besthtml += "<span class=\"cbtop\"><span class=\"cbbt\"><strong>修改回复</strong></span>";
               besthtml += "<span class=\"guanbi\"><a href=\"javascript:void(0);\" target=\"_self\" title=\"关闭\" onclick=\"CloseBox(this, '" + bestid + "')\"></a></span></span>";
               besthtml += "<span class=\"cbdown\">";
               besthtml += "<input type=\"hidden\" name=\"action\" value=\"editmyreply\" />";
               besthtml += "<textarea id=\"updatebody\" name=\"updatebody\" style=\" height:100px;OVERFLOW-X:auto;OVERFLOW:scroll;overflow-x:hidden\" onfocus=\"HideInquireError();\">" + Json.MainBody + "</textarea><span class=\"cbbuts\">";
               besthtml += "<label><input value=\"确认\" id=\"btnEdit\" name=\"btnEdit\" type=\"button\" onclick=\"return SubmitEditInquire(" + tid + "," + qid + ");\" class=\"cbbut cbbut1\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label>";
               besthtml += "<label><input value=\"取消\" type=\"button\" class=\"cbbut cbbut1\" onclick=\"CloseBox(this, '" + bestid + "')\" onmouseover=\"this.className='cbbut cbbut2'\" onmouseout=\"this.className='cbbut cbbut1'\" /></label></span>";
               besthtml += "<span class=\"cuowu\" style=\"display: none;\" id=\"inquireMsg\"><span>您输入的内容少于20个中文字，请输入后重新提交!</span></span>";
               besthtml += "</span></span>";
               $(bestid).innerHTML = besthtml;
               openShutManager(obj, bestid);
           }
           else {
               bestid = "";
               $('tiptitle').innerHTML = '出错提示';
               $('tip').innerHTML = '对不起，网络出错！';
               messbox.Show();
           }

       }
       ,
       onFailure: function() {
           tousubox.Close();
           $('tiptitle').innerHTML = '提示';
           $('tip').innerHTML = '系统出错，请稍后再试，谢谢！';
           messbox.Show();
       }
   });
   }

   function SubmitEditInquire(tid, qid) {
       var mainbody = $('updatebody').value;
       if (mainbody.replace(/[^\u4e00-\u9fa5]/g, '').length <= 19) {
           $('inquireMsg').style.display = "block";
           return false;
       }
       mainbody = RepalceMark(mainbody);
       document.getElementById('btnEdit').disabled = "disabled";
       var url = '/post.aspx?tid=' + tid;
       var pars = 'action=editinquirpost&inquireid=' + qid + '&mainbody=' + mainbody + '&tid=' + tid;
       var myAjax = new Ajax.Request(
       url,
       {
           method: 'post',
           parameters: pars,
           onSuccess: function(originalRequest) {
               $('tiptitle').innerHTML = '成功修改提示';
               $('tip').innerHTML = '您已成功修改回复！';
               messbox.Show();
               reload = true;
               return false;
           },
           onFailure: function() {
               bestid = "";
               $('tiptitle').innerHTML = '出错提示';
               $('tip').innerHTML = '对不起，网络出错！';
               messbox.Show();
           }
       });

   }
//设置经典答案
function SetClassReply(tid, rid, mid) {
    
    var url = '/web/topic.asmx/SetClassReply';
    var pars = 'rid=' + rid + '&mid=' + mid + '&tid=' + tid;
    var myAjax = new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       onSuccess: function(originalRequest) {

           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (Json.Success) {
               jdbox.Show();
           }

       }
   });
}
function ShowMyJd() {
    var username = getck("username");
    if (username != "") {
      var url = "http://my.39.net/" + username + "/doctor-2.html";
      window.open(url);
    }
}
//投诉
function ShowTouSu(fid, tid, rid) {
    if (getck("username") == '') {
        $('tip').innerHTML = '对不起，请您登录后再操作!';
        messbox.Show();
        //还没有登陆
    }
    else {
        $('cfid').value = fid;
        $('ctid').value = tid;
        $('crid').value = rid;
        tousubox.Show();
    }
}
function Complain() {
    //var W=window.parent;
    var tid = $('ctid').value;
    var fid = $('cfid').value;
    var rid = $('crid').value;
    
    var DoctorID = 0;
    var QuestionContent = '';
    var ComplaintContent = $('ComplaintContent').value;
    if (ComplaintContent == '') { alert('投诉内容不能为空'); return; }
    //if(ComplaintContent.length<10){alert('您的回复是不是过于简单了：）');return;}
    ComplaintContent = encodeURIComponent(ComplaintContent);
    var url = '/web/topic.asmx/AddComplaintContent';
    var pars = 'Tid=' + tid + '&fid=' + fid + '&rid=' + rid + '&DoctorID=' + DoctorID + '&QuestionContent=' + QuestionContent + '&ComplaintContent=' + ComplaintContent + '';
    var myAjax = new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       asynchronous: false,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (Json.Success) {
               tousubox.Close();
               $('tiptitle').innerHTML = '投诉提示';
               $('tip').innerHTML = '您的投诉信息已提交，请等待工作人员的审核！';
               messbox.Show();
           } else {

           }
       },
       onFailure: function() {
           tousubox.Close();
           $('tiptitle').innerHTML = '投诉提示';
           $('tip').innerHTML = '系统出错，请稍后再试，谢谢！';
           messbox.Show();
       }
   });

}

/*--置顶 添加时间2010-04-26--*/
var xhr;
function XHR() {
    try {
        xhr = new XMLHttpRequest();
    } catch (e) {
        var a = ['MSXML2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP.2.0', 'MICROSOFT.XMLHTTP.1.O', 'MICROSOFT.XMLHTTP.1', 'MICROSOFT.XMLHTTP']
        for (var i = 0; i < a.length; i++) {
            try {
                xhr = new ActiveXObject(a[i]);
                break;
            } catch (e) { }
        }
    }
    return xhr;
}

var votefalt = true;
//投票
function AddDing(tid, rid) {
    if (votefalt) {
        var url = '/post.aspx';
        var pars = 'action=addDing&rid=' + rid + '&act=add&tid=' + tid;
        var myAjax = new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       asynchronous: false,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           if (Result != -1) {
               document.getElementById("Ding" + rid).innerHTML = Result;
               var voteid = "Vote" + rid + "";
               $(voteid).style.display = "block";
               setTimeout(function() { $(voteid).style.display = "none";}, 3000);
           }
           else {
               $('tiptitle').innerHTML = '投票提示';
               $('tip').innerHTML = '您已经投过票，感谢您的参与！';
               messbox.Show();
           }
       },
       onFailure: function(e) {
           votefalt = true;
       }
   });

  }
 votefalt = true;
}


//收藏夹
function AddFav(fid, tid) {
    if (getck("username") == '') {
        $('logmess').innerHTML = '（要登录后才能收藏）';
        box.Show();
        return false;
        //还没有登陆
    }

    var url = '/web/TopicsFav.asmx/AddTopicsFav';
    var pars = 'fid=' + fid + '&tid=' + tid + '&time=' + Date();
    var myAjax = new Ajax.Request(
   url,
   {
       method: 'post',
       parameters: pars,
       onSuccess: function(originalRequest) {
           var Result = originalRequest.responseText;
           var regExp = />([{][^<]{0,}[}])</;
           regExp.exec(Result);
           Result = RegExp.$1;
           var Json = Result.evalJSON();
           if (Json.Success) {
               favbox.Show();
           }
           else {
               $('tiptitle').innerHTML = '提示';
               $('tip').innerHTML = Json.Message;
               messbox.Show();
           }
       }
   });
}

function ShowMove(fid, tid) {
    location.href = '/topicmove.aspx?tid=' + tid;
}
function DoMove(fid, tid) {
    var toFid = $('ForumID').value;
    if (toFid <= 0) { alert('请选择版块'); return; }
    if (toFid == fid) { alert('本帖已属该版块'); return; }
    var url = '/web/topic.asmx/MoveTopic';
    var pars = 'tid=' + tid + '&fromFid=' + fid + '&toFid=' + toFid + '&time=' + Date();
    var myAjax = new Ajax.Request(
    url,
    {
        method: 'post',
        parameters: pars,
        asynchronous: false,
        onSuccess: function(originalRequest) {
            var Result = originalRequest.responseText;
            var regExp = />([{][^<]{0,}[}])</;
            regExp.exec(Result);
            Result = RegExp.$1;
            var Json = Result.evalJSON();
            alert(Json.Info);
            if (Json.Success) {
                location.reload(); //页面刷新
            }
        }
    });
}