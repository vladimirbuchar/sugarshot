function AcceptCookies()
{
    $.cookie("cookiesAccept", true,30,'/');
    $("#cookies").hide();
}