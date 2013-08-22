<?php
///+++++++++++++++++++++++++++++++++++
function convert($message,$type = 'post'){
	$db_cvtimes = '';
	if (($pos = strpos($message,"[paragraph]")) !== false && $pos == min($pos, 10)) {
		$message = str_replace('[paragraph]', '', $message);
		$tmplist = explode('<br />', $message);
		$message = '<p style="text-indent: 2em;">' . implode('</p><p style="text-indent: 2em;">', $tmplist) . '</p>';
	}
	$message = preg_replace('/\[list=([aA1]?)\](.+?)\[\/list\]/is', "<ol type=\"\\1\" style=\"margin:0 0 0 25px\">\\2</ol>", $message);

	$searcharray = array('[u]','[/u]','[b]','[/b]','[i]','[/i]','[list]','[li]','[/li]','[/list]','[sub]', '[/sub]','[sup]','[/sup]','[strike]','[/strike]','[blockquote]','[/blockquote]','[hr]','[/backcolor]', '[/color]','[/font]','[/size]','[/align]'
	);
	$replacearray = array('<u>','</u>','<b>','</b>','<i>','</i>','<ul style="margin:0 0 0 25px">','<li>', '</li>','</ul>','<sub>','</sub>','<sup>','</sup>','<strike>','</strike>','<blockquote>','</blockquote>', '<hr />','</span>','</span>','</font>','</font>','</div>'
	);
	$message = str_replace($searcharray,$replacearray,$message);

	$searcharray = array(
	"/\[font=([^\[\(&\\;]+?)\]/is",
	"/\[color=([#0-9a-z]{1,15})\]/is",
	"/\[backcolor=([#0-9a-z]{1,10})\]/is",
	"/\[email=([^\[]*)\]([^\[]*)\[\/email\]/is",
	"/\[email\]([^\[]*)\[\/email\]/is",
	"/\[size=(\d+)\]/eis",
	"/\[align=(left|center|right|justify)\]/is",
	"/\[glow=(\d+)\,([0-9a-zA-Z]+?)\,(\d+)\](.+?)\[\/glow\]/is"
	);
	$replacearray = array(
	"<font face=\"\\1 \">",
	"<span style=\"color:\\1 \">",
	"<span style=\"background-color:\\1 \">",
	"<a href=\"mailto:\\1 \">\\2</a>",
	"<a href=\"mailto:\\1 \">\\1</a>",
	"size('\\1')",
	"<div align=\"\\1\">",
	"<div style=\"width:\\1px;filter:glow(color=\\2,strength=\\3);\">\\4</div>"
	);
	$message = preg_replace($searcharray,$replacearray,$message);
	/*
	if ($db_windcode) {
	$message = preg_replace($db_windcode['searcharray'], $db_windcode['replacearray'], $message);
	}
	*/
	if (1) {
		$message = preg_replace("/\[img\]([^\<\r\n\"']+?)\[\/img\]/eis", "cvpic('\\1','','800','800')",$message);
	} else{
		$message = preg_replace("/\[img\]([^\<\r\n\"']+?)\[\/img\]/eis","nopic('\\1')",$message,$db_cvtimes);
	}
	if (strpos($message,'[/MUSIC]') !== false || strpos($message,'[/music]') !== false) {
		$message = preg_replace("/\[music=(\d+)\](.+?)\[\/music\]/eis","SetMusic('\\1')",$message,$db_cvtimes);
	}

	if (strpos($message,'[/URL]') !== false || strpos($message,'[/url]') !== false) {
		$searcharray = array(
		"/\[url=(https?|ftp|gopher|news|telnet|mms|rtsp|thunder|ed2k|fs2you|flashget|qqdl)([^\[\s]+?)(\,(1)\/?)?\](.+?)\[\/url\]/eis",
		"/\[url\]www\.([^\[]+?)\[\/url\]/eis",
		"/\[url\](https?|ftp|gopher|news|telnet|mms|rtsp|thunder|ed2k|fs2you|flashget|qqdl)([^\[]+?)\[\/url\]/eis",
		"/\[url=([^\[\s]+?)(\,(1)\/?)?\](.+?)\[\/url\]/eis"
		);
		$replacearray = array(
		"cvurl('\\1','\\2','\\5','\\4','$allow[checkurl]')",
		"cvurl('\\1','','','','$allow[checkurl]')",
		"cvurl('\\1','\\2','','','$allow[checkurl]')",
		"cvurl('','\\1','\\4','\\3','$allow[checkurl]')"
		);
		$message = preg_replace($searcharray,$replacearray,$message);
	}
	$searcharray = array(
	"/\[fly\]([^\[]*)\[\/fly\]/is",
	"/\[move\]([^\[]*)\[\/move\]/is"
	);
	$replacearray = array(
	"<marquee width=90% behavior=alternate scrollamount=3>\\1</marquee>",
	"<marquee scrollamount=3>\\1</marquee>"
	);
	$message = preg_replace($searcharray,$replacearray,$message);

	if (strpos($message,"[quote]") !== false && strpos($message,"[/quote]") !== false) {
		$message = preg_replace("/\[quote\](.*?)\[\/quote\]/eis","qoute('\\1')",$message);
	}
	/* 去除quote中链接 */
	$quoteCode = "<blockquote class=\"blockquote3\"><div class=\"text\" style=\"padding:15px;\">";
	$quotePos =strpos($message, $quoteCode);/*fix 20110916*/
	if ($quotePos!== false){
		$endQuotePos = strpos($message, "</div></blockquote>",$quotePos);
		$startQuotePos = $quotePos + strlen($quoteCode) - 1;
		$quoteContent = substr($message,$startQuotePos ,$endQuotePos-$startQuotePos);
		$quoteContent = preg_replace('/(<br\s*\/?>\s*){2,}/', '<br>', $quoteContent);
		$quoteContent = preg_replace_callback('/<a\s+href=(\'|")([^\'"]+)(\'|")[^>]*>([^<]+)<\/a>/is', 'stripQuoteLinks', $quoteContent);
		$message = substr_replace($message,$quoteContent,$startQuotePos ,$endQuotePos-$startQuotePos);
	}

	if ($type == 'post') {
		$t = 0;
		while (strpos($message,'[table') !== false && strpos($message,'[/table]') !== false) {
			$message = preg_replace('/\[table(?:=(\d{1,3}(?:%|px)?)(?:,(#\w{6})?)?(?:,(#\w{6})?)?(?:,(\d+))?)?\](.*?)\[\/table\]/eis', "tablefun('\\5','\\1','\\2','\\3','\\4')",$message);
			if (++$t>4) break;
		}
		if ($allow['mpeg']) {
			$message=setAudio($message);
		} else{
			$message = preg_replace(
			array(
			"/\[mp3=[01]{1}\]([^\<\r\n\"']+?)\[\/mp3\]/is",
			"/\[wmv=[01]{1}\]([^\<\r\n\"']+?)\[\/wmv\]/is",
			"/\[wmv(?:=[0-9]{1,3}\,[0-9]{1,3}\,[01]{1})?\]([^\<\r\n\"']+?)\[\/wmv\]/is",
			"/\[rm(?:=[0-9]{1,3}\,[0-9]{1,3}\,[01]{1})\]([^\<\r\n\"']+?)\[\/rm\]/is"
			),
			"<img src=\"$imgpath/wind/file/music.gif\" align=\"absbottom\"> <a target=\"_blank\" href=\"\\1 \">\\1</a>",$message,$db_cvtimes
			);
		}
		if ($allow['iframe']) {
			$message = preg_replace("/\[iframe\]([^\[\<\r\n\"']+?)\[\/iframe\]/is","<IFRAME SRC=\\1 FRAMEBORDER=0 ALLOWTRANSPARENCY=true SCROLLING=YES WIDTH=97% HEIGHT=340></IFRAME>",$message,$db_cvtimes);
		} else {
			$message = preg_replace("/\[iframe\]([^\[\<\r\n\"']+?)\[\/iframe\]/is","Iframe Close: <a target=_blank href='\\1 '>\\1</a>",$message,$db_cvtimes);
		}
		strpos($message,'[s:') !== false && $message = showface($message);
	}
	/*
	if (is_array($phpcode_htm)) {
	foreach($phpcode_htm as $key => $value){
	$message = str_replace("<\twind_phpcode_$key\t>",$value,$message);
	}
	}
	*/
	return $message;
}
function post($code,$isShow=0) {
	if ($isShow==1) {
		return "<h6 class=\"f12 quoteTips\" style=\"border-bottom:0;\">以下为隐藏内容</h6><div style=\"border:1px dotted #eca46a;border-top:0;\" class=\"p10\">".str_replace('\\"','"',$code)."</div>";
	} else {
		return "<div class=\"f12 quoteTips\" style=\"margin:10px 0;\">此处为隐藏内容，回复就显示</div>";
	}
}
function size($size) {
	return "<font size=\"$size\">";
}
function cvurl($http,$url='',$name='',$ifdownload='',$checkurl) {
	$code_num=0;
	$name = str_replace('\\"','"',$name);
	if (!$url) {
		$url = "<a href=\"http://www.$http\" target=\"_blank\" $addjs>www.$http</a>";
	} elseif (!$name) {
		$url = "<a href=\"$http$url\" target=\"_blank\" $addjs>$http$url</a>";
	} elseif (!$http && $url) {
		if (strrpos($url, 'u.php?uid=') !== false) {
			preg_match_all('/^u.php\?uid=(\d*)$/i', $url, $uids);
			$uid = $uids[1][0];
			//	$url = $db_bbsurl.'/'.$url;
			$url = "<a class=\" _cardshow\" href=\"$url\" data-card-key=\"$name\" data-card-url=\"pw_ajax.php?action=smallcard&type=showcard&uid=".$uid."\" target=\"_blank\" $addjs>$name</a>";
		} elseif (strpos($url, 'ed2k:') === 0 || strpos($url, 'fs2you:') === 0 || strpos($url, 'flashget:') === 0 || strpos($url, 'thunder:') === 0 || strpos($url, 'qqdl:') === 0) {
			$url = "<a href=\"$url\" target=\"_blank\" $addjs>$name</a>";
		} else {
			$url = "<a href=\"http://$url\" target=\"_blank\" $addjs>$name</a>";
		}
	} elseif (!$ifdownload) {
		if (strrpos($url, 'u.php?uid=') !== false) {
			preg_match_all('/^u.php\?uid=(\d*)$/i', $url, $uids);
			$uid = $uids[1][0];
			$url = "<a class=\" _cardshow\" href=\"$http$url\" data-card-key=\"$name\" data-card-url=\"pw_ajax.php?action=smallcard&type=showcard&uid=".$uid."\" target=\"_blank\" $addjs>$name</a>";
		} else {
			$url = "<a href=\"$http$url\" target=\"_blank\" $addjs>$name</a>";
		}
	} else {
		$url = "<a class=\"down\" href=\"$http$url\" target=\"_blank\" $addjs>$name</a>";
	}
	return $url;
	//$code_htm[0][$code_num] = $url;
	//return "<\twind_code_$code_num\t>";
}
function cvpic($url,$type='',$picwidth='',$picheight='',$ifthumb='',$attDescrip='') {
	//global $db_bbsurl,$db_picpath,$attachpath,$db_ftpweb,$code_num,$code_htm,$webPageTitle;
	$lower_url = strtolower($url);
	strncmp($lower_url,'http',4)!=0 && $url = "$db_bbsurl/$url";
	if (strpos($lower_url,'login')!==false && (strpos($lower_url,'action=quit')!==false || strpos($lower_url,'action-quit')!==false)) {
		$url = preg_replace('/login/i','log in',$url);
	}
	$url = str_replace(array("&#39;","'"),'',$url);
	$turl = $url;
	$wopen = 0;
	$alt = '';
	if ($ifthumb) {
		if ($db_ftpweb && !strpos($url,$attachpath) !== false) {
			$picurlpath = $db_ftpweb;
		} else{
			$picurlpath = $attachpath;
		}
		if (strpos($url,$picurlpath) !== false) {
			$wopen = 1;
			$turl = str_replace($picurlpath, "$picurlpath/thumb", $url);
		}
	}
	//	$attDescrip = $attDescrip ? $attDescrip : $webPageTitle;
	//	$alt = "title=\"$attDescrip\" alt=\"$attDescrip\"";
	if ($picwidth || $picheight) {
		$wopen = !$wopen ? "if(this.parentNode.tagName!='A'&&this.width>=$picwidth)" : '';
		$onload = $styleCss = '';
		if ($picwidth) {
			$onload .= "if(is_ie6&&this.offsetWidth>$picwidth)this.width=$picwidth;";
			$styleCss .= "max-width:{$picwidth}px;";
		}
		if ($picheight) {
			//$onload .= "if(this.offsetHeight>'$picheight')this.height='$picheight';";
			$styleCss .= "max-height:{$picheight}px;";
		}
		$code = "<img src=\"$turl\" border=\"0\" onclick=\"$wopen window.open('$url');\" style=\"$styleCss\" onload=\"$onload\" $alt>";
	} else {
		$wopen = !$wopen ? "if(this.parentNode.tagName!='A'&&this.width>screen.width-461)" : '';
		$code = "<img src=\"$turl\" border=\"0\" onclick=\"$wopen window.open('$url');\" $alt>";
	}
	if ($type) {
		return $code;
	} else {
		$code_htm[-1][++$code_num] = $code;
		return "<\twind_code_$code_num\t>";
	}
}
function qoute($code) {
	$code = preg_replace('/(\[s:[^]]+\])+/', '[表情]', $code); //face
	$code = preg_replace('/(\[attachment=\d+\])+/', '[图片]', $code); //face
	return "<blockquote class=\"blockquote3\"><div class=\"text\" style=\"padding:15px;\">".str_replace('\\"','"',$code)."</div></blockquote>";
}
function stripQuoteLinks($matches){
	global $db_bbsurl;
	$url = trim($matches[2]);
	if (strpos($url,"$db_bbsurl/u.php?username") === 0) {
		return "<a href=\"$url\" target=\"_blank\">{$matches[4]}</a>";
	} else {
		return $matches[4];
	}
}
function tablefun($text, $width = '', $bgColor = '', $borderColor = '', $borderWidth = '') {
	global $tdcolor,$td_htm,$td_num;
	if (!preg_match("/\[tr\]\s*\[td(=(\d{1,2}),(\d{1,2})(,(\d{1,3}(%|px)?))?)?\]/", $text) && !preg_match("/^<tr[^>]*?>\s*<td[^>]*?>/", $text)) {
		return preg_replace("/\[tr\]|\[td(=(\d{1,2}),(\d{1,2})(,(\d{1,3}(%|px)?))?)?\]|\[\/td\]|\[\/tr\]/", '', $text);
	}
	if ($width && preg_match('/^(\d{1,3})(%|px)?$/', $width, $matchs)) {
		$unit = $matchs[2] ? $matchs[2] : 'px';
		$width = $unit == 'px' ? min($matchs[1], 600).'px' : min($matchs[1], 100).'%';
	} else {
		$width = '100%';
	}
	$tdStyle = '';
	$tableStyle = 'width:' . $width;
	$bgColor && $tableStyle .= ';background-color:' . $bgColor;
	$borderWidth && $tableStyle .= ';border-width:' . $borderWidth . 'px;border-style:solid';
	!$borderColor && $borderColor = $tdcolor;
	$tableStyle .= ';border-color:' . $borderColor;
	$tdStyle = ' style="border-color:' . $borderColor . '"';

	$text = preg_replace(
	array(
	'/(\[\/td\]\s*)?\[\/tr\]\s*/is',
	'/\[(tr|\/td)\]\s*\[td(=(\d{1,2}),(\d{1,2})(,(\d{1,3}(%|px)?))?)?\]/eis',
	'/\[tr\]/is',
	"/\\n/is"
	),
	array('</td></tr>',"tdfun('\\1','\\3','\\4','\\6','$tdStyle')","<tr><td{$tdStyle}>",'<br />'),
	trim(str_replace(array('\\"','<br />'),array('"',"\n"),$text))
	);
	return "<table class=\"read_form\" style=\"$tableStyle\" cellspacing=\"0\" cellpadding=\"0\">$text</table>";
}
function tdfun($t,$col,$row,$width,$tdStyle = '') {
	return ($t == 'tr' ? '<tr>' : '</td>').(($col && $row) ? "<td colspan=\"$col\" rowspan=\"$row\" width=\"$width\"{$tdStyle}>" : "<td{$tdStyle}>");
}
function showface($message) {
	//global $face,$db_cvtimes;
	//* include_once pwCache::getPath(D_P.'data/bbscache/postcache.php');
	//extract(pwCache::getData(D_P.'data/bbscache/postcache.php', false));
	$message = preg_replace("/\[s:(.+?)\]/eis","postcache('\\1')",$message);
	return $message;
}
$faces=array(
'default'=>array(
'name'=>'默认表情',
'child'=>array('2','3','4','5','6','7','8','9','10','11','12','13','14','15',),
),
'wangwang'=>array(
'name'=>'旺旺',
'child'=>array('152','151','150','149','148','147','146','145','144','143','142','141','140','139','138','137','136','135','134','133','132','131','130','129','128','127','126','125','124','123','122','121','120','119','118','117','116',),
),
);

function getface(){
	return array(
	'2'=>array('default/1.gif','',''),
	'3'=>array('default/2.gif','',''),
	'4'=>array('default/3.gif','',''),
	'5'=>array('default/4.gif','',''),
	'6'=>array('default/5.gif','',''),
	'7'=>array('default/6.gif','',''),
	'8'=>array('default/7.gif','',''),
	'9'=>array('default/8.gif','',''),
	'10'=>array('default/9.gif','',''),
	'11'=>array('default/10.gif','',''),
	'12'=>array('default/11.gif','',''),
	'13'=>array('default/12.gif','',''),
	'14'=>array('default/13.gif','',''),
	'15'=>array('default/14.gif','',''),
	'152'=>array('wangwang/9.gif','',''),
	'151'=>array('wangwang/8.gif','',''),
	'150'=>array('wangwang/7.gif','',''),
	'149'=>array('wangwang/6.gif','',''),
	'148'=>array('wangwang/5.gif','',''),
	'147'=>array('wangwang/4.gif','',''),
	'146'=>array('wangwang/37.gif','',''),
	'145'=>array('wangwang/36.gif','',''),
	'144'=>array('wangwang/35.gif','',''),
	'143'=>array('wangwang/34.gif','',''),
	'142'=>array('wangwang/33.gif','',''),
	'141'=>array('wangwang/32.gif','',''),
	'140'=>array('wangwang/31.gif','',''),
	'139'=>array('wangwang/30.gif','',''),
	'138'=>array('wangwang/3.gif','',''),
	'137'=>array('wangwang/29.gif','',''),
	'136'=>array('wangwang/28.gif','',''),
	'135'=>array('wangwang/27.gif','',''),
	'134'=>array('wangwang/26.gif','',''),
	'133'=>array('wangwang/25.gif','',''),
	'132'=>array('wangwang/24.gif','',''),
	'131'=>array('wangwang/23.gif','',''),
	'130'=>array('wangwang/22.gif','',''),
	'129'=>array('wangwang/21.gif','',''),
	'128'=>array('wangwang/20.gif','',''),
	'127'=>array('wangwang/2.gif','',''),
	'126'=>array('wangwang/19.gif','',''),
	'125'=>array('wangwang/18.gif','',''),
	'124'=>array('wangwang/17.gif','',''),
	'123'=>array('wangwang/16.gif','',''),
	'122'=>array('wangwang/15.gif','',''),
	'121'=>array('wangwang/14.gif','',''),
	'120'=>array('wangwang/13.gif','',''),
	'119'=>array('wangwang/12.gif','',''),
	'118'=>array('wangwang/11.gif','',''),
	'117'=>array('wangwang/10.gif','',''),
	'116'=>array('wangwang/1.gif','',''),
	);
}
function postcache($key) {
	$face = getface();
	is_array($face) && !$face[$key] && $face[$key] = current($face);
	if ($face[$key][2]) {
		return "<br /><img src=$imgpath/post/smile/{$face[$key][0]} /><br />{$face[$key][2]}<br />";
	} else {
		return "<img src=\"$imgpath/post/smile/{$face[$key][0]}\" />";
	}
}
/**
	 * sell 处理
	 *
	 * @param string $cost
	 * @param string $code
	 * @param int $isShow //是否显示，1显示
	 * @param int $qid //已经购买数
	 * @return string
	 */
function sell($cost,$code,$isShow=0,$qid=0) {
	//global $code_num;
	///$code_num++;
	list($creditvalue,$credittype) = explode(',',$cost);
	$creditvalue = (int)$creditvalue;
	if ($creditvalue < 0) {
		$creditvalue = 0;
	} elseif ($db_sellset['price'] && $creditvalue > $db_sellset['price']) {
		$creditvalue = $db_sellset['price'];
	}
	//if ($code_num == 1) {
		if ($isShow==0) {
			$printcode = "<div class=\"quoteTips f12 mb10\"><span class=\"mr10\">";
			//."此帖售价 $creditvalue 铜币,已有 $buyer_num 人购买"
			$printcode .= "此帖售价 $creditvalue 铜币 <a href=\"fukuan.php?qid=$qid\">点击付款查看</a>";
			$printcode .= "</span> </div>";
		}
		if ($isShow==0) {
			$printcode .= "<div class=\"mb10 f12 b\">购买后，将显示帖子中所有出售内容。</br>若发现会员采用欺骗的方法获取财富,请立刻举报,我们会对会员处以2-N倍的罚金,严重者封掉ID!</div> ";
		}
	//}
	//
	if ($isShow==1) {
		$printcode .= "<div style=\"border:1px dotted #cccccc;padding:5px;\">"
		. str_replace('\\"','"',$code)
		. "</div>";
	} else {
		$printcode .= "<div class=\"quoteTips f12\">此段为出售的内容，购买后显示" . "</div>";
	}
	//
	return $printcode;
}
///+++++++++++++++++++++++++++++++++++

/* 根据用户UID获得用户头像地址 */

function get_avatar_dir($uid) {
    global $setting;
    if ($setting['ucenter_open']) {
        return $setting['ucenter_url'] . '/avatar.php?uid=' . $uid . '&size=middle';
    } else {
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $avatar_dir = "data/avatar/" . $dir1 . '/' . $dir2 . '/' . $dir3 . "/small_" . $uid;

        if (file_exists($avatar_dir . ".jpg"))
            return SITE_URL . $avatar_dir . ".jpg";
        if (file_exists($avatar_dir . ".jepg"))
            return SITE_URL . $avatar_dir . ".jepg";
        if (file_exists($avatar_dir . ".gif"))
            return SITE_URL . $avatar_dir . ".gif";
        if (file_exists($avatar_dir . ".png"))
            return SITE_URL . $avatar_dir . ".png";
    }
//显示系统默认头像
    return SITE_URL . 'css/default/avatar.gif';
}

/* 伪静态和html纯静态可以同时存在 */

function url($var, $url='') {
    global $setting;
    if($setting['seo_on']){
    	$location =  $var . $setting['seo_suffix'];
    }else{
    	$location = '?' . $var . $setting['seo_suffix'];
    }
    if ($setting['seo_on']) {
        $rewritearray = array('question/view/', 'category/view/', 'category/list/', 'category/recommend/', 'user/space/', 'user/scorelist/');
        foreach ($rewritearray as $item) {
            if (false !== strpos($var, $item)) {
                $location = $var . $setting['seo_suffix'];
            }
        }
    }
    $location = urlmap($location, 2);
    if ($url)
        return SITE_URL . $location; //程序动态获取的，给question的model使用
    else
        return '<?=SITE_URL?>' . $location; //模板编译时候生成使用
}

/* url转换器，1为请求转换，就是把类似q-替换为question/view
  2为反向转换，就是把类似/question/view/替换为q-
 */

function urlmap($var, $direction=1) {
    $replaces = array(
        'q-' => 'question/view/',
        'c-' => 'category/view/',
        'l-' => 'category/list/',
        'r-' => 'category/recommend/',
        'u-' => 'user/space/',
        'us-' => 'user/scorelist/',
    );
    (2 == $direction) && $replaces = array_flip($replaces);
    return str_replace(array_keys($replaces), array_values($replaces), $var);
}

/**
 * random
 * @param int $length
 * @return string $hash
 */
function random($length=6, $type=0) {
    $hash = '';
    $chararr = array(
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz',
        '0123456789',
        '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'
    );
    $chars = $chararr[$type];
    $max = strlen($chars) - 1;
    PHP_VERSION < '4.2.0' && mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

function cutstr($string, $length, $dot = ' ...') {
    if (strlen($string) <= $length) {
        return $string;
    }
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    $strcut = '';
    if (strtolower(TIPASK_CHARSET) == 'utf-8') {
        $n = $tn = $noc = 0;
        while ($n < strlen($string)) {
            $t = ord($string[$n]);
            if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1;
                $n++;
                $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2;
                $n += 2;
                $noc += 2;
            } elseif (224 <= $t && $t <= 239) {
                $tn = 3;
                $n += 3;
                $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4;
                $n += 4;
                $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5;
                $n += 5;
                $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6;
                $n += 6;
                $noc += 2;
            } else {
                $n++;
            }
            if ($noc >= $length) {
                break;
            }
        }
        if ($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
    } else {
        for ($i = 0; $i < $length; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
        }
    }
    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    return $strcut . $dot;
}

function generate_key() {
    $random = random(20);
    $info = md5($_SERVER['SERVER_SOFTWARE'] . $_SERVER['SERVER_NAME'] . $_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_PORT'] . $_SERVER['HTTP_USER_AGENT'] . time());
    $return = '';
    for ($i = 0; $i < 64; $i++) {
        $p = intval($i / 2);
        $return[$i] = $i % 2 ? $random[$p] : $info[$p];
    }
    return implode('', $return);
}

/**
 * getip
 * @return string
 */
function getip() {
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    preg_match("/[\d\.]{7,15}/", $ip, $temp);
    $ip = $temp[0] ? $temp[0] : 'unknown';
    unset($temp);
    return $ip;
}

//格式化前端IP显示
function formatip($ip, $type=1) {
    if (strtolower($ip) == 'unknown') {
        return false;
    }
    if ($type == 1) {
        $ipaddr = substr($ip, 0, strrpos($ip, ".")) . ".*";
    }
    return $ipaddr;
}

function forcemkdir($path) {
    if (!file_exists($path)) {
        forcemkdir(dirname($path));
        mkdir($path, 0777);
    }
}

function cleardir($dir, $forceclear=false) {
    if (!is_dir($dir)) {
        return;
    }
    $directory = dir($dir);
    while ($entry = $directory->read()) {
        $filename = $dir . '/' . $entry;
        if (is_file($filename)) {
            @unlink($filename);
        } elseif (is_dir($filename) && $forceclear && $entry != '.' && $entry != '..') {
            chmod($filename, 0777);
            cleardir($filename, $forceclear);
            rmdir($filename);
        }
    }
    $directory->close();
}

function iswriteable($file) {
    $writeable = 0;
    if (is_dir($file)) {
        $dir = $file;
        if ($fp = @fopen("$dir/test.txt", 'w')) {
            @fclose($fp);
            @unlink("$dir/test.txt");
            $writeable = 1;
        }
    } else {
        if ($fp = @fopen($file, 'a+')) {
            @fclose($fp);
            $writeable = 1;
        }
    }
    return $writeable;
}

function readfromfile($filename) {
    if ($fp = @fopen($filename, 'rb')) {
        if (PHP_VERSION >= '4.3.0' && function_exists('file_get_contents')) {
            return file_get_contents($filename);
        } else {
            flock($fp, LOCK_EX);
            $data = fread($fp, filesize($filename));
            flock($fp, LOCK_UN);
            fclose($fp);
            return $data;
        }
    } else {
        return '';
    }
}

function writetofile($filename, &$data) {
    if ($fp = @fopen($filename, 'wb')) {
        if (PHP_VERSION >= '4.3.0' && function_exists('file_put_contents')) {
            return @file_put_contents($filename, $data);
        } else {
            flock($fp, LOCK_EX);
            $bytes = fwrite($fp, $data);
            flock($fp, LOCK_UN);
            fclose($fp);
            return $bytes;
        }
    } else {
        return 0;
    }
}

function extname($filename) {
    $pathinfo = pathinfo($filename);
    return strtolower($pathinfo['extension']);
}

function taddslashes($string, $force = 0) {
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = taddslashes($val, $force);
            }
        } else {
            $string = addslashes($string);
        }
    }
    return $string;
}

/* XSS 检测 */

function checkattack($reqarr, $reqtype='post') {
    $filtertable = array(
        'get' => '\'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)',
        'post' => '\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)',
        'cookie' => '\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)'
    );
    foreach ($reqarr as $reqkey => $reqvalue) {
        if (preg_match("/" . $filtertable[$reqtype] . "/is", $reqvalue) == 1) {
            print('Illegal operation!');
            exit(-1);
        }
    }
}

function tstripslashes($string) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = tstripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

function template($file, $tpldir = '') {
    global $setting;
    $tpldir = ('' == $tpldir) ? $setting['tpl_dir'] : $tpldir;
    $tplfile = TIPASK_ROOT . '/view/' . $tpldir . '/' . $file . '.html';
    $objfile = TIPASK_ROOT . '/data/view/' . $tpldir . '_' . $file . '.tpl.php';
    if ('default' != $tpldir && !is_file($tplfile)) {
        $tplfile = TIPASK_ROOT . '/view/default/' . $file . '.html';
        $objfile = TIPASK_ROOT . '/data/view/default_' . $file . '.tpl.php';
    }
    if (!file_exists($objfile) || (@filemtime($tplfile) > @filemtime($objfile))) {
        require_once TIPASK_ROOT . '/lib/template.func.php';
        parse_template($tplfile, $objfile);
    }
    return $objfile;
}

function timeLength($time) {
    $length = '';
    if ($day = floor($time / (24 * 3600))) {
        $length .= $day . '天';
    }
    if ($hour = floor($time % (24 * 3600) / 3600)) {
        $length .= $hour . '小时';
    }
    if ($day == 0 && $hour == 0) {
        $length = floor($time / 60) . '分';
    }
    return $length;
}

/* 验证码生成 */

function makecode($code) {
    $codelen = strlen($code);
    $im = imagecreate(50, 20);
    $font_type = TIPASK_ROOT . '/css/common/ninab.ttf';
    $bgcolor = ImageColorAllocate($im, 245, 245, 245);
    $iborder = ImageColorAllocate($im, 0x71, 0x76, 0x67);
    $fontColor = ImageColorAllocate($im, 0x50, 0x4d, 0x47);
    $fontColor2 = ImageColorAllocate($im, 0x36, 0x38, 0x32);
    $fontColor1 = ImageColorAllocate($im, 0xbd, 0xc0, 0xb8);
    $lineColor1 = ImageColorAllocate($im, 130, 220, 245);
    $lineColor2 = ImageColorAllocate($im, 225, 245, 255);
    for ($j = 3; $j <= 16; $j = $j + 3)
        imageline($im, 2, $j, 48, $j, $lineColor1);
    for ($j = 2; $j < 52; $j = $j + (mt_rand(3, 6)))
        imageline($im, $j, 2, $j - 6, 18, $lineColor2);
    imagerectangle($im, 0, 0, 49, 19, $iborder);
    $strposs = array();
    for ($i = 0; $i < $codelen; $i++) {
        if (function_exists("imagettftext")) {
            $strposs[$i][0] = $i * 10 + 6;
            $strposs[$i][1] = mt_rand(15, 18);
            imagettftext($im, 11, 5, $strposs[$i][0] + 1, $strposs[$i][1] + 1, $fontColor1, $font_type, $code[$i]);
        } else {
            imagestring($im, 5, $i * 10 + 6, mt_rand(2, 4), $code[$i], $fontColor);
        }
    }
    for ($i = 0; $i < $codelen; $i++) {
        if (function_exists("imagettftext")) {
            imagettftext($im, 11, 5, $strposs[$i][0] - 1, $strposs[$i][1] - 1, $fontColor2, $font_type, $code[$i]);
        }
    }
    header("Pragma:no-cache\r\n");
    header("Cache-Control:no-cache\r\n");
    header("Expires:0\r\n");
    if (function_exists("imagejpeg")) {
        header("content-type:image/jpeg\r\n");
        imagejpeg($im);
    } else {
        header("content-type:image/png\r\n");
        imagepng($im);
    }
    ImageDestroy($im);
}

/* 通用加密解密函数，phpwind、phpcms、dedecms都用此函数 */

function strcode($string, $auth_key, $action='ENCODE') {
    $key = substr(md5($_SERVER["HTTP_USER_AGENT"] . $auth_key), 8, 18);
    $string = $action == 'ENCODE' ? $string : base64_decode($string);
    $len = strlen($key);
    $code = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $k = $i % $len;
        $code .= $string[$i] ^ $key[$k];
    }
    $code = $action == 'DECODE' ? $code : base64_encode($code);
    return $code;
}

/* 日期格式显示 */

function tdate($time, $type = 3, $friendly=1) {
    global $setting;
    ($setting['time_friendly'] != 1) && $friendly = 0;
    $format[] = $type & 2 ? (!empty($setting['date_format']) ? $setting['date_format'] : 'Y-n-j') : '';
    $format[] = $type & 1 ? (!empty($setting['time_format']) ? $setting['time_format'] : 'H:i') : '';
    $timeoffset = $setting['time_offset'] * 3600 + $setting['time_diff'] * 60;
    $timestring = gmdate(implode(' ', $format), $time + $timeoffset);
    if ($friendly) {
        $time = time() - $time;
        if ($time <= 24 * 3600) {
            if ($time > 3600) {
                $timestring = intval($time / 3600) . '小时前';
            } elseif ($time > 60) {
                $timestring = intval($time / 60) . '分钟前';
            } elseif ($time > 0) {
                $timestring = $time . '秒前';
            } else {
                $timestring = '现在前';
            }
        }
    }
    return $timestring;
}

/* cookie设置和读取 */

function tcookie($var, $value=0, $life = 0) {
    global $setting;
    $cookiepre = $setting['cookie_pre'] ? $setting['cookie_pre'] : 't_';
    if (0 === $value) {
        return isset($_COOKIE[$cookiepre . $var]) ? $_COOKIE[$cookiepre . $var] : '';
    } else {
        $domain = $setting['cookie_domain'] ? $setting['cookie_domain'] : '';
        setcookie($cookiepre . $var, $value, $life ? time() + $life : 0, '/', $domain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
    }
}

/* 日志记录 */

function runlog($file, $message, $halt=0) {
    $nowurl = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
    $log = tdate($_SERVER['REQUEST_TIME'], 'Y-m-d H:i:s') . "\t" . $_SERVER['REMOTE_ADDR'] . "\t{$nowurl}\t" . str_replace(array("\r", "\n"), array(' ', ' '), trim($message)) . "\n";
    $yearmonth = gmdate('Ym', $_SERVER['REQUEST_TIME']);
    $logdir = TIPASK_ROOT . '/data/logs/';
    if (!is_dir($logdir))
        mkdir($logdir, 0777);
    $logfile = $logdir . $yearmonth . '_' . $file . '.php';
    if (@filesize($logfile) > 2048000) {
        $dir = opendir($logdir);
        $length = strlen($file);
        $maxid = $id = 0;
        while ($entry = readdir($dir)) {
            if (strexists($entry, $yearmonth . '_' . $file)) {
                $id = intval(substr($entry, $length + 8, -4));
                $id > $maxid && $maxid = $id;
            }
        }
        closedir($dir);
        $logfilebak = $logdir . $yearmonth . '_' . $file . '_' . ($maxid + 1) . '.php';
        @rename($logfile, $logfilebak);
    }
    if ($fp = @fopen($logfile, 'a')) {
        @flock($fp, 2);
        fwrite($fp, "<?PHP exit;?>\t" . str_replace(array('<?', '?>', "\r", "\n"), '', $log) . "\n");
        fclose($fp);
    }
    if ($halt)
        exit();
}

/* 翻页函数 */

function page($num, $perpage, $curpage, $operation) {
    global $setting;
    $multipage = '';
    $operation = urlmap($operation, 2);

    $mpurl = SITE_URL . $setting['seo_prefix'] . $operation . '/';
    ('admin' == substr($operation, 0, 5)) && ( $mpurl = 'index.php?' . $operation . '/');

    if ($num > $perpage) {
        $page = 10;
        $offset = 2;
        $pages = @ceil($num / $perpage);
        if ($page > $pages) {
            $from = 1;
            $to = $pages;
        } else {
            $from = $curpage - $offset;
            $to = $from + $page - 1;
            if ($from < 1) {
                $to = $curpage + 1 - $from;
                $from = 1;
                if ($to - $from < $page) {
                    $to = $page;
                }
            } elseif ($to > $pages) {
                $from = $pages - $page + 1;
                $to = $pages;
            }
        }
        $multipage = ($curpage - $offset > 1 && $pages > $page ? '<a  class="n" href="' . $mpurl . '1' . $setting['seo_suffix'] . '" >首页</a>' . "\n" : '') .
                ($curpage > 1 ? '<a href="' . $mpurl . ($curpage - 1) . $setting['seo_suffix'] . '"  class="n">上一页</a>' . "\n" : '');
        for ($i = $from; $i <= $to; $i++) {
            $multipage .= $i == $curpage ? "<strong>$i</strong>\n" :
                    '<a href="' . $mpurl . $i . $setting['seo_suffix'] . '">' . $i . '</a>' . "\n";
        }
        $multipage .= ( $curpage < $pages ? '<a class="n" href="' . $mpurl . ($curpage + 1) . $setting['seo_suffix'] . '">下一页</a>' . "\n" : '') .
                ($to < $pages ? '<a class="n" href="' . $mpurl . $pages . $setting['seo_suffix'] . '" >最后一页</a>' . "\n" : '');
    }
    return $multipage;
}

/* 过滤关键词 */

function checkwords($content) {
    global $setting, $badword;
    $status = 0;
    $text = $content;
    if (!empty($badword)) {
        foreach ($badword as $word => $wordarray) {
            $replace = $wordarray['replacement'];
            $content = str_replace($word, $replace, $content, $matches);
            if ($matches > 0) {
                '{MOD}' == $replace && $status = 1;
                '{BANNED}' == $replace && $status = 2;
                if ($status > 0) {
                    $content = $text;
                    break;
                }
            }
        }
    }
//$content = str_replace(array("\r\n", "\r", "\n"), '<br />', htmlentities($content));
    return array($status, $content);
}

/* http请求 */

function topen($url, $timeout = 15, $post = '', $cookie = '', $limit = 0, $ip = '', $block = TRUE) {
    $return = '';
    $matches = parse_url($url);
    $host = $matches['host'];
    $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;
    if ($post) {
        $out = "POST $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
//$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= 'Content-Length: ' . strlen($post) . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
        $out .= $post;
    } else {
        $out = "GET $path HTTP/1.0\r\n";
        $out .= "Accept: */*\r\n";
//$out .= "Referer: $boardurl\r\n";
        $out .= "Accept-Language: zh-cn\r\n";
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: $cookie\r\n\r\n";
    }
    $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
    if (!$fp) {
        return '';
    } else {
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        if (!$status['timed_out']) {
            while (!feof($fp)) {
                if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
                    break;
                }
            }
            $stop = false;
            while (!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                $return .= $data;
                if ($limit) {
                    $limit -= strlen($data);
                    $stop = $limit <= 0;
                }
            }
        }
        @fclose($fp);
        return $return;
    }
}

/* 发送邮件 */

function sendmail($touser, $subject, $message, $from = '') {
    global $setting;
    $toemail = $touser['email'];
    $tousername = $touser['username'];
    $message = <<<EOT
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset={TIPASK_CHARSET}">
		<title>$subject</title>
		</head>
		<body>
		hi, $tousername<br>
            $subject<br>
            $message<br>
		这封邮件由系统自动发送，请不要回复。
		</body>
		</html>
EOT;

    $maildelimiter = $setting['maildelimiter'] == 1 ? "\r\n" : ($setting['maildelimiter'] == 2 ? "\r" : "\n");
    $mailusername = isset($setting['mailusername']) ? $setting['mailusername'] : 1;
    $mailserver = $setting['mailserver'];
    $mailport = $setting['mailport'] ? $setting['mailport'] : 25;
    $mailsend = $setting['mailsend'] ? $setting['mailsend'] : 1;

    if ($mailsend == 3) {
        $email_from = empty($from) ? $setting['maildefault'] : $from;
    } else {
        $email_from = $from == '' ? '=?' . TIPASK_CHARSET . '?B?' . base64_encode($setting['site_name']) . "?= <" . $setting['maildefault'] . ">" : (preg_match('/^(.+?) \<(.+?)\>$/', $from, $mats) ? '=?' . TIPASK_CHARSET . '?B?' . base64_encode($mats[1]) . "?= <$mats[2]>" : $from);
    }

    $email_to = preg_match('/^(.+?) \<(.+?)\>$/', $toemail, $mats) ? ($mailusername ? '=?' . CHARSET . '?B?' . base64_encode($mats[1]) . "?= <$mats[2]>" : $mats[2]) : $toemail;
    ;

    $email_subject = '=?' . TIPASK_CHARSET . '?B?' . base64_encode(preg_replace("/[\r|\n]/", '', '[' . $setting['site_name'] . '] ' . $subject)) . '?=';
    $email_message = chunk_split(base64_encode(str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message))))));

    $headers = "From: $email_from{$maildelimiter}X-Priority: 3{$maildelimiter}X-Mailer: Tipask1.0 {$maildelimiter}MIME-Version: 1.0{$maildelimiter}Content-type: text/html; charset=" . TIPASK_CHARSET . "{$maildelimiter}Content-Transfer-Encoding: base64{$maildelimiter}";

    if ($mailsend == 1) {
        if (function_exists('mail') && @mail($email_to, $email_subject, $email_message, $headers)) {
            return true;
        }
        return false;
    } elseif ($mailsend == 2) {

        if (!$fp = fsockopen($mailserver, $mailport, $errno, $errstr, 30)) {
            runlog('SMTP', "($mailserver:$mailport) CONNECT - Unable to connect to the SMTP server", 0);
            return false;
        }
        stream_set_blocking($fp, true);

        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '220') {
            runlog('SMTP', "($mailserver:$mailport) CONNECT - $lastmessage", 0);
            return false;
        }

        fputs($fp, ($setting['mailauth'] ? 'EHLO' : 'HELO') . " Tipask\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
            runlog('SMTP', "($mailserver:$mailport) HELO/EHLO - $lastmessage", 0);
            return false;
        }

        while (1) {
            if (substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
                break;
            }
            $lastmessage = fgets($fp, 512);
        }

        if ($setting['mailauth']) {
            fputs($fp, "AUTH LOGIN\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 334) {
                runlog('SMTP', "($mailserver:$mailport) AUTH LOGIN - $lastmessage", 0);
                return false;
            }

            fputs($fp, base64_encode($setting['mailauth_username']) . "\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 334) {
                runlog('SMTP', "($mailserver:$mailport) USERNAME - $lastmessage", 0);
                return false;
            }

            fputs($fp, base64_encode($setting['mailauth_password']) . "\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 235) {
                runlog('SMTP', "($mailserver:$mailport) PASSWORD - $lastmessage", 0);
                return false;
            }

            $email_from = $setting['maildefault'];
        }

        fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from) . ">\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from) . ">\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 250) {
                runlog('SMTP', "($mailserver:$mailport) MAIL FROM - $lastmessage", 0);
                return false;
            }
        }

        fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail) . ">\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $toemail) . ">\r\n");
            $lastmessage = fgets($fp, 512);
            runlog('SMTP', "($mailserver:$mailport) RCPT TO - $lastmessage", 0);
            return false;
        }

        fputs($fp, "DATA\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 354) {
            runlog('SMTP', "($mailserver:$mailport) DATA - $lastmessage", 0);
            return false;
        }

        $headers .= 'Message-ID: <' . gmdate('YmdHs') . '.' . substr(md5($email_message . microtime()), 0, 6) . rand(100000, 999999) . '@' . $_SERVER['HTTP_HOST'] . ">{$maildelimiter}";

        fputs($fp, "Date: " . gmdate('r') . "\r\n");
        fputs($fp, "To: " . $email_to . "\r\n");
        fputs($fp, "Subject: " . $email_subject . "\r\n");
        fputs($fp, $headers . "\r\n");
        fputs($fp, "\r\n\r\n");
        fputs($fp, "$email_message\r\n.\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            runlog('SMTP', "($mailserver:$mailport) END - $lastmessage", 0);
        }
        fputs($fp, "QUIT\r\n");

        return true;
    } elseif ($mailsend == 3) {

        ini_set('SMTP', $mailserver);
        ini_set('smtp_port', $mailport);
        ini_set('sendmail_from', $email_from);

        if (function_exists('mail') && @mail($email_to, $email_subject, $email_message, $headers)) {
            return true;
        }
        return false;
    }
}

/* 取得一个字符串的拼音表示形式 */

function getpinyin($str, $ishead=0, $isclose=1) {
    if (!function_exists('gbk_to_pinyin')) {
        require_once(TIPASK_ROOT . '/lib/iconv.func.php');
    }
    if (TIPASK_CHARSET == 'utf-8') {
        $str = utf8_to_gbk($str);
    }
    return gbk_to_pinyin($str, $ishead, $isclose);
}

/* 得到一个分类的getcategorypath，一直到根分类 */

function getcategorypath($cid) {
    global $category;
    $item = $category[$cid];
    $dirpath = $item['dir'];
    while (true) {
        if (0 == $item['pid']) {
            break;
        } else {
            $item = $category[$item['pid']];
        }
        $dirpath = $item['dir'] . '/' . $dirpath;
    }
    return $dirpath;
}

/* 得到问题纯静态的存储路径 */

function getstaticurl($question) {
    global $setting, $category;
    $staticurl = $setting['static_url'];
    $repacearray = array(
        'typedir' => getcategorypath($question['cid']),
        'timestamp' => $question['time'],
        'Y' => date('Y', $question['time']),
        'M' => date('m', $question['time']),
        'D' => date('d', $question['time']),
        'qid' => $question['id'],
        'pinyin' => getpinyin($question['title']) . '_' . $question['id'],
        'py' => getpinyin($question['title'], 1) . '_' . $question['id'],
        'cc' => date('md', $question['time']) . '_' . md5($question['id']),
    );
    foreach ($repacearray as $search => $replace) {
        $staticurl = str_replace($search, $replace, $staticurl);
    }
    return $staticurl;
}

/* 数组类型，是否是向量类型 */

function isVector(&$array) {
    $next = 0;
    foreach ($array as $k => $v) {
        if ($k !== $next)
            return false;
        $next++;
    }
    return true;
}

/* 自己定义tjson_encode */

function tjson_encode($value) {
    switch (gettype($value)) {
        case 'double':
        case 'integer':
            return $value > 0 ? $value : '"' . $value . '"';
        case 'boolean':
            return $value ? 'true' : 'false';
        case 'string':
            return '"' . str_replace(
                            array("\n", "\b", "\t", "\f", "\r"), array('\n', '\b', '\t', '\f', '\r'), addslashes($value)
                    ) . '"';
        case 'NULL':
            return 'null';
        case 'object':
            return '"Object ' . get_class($value) . '"';
        case 'array':
            if (isVector($value)) {
                if (!$value) {
                    return $value;
                }
                foreach ($value as $v) {
                    $result[] = tjson_encode($v);
                }
                return '[' . implode(',', $result) . ']';
            } else {
                $result = '{';
                foreach ($value as $k => $v) {
                    if ($result != '{')
                        $result .= ',';
                    $result .= tjson_encode($k) . ':' . tjson_encode($v);
                }
                return $result . '}';
            }
        default:
            return '"' . addslashes($value) . '"';
    }
}

/* 是否是外部url */

function is_outer($url) {
    $findstr = $domain = $_SERVER["HTTP_HOST"];
    $words = explode('.', $domain);
    if (count($words) > 2) {
        array_shift($words);
        $findstr = implode('.', $words);
    }
    return false === strpos($url, $findstr);
}

/* html中的是否包含外部url */

function has_outer($content) {
    $contain = false;
    if (!function_exists('file_get_html')) {
        require_once(TIPASK_ROOT . '/lib/simple_html_dom.php');
    }
    $html = str_get_html($content);
    $ret = $html->find('a');
    foreach ($ret as $a) {
        if (is_outer($a->href)) {
            $contain = true;
            break;
        }
    }
    $html->clear();
    return $contain;
}

/* 过滤外部url */

function filter_outer($content) {
    if (!function_exists('file_get_html')) {
        require_once(TIPASK_ROOT . '/lib/simple_html_dom.php');
    }
    $html = str_get_html($content);
    $ret = $html->find('a');
    foreach ($ret as $a) {
        if (is_outer($a->href)) {
            $a->outertext = $a->innertext;
        }
    }
    $content = $html->save();
    $html->clear();
    return $content;
}

/* 内存是否够用 */

function is_mem_available($mem) {
    $limit = trim(ini_get('memory_limit'));
    if (empty($limit))
        return true;
    $unit = strtolower(substr($limit, -1));
    switch ($unit) {
        case 'g':
            $limit = substr($limit, 0, -1);
            $limit *= 1024 * 1024 * 1024;
            break;
        case 'm':
            $limit = substr($limit, 0, -1);
            $limit *= 1024 * 1024;
            break;
        case 'k':
            $limit = substr($limit, 0, -1);
            $limit *= 1024;
            break;
    }
    if (function_exists('memory_get_usage')) {
        $used = memory_get_usage();
    }
    if ($used + $mem > $limit) {
        return false;
    }
    return true;
}

//图片处理函数
/* 根据扩展名判断是否图片 */
function isimage($extname) {
    return in_array($extname, array('jpg', 'jpeg', 'png', 'gif', 'bmp'));
}

function image_resize($src, $dst, $width, $height, $crop=0) {
    if (!list($w, $h) = getimagesize($src))
        return "Unsupported picture type!";

    $type = strtolower(substr(strrchr($src, "."), 1));
    if ($type == 'jpeg')
        $type = 'jpg';
    switch ($type) {
        case 'bmp': $img = imagecreatefromwbmp($src);
            break;
        case 'gif': $img = imagecreatefromgif($src);
            break;
        case 'jpg': $img = imagecreatefromjpeg($src);
            break;
        case 'png': $img = imagecreatefrompng($src);
            break;
        default : return false;
    }
// resize
    if ($crop) {
        if ($w < $width or $h < $height) {
            rename($src, $dst);
            return true;
        }
        $ratio = max($width / $w, $height / $h);
        $h = $height / $ratio;
        $x = ($w - $width / $ratio) / 2;
        $w = $width / $ratio;
    } else {
        if ($w < $width and $h < $height) {
            rename($src, $dst);
            return true;
        }
        $ratio = min($width / $w, $height / $h);
        $width = $w * $ratio;
        $height = $h * $ratio;
        $x = 0;
    }
    $new = imagecreatetruecolor($width, $height);
// preserve transparency
    if ($type == "gif" or $type == "png") {
        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

    switch ($type) {
        case 'bmp': imagewbmp($new, $dst);
            break;
        case 'gif': imagegif($new, $dst);
            break;
        case 'jpg': imagejpeg($new, $dst);
            break;
        case 'png': imagepng($new, $dst);
            break;
    }
    return true;
}

function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $thumb_image_name = TIPASK_ROOT . $thumb_image_name;
    $imageType = image_type_to_mime_type($imageType);
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($image);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($image);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($image);
            break;
    }
    imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
    switch ($imageType) {
        case "image/gif":
            imagegif($newImage, $thumb_image_name);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage, $thumb_image_name);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage, $thumb_image_name);
            break;
    }
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
}

/**
 * 获取内容中的第一张图
 * @param unknown_type $string
 * @return unknown|string
 */
function getfirstimg(&$string) {
    preg_match("/<img.+?src=[\\\\]?\"(.+?)[\\\\]?\"/i", $string, $imgs);
    if (isset($imgs[1])) {
        return $imgs[1];
    } else {
        return "";
    }
}

function highlight($content, $words, $highlightcolor='red') {
    $wordlist = explode(" ", $words);
    foreach ($wordlist as $hightlightword) {
        if (strlen($content) < 1 || strlen($hightlightword) < 1) {
            return $content;
        }
        preg_match_all("/$hightlightword+/i", $content, $matches);
        if (is_array($matches[0]) && count($matches[0]) >= 1) {
            foreach ($matches[0] as $match) {
        $content = preg_replace("/$hightlightword/is", "<font color=red>\\0</font>;", $content);
            }
        }
    }
    return $content;
}

function do_post($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;
}

function get_url_contents($url) {
    if (ini_get("allow_url_fopen") == "1")
        return file_get_contents($url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function get_remote_image($url, $savepath) {
    ob_start();
    readfile($url);
    $img = ob_get_contents();
    ob_end_clean();
    $size = strlen($img);
    $fp2 = @fopen(TIPASK_ROOT . $savepath, "a");
    fwrite($fp2, $img);
    fclose($fp2);
    return $savepath;
}

?>