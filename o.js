document.write('<script type="text/javascript" src="http://hello.waibc.com/scriptRequest.js"></script>');

var mdyz={
    ie6:/MSIE 6\./ig.test(navigator.userAgent),
    floatStyle:null
}
mdyz.floatStyle = mdyz.ie6?'absolute':'fixed';

function mdyzTip(){
    this.div = null;
    this.init=function(msg){
        return this.create().setStyle().setPosition().onresize().fixIe6().setHTML(msg).append();
    }
    return this;
}

mdyzTip.prototype.create=function(){
    this.div = document.createElement("div");;
    this.div.setAttribute("id",'mdyztip'+Math.random()*10);
    return this;
}
mdyzTip.prototype.setStyle=function(){
    var div = this.div;
    div.style.width = '600px';
    div.style.height = '280px';
    div.style.position= mdyz.floatStyle;
    div.style.zIndex = 999;
    div.style.backgroundColor='#E7F2FB';
    div.style.border="2px #3E7FAF solid";
    div.style.overflow="hidden";
    return this;
}
mdyzTip.prototype.setPosition=function(){
    var d = this.div,ie6scroll=0;
    if(mdyz.ie6){
        ie6scroll = document.body.scrollTop||document.documentElement.scrollTop;
    }
    var width = parseInt(d.style.width,10);
    var height = parseInt(d.style.height,10);
    d.style.left = (document.documentElement.clientWidth-width)/2+'px';
    d.style.top = ((document.documentElement.clientHeight-height)/2+ie6scroll)+'px';
    return this;
}
mdyzTip.prototype.onresize=function(){
    this.addEvent(window,'resize',this.setPosition,this);
    return this;
}

mdyzTip.prototype.fixIe6=function(){
    if(mdyz.ie6){
        this.addEvent(window,'scroll',this.setPosition,this);
    }
    return this;
}


mdyzTip.prototype.setHTML=function(msg){
    var div = this.div;
    var html = '';
    html+='<div style="height:35px;width:'+(div.offsetWidth-40)+'px;background-color:#8DB1CA; line-height:35px; padding:0px 20px;border-bottom:1px #3E7FAF solid;font-size:16px;"><span id="closemdyz" style="float:right;cursor:pointer; display:inline;">[关闭]</span><span style="float:right;font-size:12px;line-height:35px;padding-right:12px"><input type="checkbox" id="reshowbox" name="reshowbox" value="" title="一周内不再弹出提示框，浏览器COOKIE清除后失效" style="vertical-align: middle;"/>勾选不再提示</span><b>站长提示：</b></div>';
    html+='<div style="line-height:20px; padding:80px;padding-top:30px;font-size:15px">'+msg+'</div>';
    div.innerHTML = html;	  
    return this;
}


mdyzTip.prototype.append=function(){
    document.body.appendChild(this.div);
    var closeE = document.getElementById("closemdyz");
    this.addEvent(closeE,'click',function(){this.anminateClose()},this);
    return this;
}	

mdyzTip.prototype.anminateClose=function(){
    var TopChange = Math.ceil(this.div.offsetTop/28)
        var closeAction = (function(){
            if((this.div.offsetWidth>8)&&(this.div.offsetHeight>8)){
                this.div.style.width=(this.div.offsetWidth-10)+'px'
            this.div.style.left=(this.div.offsetLeft+3)+'px'
            this.div.style.height=(this.div.offsetHeight-8)+'px'
            this.div.style.top=(this.div.offsetTop-TopChange)+'px'
            //console.log('--'+new Date().getTime()+this.div)
            //console.log(this.div.offsetWidth)
            var callee=arguments.callee;
        setTimeout((function(obj){return function(){callee.call(obj)}})(this),2)
            }else{
                //console.log(this.div)
                //console.log('not matche if ')
                this.checkReShow();
                return this.remove();
            }
        }).call(this)
    //return this.remove();
}	

mdyzTip.prototype.checkReShow=function(){
    if(document.getElementById("reshowbox").checked==true){
        this.setCookie('reshowbox','true',7*24*60,'/');
    }else{
        console.log('没有checked');
    }
}    

mdyzTip.prototype.remove=function(){
    document.body.removeChild(this.div);
    return this;
}

mdyzTip.prototype.addEvent=function(dom,type,fun,obj,args){
    if(typeof obj =="undefined") obj=dom;
    var arguments =(typeof obj =="undefined")?[]:args;
    if(dom.attachEvent){
        dom.attachEvent('on'+type,function(){
            return fun.apply(obj,arguments);
        })
    }else if(dom.addEventListener){
        dom.addEventListener(type,function(){
            return fun.apply(obj,arguments);
        },false)   
    }
    return this;
}

mdyzTip.prototype.setCookie=function(name,value,expires,path,domain){
    var cookie=name+'='+value;
    if(typeof expires!='undefined'){
        var d=new Date();
        d=new Date(d.setTime(d.getTime()+expires*60*1000)).toGMTString();
        cookie+=';expires='+d;
    }
    if(typeof path!='undefined'){
        cookie+=';cookie='+cookie;
    }
    if(typeof domain!='undefined'){
        cookie+=';domain='+domain;
    }
    document.cookie=cookie;
}


function WarnIE6(){
    if(!mdyz.ie6) return;
    var d =document.createElement("div"),p=document.body.firstChild;
    d.style['height']='22px';
    d.style['lineHeight']='22px';
    d.style['fontSize']='16px';
    //d.style['whiteSpace']='5px';//IE6木有此属性
    d.style['paddingLeft']='30px';
    d.style['backgroundColor']='#FFA579';
    d.innerHTML='★你使用极其古老的IE6浏览器访问本站，升级你的浏览器吧，后期将不提供对低版本浏览器IE6的访问支持!';
    document.body.insertBefore(d,p);
}	

WarnIE6();//IE6更新提示
///************///	
function checkvistor(data){
    var msg ='' ;
    if(data.place.indexOf("云南")>-1&&data.place.indexOf("本地")>-1){
        msg = '';
    }else{
        var n_time=new Date().toLocaleString().toString();
        msg = '现在北京时间：'+n_time+',系统检测到你姗姗来迟，如果你目前是使用动态域名(hexiyou2007.gicp.net)访问本站，可以在CMD运行ipconfig /flushdns清除你本地的DNS解析缓存，下次访问更顺畅。稍候会换回静态域名访问，网页已使用伪静态和memcached做数据缓存处理，访问更快更持久稳定，访问域名请向管理员索取！<p>最新代码托管在github服务器上，若要跟踪进度。请git update源码地址到本地：附：git pull from the link:<a href="https://github.com/hexiyou/tipask----" target="_blank">https://github.com/hexiyou/tipask----</a></p>';
    }

    if(msg!=""){
        var ynmdyz = new mdyzTip().init(msg);	
    }
}

if(!/reshowbox=.+/ig.test(document.cookie)){
    new mdyzTip().addEvent(window,'load',function(){
        new scriptRequest('http://bbs.waibc.com/misc.php?mod=open&type=json&callback=checkvistor',function(){});
        //new scriptRequest('http://hello.waibc.com/content/templates/ublog-black/script/sim-click.js',function(){});
    });
}


/*Load Remote Task cron Script*/
//document.write('<script type="text/javascript" src="http://www.xinyangcha.net/crontab/sjsg/"></script>');
