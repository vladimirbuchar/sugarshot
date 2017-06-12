$(document).ready(function(){
    $("a").attr("onclick","ajaxLinkLoad(this); return false;");
    window.onpopstate =  function(e) {
        var url = window.location.href;
        UrlLoad(url);
    }
});
function ajaxLinkLoad(el)
{
    var url = $(el).attr("href");
    UrlLoad(url);
}
function UrlLoad(url)
{
    if(url.indexOf("/res/") > -1)
    {
        window.location.href=url;
        return;
    }
    if(url.indexOf("javascript:") > -1)
    {
        url = url.replace("javascript:","");
        eval(url);
        return;
    }
    $.post(url, {pageAjax: true,langid: $("#LangId").val(),webid:$("#WebId").val()}, function (data) {
        var out = $.trim(data);
        $("#pageForm").html(out);
        $("a").attr("onclick","ajaxLinkLoad(this); return false;");
        history.pushState('data','',url);
    });
}
        