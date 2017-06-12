$(document).ready(function(){
    var webCount = $("#web option").length;
    if (webCount == 2)
    {
        var options = $("#web option");
        var option = $(options[1]);
        option.attr("selected","selected");
        GetLangByWeb();
    }
});
function GetLangByWeb()
{
    ShowLoading();
    var data = CallPhpFunctionAjax('Admin','GetLangListByWeb','JSON',$("#web").val());
    $("#selectLang").addClass("dn");
    $("#noLang").addClass("dn");
    
    if (IsUndefined(data) || data == "")
    {
        
        $("#selectLang").addClass("dn");
        if ($("#web").val() > 0)
            $("#noLang").removeClass("dn");
    }
    else 
    {
        var html ="";
        for (key in data)
        {
            var value = data[key]["Id"];
            var langName = data[key]["LangName"];
            html += "<option value=\""+value+"\">"+langName+"</option>";
        }
        $("#lang").html(html);
        $("#selectLang").removeClass("dn");
        $("#noLang").addClass("dn");
    }
    HideLoading();
}
function AddLang()
{
    var webId = $("#web").val();
    Redirect("Settings","LangList","xadm",webId);
}
function OpenDefaultState()
{
    var params = new Array();
    var ar1 = new Array();
    ar1[0]= "SelectWebId";
    ar1[1]= $("#web").val();
    params[0] = ar1;
    var ar2 = new Array();
    ar2[0]= "SelectLangId";
    ar2[1]= $("#lang").val();
    params[1] = ar2;
    var href= "/xadm/WebEdit/Tree/"+$("#web").val()+"/"+$("#lang").val()+"/";
    var newHref = CallPhpFunctionAjax("UsersItem","GetDefaultState","POST",params);
    if (newHref !="") href = newHref;
    window.location.href=href;
}