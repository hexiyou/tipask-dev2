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
