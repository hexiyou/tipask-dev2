// tabs
function showTab(n1,n2){
	var h=document.getElementById("tab"+n1).getElementsByTagName("em");
	var d=document.getElementById("tab"+n1).getElementsByTagName("div");
	for(var i=0;i<h.length;i++){
		if(n2-1==i){
			h[i].className+=" up";
			d[i].className+=" block";
		}
		else {
			h[i].className=" ";
			d[i].className=" ";
		}
	}
}

// focus
var n = 0;
function Mea(value) {
	n = value;
	setBg(value);
	plays(value);
}
function setBg(value) {
	for (var i = 0; i < 5; i++)
		document.getElementById("t" + i + "").className = "bg";
	document.getElementById("t" + value + "").className = "active";
}
function plays(value) {
	try {
		with (hotpic) {
			filters[0].Apply();
			for (i = 0; i < 5; i++) i == value ? children[i].style.display = "block" : children[i].style.display = "none";
			filters[0].play();
		}
	}
	catch (e) {
		var d = document.getElementById("hotpic").getElementsByTagName("div");
		for (i = 0; i < 5; i++) i == value ? d[i].style.display = "block" : d[i].style.display = "none";
	}
}
function clearAuto() { clearInterval(autoStart) }
function setAuto() { autoStart = setInterval("auto(n)", 4000) }
function auto() {
	n++;
	if (n > 4) n = 0;
	Mea(n);
}
setAuto(); 
