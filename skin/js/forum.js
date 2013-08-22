
var intervalId = null;

function cookiesave(n, v, mins, dn, path) {
    if (n) {
        if (!mins) mins = 24 * 60;
        if (!path) path = "/";
        var date = new Date();
        date.setTime(date.getTime() + (mins * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
        if (dn) dn = "domain=" + dn + "; ";
        document.cookie = n + "=" + v + expires + "; " + dn + "path=" + path;
    }
}
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

var intervalId = null;
function slideAd(id, nStayTime, sState, nMaxHth, nMinHth) {
    this.stayTime = nStayTime * 3500 || 3000;
    this.maxHeigth = nMaxHth || 200;
    this.minHeigth = nMinHth || 0;
    this.state = sState || "down";
    var obj = document.getElementById(id);
    if (intervalId != null) window.clearInterval(intervalId);
    function openBox() {
        var h = obj.offsetHeight;
        obj.style.height = ((this.state == "down") ? (h + 2) : (h - 2)) + "px";
        if (obj.offsetHeight > this.maxHeigth) {
            window.clearInterval(intervalId);
            intervalId = window.setInterval(closeBox, this.stayTime);
        }
        if (obj.offsetHeight < this.minHeigth) {
            window.clearInterval(intervalId);
            obj.style.display = "none";
        }
    }
    function closeBox() {
        slideAd(id, this.stayTime, "up", nMaxHth, nMinHth);
        //Ð´Èë
        cookiesave('Adcloseclick', 'Adcloseclick', '', '', '');
    }

    intervalId = window.setInterval(openBox, 10);
} 