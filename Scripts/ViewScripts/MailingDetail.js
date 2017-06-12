 $(document).ready(function(){
    $("#OtherLang").change(function(){
            Save(false,false);
             SetIgnoreExit(true);
                    CallPhpFunctionAjax("WebEdit", "ChangeLangVersion", "POST", null);
                    var selectLang = $(this).val();
                    window.location.href = "/xadm/WebEdit/MailingDetail/" + $("#WebId").val() + "/" + selectLang + "/" + $(" # ObjectId").val() + " / 0/";
    });
            
            });
                    function Save(publish, checkValid)
                    {
                            ShowLoading();
                            var params = PrepareParametrs("itemForm");
                            var nextItem = params.length;
                            var ar1 = new Array();
                            ar1[0] = "Publish";
                            ar1[1] = publish;
                            params[nextItem] = ar1;
                            nextItem++;
                            var ar2 = new Array();
                            ar2[0] = "Id";
                            ar2[1] = $("#ObjectId").val();
                            params[nextItem] = ar2;
                            nextItem++;
                            var privileges = ReadUserPrivileges("userSecurity");
                            var ar3 = new Array();
                            ar3[0] = "Privileges";
                            ar3[1] = privileges;
                            params[nextItem] = ar3;
                            nextItem++;
                            var mailingParametrs = PrepareParametrs("mailingParametrs");
                            var ar4 = new Array();
                            ar4[0] = "MailingParametrs";
                            ar4[1] = mailingParametrs;
                            params[nextItem] = ar4;
                            nextItem++;
                            var outId = CallPhpFunctionAjax("WebEdit", "SaveMailing", "POST", params);
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
                 var params = new Array();
                 var ar1 = new Array();
                 ar1[0] = "ObjectId";
                 ar1[1] = $("#ObjectId").val();
                 params[0] = ar1;
                 
                 var ar2 = new Array();
                 ar2[0] = "Email";
                 ar2[1] = $("#Email").val();
                 params[1] = ar2;
                 
                 var ar3 = new Array();
                 ar3[0] = "MailingGroup";
                 ar3[1] = $("#MailingGroup").val();
                 params[2] = ar3;
                 

                var ar4 = new Array();
                 ar4[0] = "MailSender";
                 ar4[1] = $("#UserMailing").val();
                 params[3] = ar4;
                 CallPhpFunctionAjax("WebEdit", "SendMailing", "POST", params);
             }
    
    