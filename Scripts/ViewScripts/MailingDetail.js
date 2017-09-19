 $(document).ready(function(){
    $("#OtherLang").change(function(){
            Save(false,false);
             SetIgnoreExit(true);
                    CallPhpFunctionAjax("WebEdit", "ChangeLangVersion", "POSTOBJECT", null);
                    var selectLang = $(this).val();
                    window.location.href = "/xadm/WebEdit/MailingDetail/" + $("#WebId").val() + "/" + selectLang + "/" + $(" # ObjectId").val() + " / 0/";
    });
            
            });
                    function Save(publish, checkValid)
                    {
                            ShowLoading();
                            var params = PrepareParametrs("itemForm");
                            params.Publish = publish;
                            params.Id = $("#ObjectId").val();
                            
                            var privileges = ReadUserPrivileges("userSecurity");
                            params.Privileges = privileges;
                            
                            var mailingParametrs = PrepareParametrs("mailingParametrs");
                            params.MailingParametrs = mailingParametrs;
                            
                            var outId = CallPhpFunctionAjax("WebEdit", "SaveMailing", "POSTOBJECT", params);
                            $("#ObjectId").val(outId);
                            LoadData(outId,"mailing");
                            HideLoading();
                    }
            function WriteXmlData(xml)
            {
            xmlDoc = $.parseXML(xml);
                    $xml = $(xmlDoc);
                    WriteItem("Email", $xml);
                    WriteItem("MailingGroup", $xml);
                    WriteItem("UserMailing", $xml);
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
             function SendMailing()
             {
                 Save(true,true);
                 var params = {ObjectId:$("#ObjectId").val(), Email:$("#Email").val(),MailingGroup:$("#MailingGroup").val(),MailSender:$("#UserMailing").val()}
                 CallPhpFunctionAjax("WebEdit", "SendMailing", "POSTOBJECT", params);
             }
    
    