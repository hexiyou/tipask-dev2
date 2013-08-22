var userAgent = navigator.userAgent.toLowerCase();
var is_opera = userAgent.indexOf('opera') != -1 && opera.version();
var is_moz = (navigator.product == 'Gecko') && userAgent.substr(userAgent.indexOf('firefox') + 8, 3);
var is_ie = (userAgent.indexOf('msie') != -1 && !is_opera) && userAgent.substr(userAgent.indexOf('msie') + 5, 3);

function getbyid(id) {
	return document.getElementById(id);
}

Array.prototype.push = function(value) {
	this[this.length] = value;
	return this.length;
}


function _attachEvent(obj, evt, func) {
	if(obj.addEventListener) {
		obj.addEventListener(evt, func, false);
	} else if(obj.attachEvent) {
		obj.attachEvent("on" + evt, func);
	}
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

function checkall(name) {
	var e = is_ie ? event : checkall.caller.arguments[0];
	obj = is_ie ? e.srcElement : e.target;
	var arr = document.getElementsByName(name);
	var k = arr.length;
	for(var i=0; i<k; i++) {
		arr[i].checked = obj.checked;
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

function addMouseEvent(obj){
	var checkbox,atr,ath,i;
	atr=obj.getElementsByTagName("tr");
	for(i=0;i<atr.length;i++){
		atr[i].onclick=function(){
			ath=this.getElementsByTagName("th");
			checkbox=this.getElementsByTagName("input")[0];
			if(!ath.length && checkbox.getAttribute("type")=="checkbox"){
				if(this.className!="currenttr"){
					this.className="currenttr";
					checkbox.checked=true;
				}else{
					this.className="";
					checkbox.checked=false;
				}
			}
		}
	}
}

// editor.js
if(is_ie) document.documentElement.addBehavior("#default#userdata");

function setdata(key, value){
	if(is_ie){
		document.documentElement.load(key);
		document.documentElement.setAttribute("value", value);
		document.documentElement.save(key);
		return  document.documentElement.getAttribute("value");
	} else {
		sessionStorage.setItem(key,value);
	}
}

function getdata(key){
	if(is_ie){
		document.documentElement.load(key);
		return document.documentElement.getAttribute("value");
	} else {
		return sessionStorage.getItem(key) && sessionStorage.getItem(key).toString().length == 0 ? '' : (sessionStorage.getItem(key) == null ? '' : sessionStorage.getItem(key));
	}
}

function form_option_selected(obj, value) {
	for(var i=0; i<obj.options.length; i++) {
		if(obj.options[i].value == value) {
			obj.options[i].selected = true;
		}
	}
}


function setselect(selectobj, value) {
	var len = selectobj.options.length;
	for(i = 0;i < len;i++) {
		if(selectobj.options[i].value == value) {
			selectobj.options[i].selected = true;
		}
	}
}

function show(id, display) {
	if(!getbyid(id)) return false;
	if(display == 'auto') {
		getbyid(id).style.display = getbyid(id).style.display == '' ? 'none' : '';
	} else {
		getbyid(id).style.display = display;
	}
}

