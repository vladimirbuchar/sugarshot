    $(document).ready(function(){
        $("#OtherLang").change(function(){
            SaveTemplate(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/CssEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
    
        });
    

function SaveTemplate(publish)
{
    ShowLoading();
    var params = PrepareParametrs("settingInquery");
    var nextItem = params.length;
    var privileges = ReadUserPrivileges("userSecurity");
    var ar1 = new Array();
    ar1[0] = "Privileges";
    ar1[1] = privileges;
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
    
    var data = PrepareParametrs("inquryData");
    var ar4 = new Array();
    ar4[0] = "Data";
    ar4[1] = data;
    nextItem++;
    params[nextItem] = ar4;
    
   var outId = CallPhpFunctionAjax("WebEdit","SaveIquery","POST",params);
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