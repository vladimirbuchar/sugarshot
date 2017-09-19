    var editor;
    $(document).ready(function(){
        var domainValue = $("#Domain").val();
        LoadDomainItems(domainValue);
        $("#Domain").change(function(){
            LoadDomainItems($(this).val());
        })
    editor = CodeMirror(document.getElementById("DataEditor"), {
          mode: "text/html",
          extraKeys: {"Ctrl-Space": "autocomplete"},
          value: $("#Data").val()
        });
        editor.on('change',function(cMirror){
            $("#Data").val(cMirror.getValue());
        });
        $("#OtherLang").change(function(){
             SaveTemplate(false);
             SetIgnoreExit(true);;
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/TemplateDetail/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
});
function LoadDomainItems(domainValue)
{
    
    var data = CallPhpFunctionAjax("WebEdit","GetDomainItems","JSONOBJECT",{domainValue: domainValue});
    var outHtml = "";
    for(var i = 0; i< data.length; i++)
    {
        var showName = data[i].ShowName;
        var Identificator = data[i].Identificator;
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"}\");return false;'>"+showName+" ("+Identificator+") </a><br />";
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"_label}\");return false;'>"+showName+" - label  </a><br />";
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"_result}\");return false;'>"+showName+" - result  </a><br />";
        outHtml += "<br />";
    }
    $("#domainItems").html(outHtml);
}
function SetTextHtml(item)
{
    editor.replaceSelection(item);
    
}
function SaveTemplate(publish)
{
 ShowLoading();
    
    var iframeData = $("#Data").val();
    var params = PrepareParametrs("settingTemplate");
    var nextItem = params.length;
    params.Content = iframeData;
    var privileges = ReadUserPrivileges("userSecurity");
    params.Privileges = privileges;
    params.Id = $("#ObjectId").val();
    params.Publish = publish;
    params.TemplateHeader = $("#TemplateHeader").val();
    params.TemplateSettings = PrepareParametrs("templateSettings");
    var outId = CallPhpFunctionAjax("WebEdit","SaveTemplate","LONGREQUEST",params);
    $("#ObjectId").val(outId);
    LoadData(outId,"template");
    HideLoading();

}



function WriteXmlData(xml)
{
    xmlDoc = $.parseXML(xml);
    $xml = $(xmlDoc);
    WriteItem("hideRelatedItems", $xml);
    WriteItem("hideMediaGallery", $xml);
    WriteItem("hideOthersObjects", $xml);
    WriteItem("hideAlernativeObjects", $xml);
}
function WriteItem(key, $xml)
{
    $xmlItem = $xml.find(key);
    var item = $("#" + key);
    var value = $xmlItem.text();
    if (item.is("input"))
    {
        var type = item.attr("type");
        if (type == "text" || type == "hidden")
        {
            item.val(value);
        }
        else if (type == "checkbox")
        {
            if (value == 1)
            {
                item.attr("checked", "checked");
            }
        }
    }
    else if (item.is("select"))
    {
        var selectBox = $("#" + key);
        selectBox.val(value);
        selectBox.change();
    }
    else if (item.is("textarea"))
    {
        item.val(value);
    }
}