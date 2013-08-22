    var box = new LightBox("idBox");
    var tcbox = new LightBox("tcBox");
    var tcboxsu = new LightBox("tcBoxSu");
    
    function cookieget(n) {
    var name = n + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
    }
    
    if(cookieget('orderemail')!='')
    {
 $("#dingzhiID").html('修改订制活动通知');
    }
    jQuery(document).ready(function() {
        $("#btnOrder").click(function() {
            if (!isEmail($('#txtEmail').val())) {
                $("#emailErr").css('display', 'block');
                return false;
            }
            else {
                $("#emailErr").css('display', 'none');
            }
            var forumid = '';
            $('input[name="orderfourm"]:checked').each(function() {
                forumid += $(this).val() + ',';
            });
            if (forumid == '') {
                alert('你还没有选择任何内容！');
            }
            var params = "{forumid:'" + forumid + "',email:'" + $('#txtEmail').val() + "'}";
            jQuery.ajax({
                type: "POST",
                url: "/web/Member.asmx/AddOrderFourm",
                data: params,
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(originalRequest) {

                    var Json = eval("(" + originalRequest.d + ")");
                    if (Json.Success) {

                        //写入Cookie
                        var exp = new Date();
                        exp.setTime(exp.getTime() + 10 * 24 * 60 * 60 * 1000);
                        document.cookie = "orderactive=" + forumid + ";expires = " + exp.toGMTString() + ";path=/";
                        document.cookie = "orderemail=" + $('#txtEmail').val() + ";expires = " + exp.toGMTString() + ";path=/";
                        tcbox.Close();
                        tcboxsu.Show();
                        //替换状态
                        $("#dingzhiID").html('修改订制活动通知');

                    }
                    else {

                        alert(Json.Info);
                    }
                },
                error: function(x, e) {
                    alert("系统出错！");
                }
            });

        });

    });

    function getCookie(name) {
        var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
        if (arr != null) return unescape(arr[2]); return null;
    }
    
    function TcClose() {
        $("#emailErr").css('display', 'none');
        tcbox.Close();
    }
   function isEmail(str) {
       res = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
       var re = new RegExp(res);
       return !(str.match(re) == null);
   }
    function messboxClose(messid, mid) {
        document.getElementById("MessageInboxHtml").style.display = "none";
        //关闭弹出广告提醒
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
    
  function downloadCount() {
        $.get('/aspx/JsCount.aspx?typeid=1'+ '?' + Math.random(), function(data) {
          
            var counter  = data.split(";");
            
            $('#TopicCount').html(counter[0]);
            $('#AnswerCount').html(counter[1]);
            
        });
    }
    
    downloadCount();
    setInterval(downloadCount,15000);
    
    
    function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){
var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;
var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;
var openTip = oOpenTip || "";
var shutTip = oShutTip || "";
if(targetObj.style.display!="none"){
   if(shutAble) return;
   targetObj.style.display="none";
   if(openTip && shutTip){
	sourceObj.innerHTML = shutTip;
   }
} else {
   targetObj.style.display="block";
   if(openTip && shutTip){
	sourceObj.innerHTML = openTip;
   }
}
}