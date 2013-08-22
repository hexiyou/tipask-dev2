<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template('headers'); ?>
<script type="text/javascript">

    function check_code(){
        var code=$.trim($('#code').val());
        $.post("<?=SITE_URL?>index.php?user/ajaxcode",{code: code}, function(flag){
            if(1==flag){
                $('#codetip').html("<font color='green'>OK</font>");
            }else{
                $('#codetip').html("验证码不匹配！");
                $('#codetip').attr('class','font_orange2');
                codeok=0;
            }
        });
    }

</script>
<div class="box">
    <div class="box_left">
        <div class="box_left_nav font_gray">
            → <a href="<?=SITE_URL?>"><?=$setting['site_name']?></a>
        </div>
        <!--分类 开始-->
        <div class="box_left1">
            <div class="boxtop2_title boxtop1">
                <strong>您好欢迎登录<?=$setting['site_name']?>问答网</strong>
            </div>
            <div style="padding-bottom: 0pt;" class="box_left1_down">
                <br />

                <div class="write">
                    <form name="loginform"  action="<?=SITE_URL?>user/login.html" method="post">
                    <div class="fjr">
                        <div class="retext fL fontBold"> 用 户 名：</div>
                        <div class="fC"><input type="text" size="35"  maxlength="18" id="username" name="username" /></div>
                    </div>
                    <div class="fjr">
                        <div class="retext fL fontBold"> 登录密码：</div>
                        <div class="fC"><input type="password" size="35" maxlength="64"  id="password" name="password" /></div>
                    </div>
                      <? if($setting['code_login']) { ?>                        <div class="fjr">
                            <div class="retext fL fontBold"> 验证码：</div>
                            <div class="fC">
                                <input type="text" size="10"  maxlength="4" id="code" name="code" onblur="check_code()" />&nbsp;<img id="verifycode" onclick="javascript:updatecode();" src="<?=SITE_URL?>user/code.html"/>
                                <span><a href="javascript:updatecode();">看不清，换一个</a></span>
                                <span id="codetip" class="font_gray2"></span>
                            </div>
                        </div>
                        <? } ?>                    <div class="fjr">
                        <div class="retext fL fontBold">&nbsp;</div>  
                        <div class="fC"><input type="checkbox" checked value="2592000" id="cookietime" name="cookietime">&nbsp;下次自动登录</div>
                    </div>

                    <div class="btn_sure">&nbsp;&nbsp;<input type="submit" name="submit" value="登&nbsp;录" />&nbsp;忘记密码了？请点击 “<a class="red" href="<?=SITE_URL?>user/getpass.html">找回密码</a>” 。</div>
                    </form>
                </div>
            </div>
            <br /><br /><br />
        </div>
        <!--分类 结束-->
        <div class="blank10">
        </div>
        <!--精彩推荐 开始-->
        <!--精彩推荐 结束-->
        <div class="blank10">
        </div>
        <!--内容左二栏 begin-->

        <!--内容左二栏 end--></div>
    <!--内容左栏 end-->
    <!--内容右栏 begin-->
    <div class="boxthree">
        <div class="box_left_nav" align="right"><a href="<?=SITE_URL?>user/register.html">还没有账号？立即注册！ >></a></div>
        <div class="boxthree2">
            <div class="boxtop">友情贴士</div>
            <div class="boxthree2down blue">
                <div id="Userorder1_plNo2">
                    <div class="fenr margb">
                        <div class="flmrbc">
                            <p>·我们提醒您注意，您需要注册并登陆，才能享受我们的完整服务进行各项操作，否则您只有搜索和浏览的权限。</p><br />
                            <p>·密码过于简单有被盗的风险，一旦密码被盗你的个人信息有泄漏的危险，同时你和你好友的利益也会造成损害。</p><br />
                            <p>·我们拒绝垃圾邮件，请使用有效的邮件地址。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--内容右栏 end-->
    <div class="clear">
    </div>
</div>
<? include template('footer'); ?>
