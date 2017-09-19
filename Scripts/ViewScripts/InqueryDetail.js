    $(document).ready(function(){
        $("#OtherLang").change(function(){
            SaveTemplate(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/CssEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
    
        });
    

function SaveTemplate(publish)
{
    ShowLoading();
    var params = PrepareParametrs("settingInquery");
    var privileges = ReadUserPrivileges("userSecurity");
    params.Privileges = privileges;
    params.Id = $("#ObjectId").val();
    params.Publish = publish;
    var data = PrepareParametrs("inquryData");
    params.Data = data;
    var outId = CallPhpFunctionAjax("WebEdit","SaveIquery","POSTOBJECT",params);
    $("#ObjectId").val(outId);
    LoadData(outId,"inquery");
    HideLoading();
}
function WriteXmlData(xml)
            {

            xmlDoc = $.parseXML(xml);
                    $xml = $(xmlDoc);
                    WriteItem("InquryQuestion", $xml);
                    WriteItem("Answer1", $xml);
                    WriteItem("Answer2", $xml);
                    WriteItem("Answer3", $xml);
                    WriteItem("Answer4", $xml);
                    WriteItem("Answer5", $xml);
                    WriteItem("OtherItem", $xml);
                    WriteItem("MoreItem", $xml);
                    WriteItem("OtherText", $xml);
                    WriteItem("SendButtonText", $xml);
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