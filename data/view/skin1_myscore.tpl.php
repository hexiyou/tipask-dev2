<? if(!defined('IN_TIPASK')) exit('Access Denied'); include template('header'); ?>
<div class="box">
    <div class="box_left_nav font_gray" style="font-size: 12px;">→ <a href="<?=SITE_URL?>"><?=$setting['site_name']?></a> &gt; <a>我的积分</a> </div>
    <!--内容 begin-->
    <div class="ps_content">
        <!--左边 begin -->
        <div class="ps_contentl">
            <!--用户信息-->
            <!--左边 begin -->
            <!-- 个人信息 开始-->
            <div class="ps_box1">
                <div class="person_name"><?=$user['username']?> </div>
                <div class="user_info">
                    <div class="person_pic"><img id="headPic" src="<?=$user['avatar']?>" alt="<?=$user['username']?>" title="<?=$user['username']?>"></div>
                    <div class="user_subinfo">
                        <span><?=$user['grouptitle']?></span>
                    </div>
                </div>
                <div class="user_info">
                    <? if(2==$user['grouptype']) { ?>                    <div>
                        <? $credit1percent=round(($user['credit1']/$user['creditshigher'])*100); ?>                        <? $credit1percentleft = 100-$credit1percent-1 ?>                        升级进度<img src="css/default/jindutiao_yellow.gif" alt="" style="vertical-align: middle;" width="<?=$credit1percent?>" height="7"><img src="css/default/jindutiao_white-02.gif" alt="" style="vertical-align: middle;" width="<?=$credit1percentleft?>" height="7"><img src="css/default/jindutiao_end.gif" alt="" style="vertical-align: middle;" width="1" height="7"><span class="font_gray">(<?=$user['credit1']?>/<?=$user['creditshigher']?>)</span>
                    </div>
                    <? } ?>                </div>
            </div>
            <!-- 个人信息 结束-->
            <div class="nav_bar">
                <dl>
                    <dd class="hover" id="myscore"><a href="<?=SITE_URL?>?user/score.html">我的积分</a></dd>
                    <dd class="nav_link" id="myask"><a href="<?=SITE_URL?>?user/profile.html">个人资料</a></dd>
                    <dd class="nav_link" id="myask"><a href="<?=SITE_URL?>?user/ask.html">我的提问</a></dd>
                    <dd class="nav_link" id="myanswer"><a href="<?=SITE_URL?>?user/answer.html">我的回答</a></dd>
                    <dd class="nav_link" id="myanswer"><a href="<?=SITE_URL?>?user/favorite.html">我的收藏</a></dd>
                    <dd class="nav_link" id="mymsg"><a href="<?=SITE_URL?>?message/new.html">我的消息</a></dd>
                </dl>
            </div>
            <!--左边 end -->
        </div>
        <!--左边 end -->
        <!--右边 begin -->
        <div id="centerInfo" class="ps_contentr" style>
            <!-- 个人主页 begin-->
            <? if($setting['outextcredits']) { ?>            <div class="column">
                <div class="column_title">积分兑换</div>
                <form name="exchangeform"  action="<?=SITE_URL?>?user/exchange.html" method="post">
                    <div class="taber">
                        <h3>积分兑换</h3>
                    </div>
                    <div class="taber" style="background-color:#FAFDFE;">
                        <div style="float:left;margin-left:10px">
                            <input type="text" id="exchangeamount"  name="exchangeamount" onkeyup="init_exchange()" size="5" value="10"  >
                            <select id="tocredits"  name="tocredits" onchange="init_exchange()" >
                                
<? if(is_array($outextcredits)) { foreach($outextcredits as $index => $credits) { ?>
                                <option value="<?=$credits['creditsrc']?>|<?=$credits['creditdesc']?>" src="<?=$credits['creditsrc']?>" unit="<?=$credits['unit']?>" title="<?=$credits['title']?>" index="<?=$index?>" ratio="<?=$credits['ratio']?>"><?=$credits['title']?></option>
                                
<? } } ?>
                            </select>
                        </div>

                        <div style="float:left;margin-left:80px">
                            所需<input  type="text" id="needamount" name="needamount" size="5" disabled="disabled"  value="0">
                            <select id="fromcredits"  name="fromcredits"></select>
                        </div>
                        <span style="margin-left:80px"><input type="submit" value="立即兑换" name="exchange"></span>
                    </div>
                    <input type="hidden" id="outextindex" name="outextindex" >
                </form>
                <script type="text/javascript">
                    function init_exchange(){
                        var exchangeamount=parseFloat($('#exchangeamount').val());
                        var creditsrc=$('#tocredits').find("option:selected").attr('src');
                        var ratio=parseFloat($('#tocredits').find("option:selected").attr('ratio'));
                        var outextindex=$('#tocredits').find("option:selected").attr('index');
                        $('#outextindex').val(outextindex);
                        $('#needamount').val(exchangeamount/ratio);
                        $("#fromcredits").empty();
                        if(1==creditsrc){
                            $("#fromcredits").append('<option value="1" title="经验">经验值</option>');
                        }else{
                            $("#fromcredits").append('<option value="2" title="财富">财富值</option>');
                        }
                    }
                    $(document).ready(init_exchange);
                </script>
            </div>
            <? } ?>            <div class="column">
                <div class="column_title">经验值</div>
                <table class="jifen" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th width="10%">总分</th>
                        <th>日常操作</th>
                        <th>奖励得分</th>
                        <th>违规处罚</th>
                    </tr>
                    <tr>
                        <td class="font_orange2"><?=$user['credit1']?></td>
                        <td class="font_orange2"><?=$detail1['other']?></td>
                        <td class="font_orange2"><?=$detail1['reward']?></td>
                        <td class="font_orange2"><?=$detail1['punish']?></td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <div class="column_title">财富值</div>
                <table class="jifen" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th width="10%">总分</th>
                        <th>奖励得分</th>
                        <th>违规处罚</th>
                        <th>悬赏付出</th>
                        <th>回答被采纳</th>
                    </tr>
                    <tr>
                        <td class="font_orange2"><?=$user['credit2']?></td>
                        <td class="font_orange2"><?=$detail2['reward']?></td>
                        <td class="font_orange2"><?=$detail2['punish']?></td>
                        <td class="font_orange2"><?=$detail2['offer']?></td>
                        <td class="font_orange2"><?=$detail2['adopt']?></td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <div class="column_title">知道明细</div>
                <table class="jifen" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <th width="10%">回答数</th>
                        <th>采纳率</th>
                        <th>提问数</th>
                    </tr>
                    <tr>
                        <td class="font_orange2"><?=$user['answers']?></td>
                        <td class="font_orange2"><?=$adoptpercent?>%</td>
                        <td class="font_orange2"><?=$user['questions']?></td>
                    </tr>
                </table>
            </div>
            <!--  消息提醒 -->
        <!-- 提问 回答-->
        <div class="ps_contentr"></div>
        <!--右边 end --></div>
    <!--内容 end-->
    <div class="clear"></div>
</div>
</div>
<? include template('footer'); ?>
