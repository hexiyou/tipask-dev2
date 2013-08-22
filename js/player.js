function player(id,url,width,height,type) {
	if (!IsElement('p_' + id)) {
		var player = document.createElement('div');
		player.id  = 'p_' + id;
		player.style.cssText = 'display:block;';
		switch(type){
			case "rmvb":
			case "rm":
				type="rm";
				break;
			case "wmv":
			case "avi":
			case "wma":
				type="wmv";
				break;
			case "swf":
				type="flash";
				break;
			case "mp3":
				url="u/images/mp3player.swf?soundFile="+url;
				type="flash";
				break;
			case "flv":
				type="flv";
				break;
			default:
				return false;
		}
		player.innerHTML = eval('player_' + type)(url.replace('"',''),width,height,'swf_' + id);
		setTimeout(function(){getObj(id).appendChild(player)},200);
	} else {
		if (is_ie) {
			try{document['swf_'+id].pause();}catch(e){}
		}
		var p = getObj(id);
		p.removeChild(p.lastChild);
	}
}
/*
*多媒体播放点击切换
*/
function toggleVideo(elem){
	addEvent(elem,"click",function(e){
		var e=e||window.event;
		if(e.preventDefault){
			e.preventDefault();
		}else{
			e.returnValue=false;
		}
		var id=elem.getAttribute("data-pid");
		var url=elem.getAttribute("data-url");
		var w=elem.getAttribute("data-width");
		var h=elem.getAttribute("data-height");
		var type=elem.getAttribute("data-type");
		if(!url){
			return false;
		}
		if(!elem.open){
			player('player_'+id,url,w,h,type);
			elem.open=true;
			elem.className="video_u";
			elem.innerHTML="收起";
		}else{
			elem.open=false;
			elem.className="video";
			elem.innerHTML="点击播放";
			if(getObj("p_player_"+id)){
				getObj("player_"+id).removeChild(getObj("p_player_"+id));
			}
		}
	})
	
}
function player_rm(url,width,height,id){
		var hash=Math.random().toString(36).substring(12);
		return '<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'+width+'" height="'+height+'">\
					<param name="autostart" value="true">\
					<param name="src" value="'+url+'">\
					<param name="controls" value="imagewindow,controlpanel">\
					<param name="console" value="media_'+hash+'">\
					<embed src="'+url+'" autostart="true" type="audio/x-pn-realaudio-plugin" controls="imagewindow,controlpanel"  width="'+width+'" height="'+height+'"></embed>\
					<div style="height:100%">需要安装realplayer播放器才能播放,请点击<a href="http://www.real.com/realplayer" target="_blank">这里</a>安装</div>\
				</object>';
}
function player_flash(url,width,height,id){
	if (is_ie) {
		return '<object classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" id="'+id+'" width="'+width+'" height="'+height+'">\
					<param name="src" value="'+url+'" />\
					<param name="autostart" value="true" />\
					<param name="loop" value="true" />\
					<param name="allownetworking" value="internal" />\
					<param name="allowscriptaccess" value="never" />\
					<param name="allowfullscreen" value="true" />\
					<param name="quality" value="high" />\
					<param name="wmode" value="transparent">\
				</object>';
	} else {
		return '<object type="application/x-shockwave-flash" data="'+url+'" width="'+width+'" height="'+height+'" id="'+id+'" style="">\
					<param name="movie" value="'+url+'">\
					<param name="allowFullScreen" value="true">\
					<param name="autostart" value="true" />\
					<param name="loop" value="true" />\
					<param name="allownetworking" value="internal" />\
					<param name="allowscriptaccess" value="never">\
					<param name="quality" value="high" />\
					<param name="wmode" value="transparent">\
					<div style="height:100%">您还没有安装flash播放器,请点击<a href="http://www.adobe.com/go/getflash" target="_blank">这里</a>安装</div>\
			    </object>';
	}
}
function player_wmv(url,width,height,id) {
	if (height<64) height = 64;
	return '<object classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6" width="'+width+'" height="'+height+'">\
				<param name="invokeURLs" value="0">\
				<param name="autostart" value="1">\
				<param name="url" value="'+url+'">\
				<embed src="'+url+'" autostart="1" type="application/x-mplayer2" width="'+width+'" height="'+height+'"/>\
			</object>';
}
function player_flv(url,width,height,id){
		return '<object type="application/x-shockwave-flash" data="images/vcastr3.swf" width="'+width+'" height="'+height+'" id="'+id+'" style="">\
					<param name="movie" value="images/vcastr3.swf">\
					<param name="FlashVars" value="xml=<vcastr><channel><item><source>'+url+'</source><duration></duration><title></title></item></channel></vcastr>"/>\
					<param name="allowFullScreen" value="true">\
					<param name="autostart" value="true" />\
					<param name="loop" value="true" />\
					<param name="allownetworking" value="internal" />\
					<param name="allowscriptaccess" value="never">\
					<param name="quality" value="high" />\
					<param name="wmode" value="transparent">\
					<div style="height:100%">您还没有安装flash播放器,请点击<a href="http://www.adobe.com/go/getflash" target="_blank">这里</a>安装</div>\
			    </object>';
	
}