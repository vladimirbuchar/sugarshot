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
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/TemplateDetail/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
});
function LoadDomainItems(domainValue)
{
    
    var data = CallPhpFunctionAjax("WebEdit","GetDomainItems","JSON",domainValue);
    var outHtml = "";
    for(var i = 0; i< data.length; i++)
    {
        var showName = data[i].ShowName;
        var Identificator = data[i].Identificator;
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"}\");return false;'>"+showName+" ("+Identificator+") </a><br />";
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"_label}\");return false;'>"+showName+" - label  </a><br />";
        outHtml += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"_result}\");return false;'>"+showName+" - result  </a><br />";
        outHtml += "<br />";
        
        //var formGroupHtml = '<div class=\"form-group\"><label for=\"{'+Identificator+'}\" class=\"control-label col-md-3\">{'+Identificator+'_label}:</label><div class=\"col-md-9\">{'+Identificator+'}</div></div>';
        
        //outHtml += "<a href='#' onclick='SetTextHtml(\"" +formGroupHtml +"\");return false;'>"+showName+" - pole formuláře  </a><br />";
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
    
     var ar = new Array();
    ar[0] = "Content";
    ar[1] = iframeData;
    params[nextItem] = ar;
    var privileges = ReadUserPrivileges("userSecurity");
    var ar1 = new Array();
    ar1[0] = "Privileges";
    ar1[1] = privileges;
    ar1[2] = false;
    nextItem++;
    params[nextItem] = ar1;
    var ar2 = new Array();
    ar2[0] = "Id";
    ar2[1] = $("#ObjectId").val();
    nextItem++;
    params[nextItem] = ar2;
    var ar3 = new Array();
    ar3[0] = "Publish";
    ar3[1] = publish;
    nextItem++;
    params[nextItem] = ar3;
    var ar4 = new Array();
    ar4[0] = "TemplateHeader";
    ar4[1] = $("#TemplateHeader").val();
    nextItem++;
    params[nextItem] = ar4;
    var ar5 = new Array();
    ar5[0] = "TemplateSettings";
    ar5[1] = PrepareParametrs("templateSettings");
    nextItem++;
    params[nextItem] = ar5;
    
    
    
    
    
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