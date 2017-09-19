function AddLink()
{
    var params = {Id: GetSelectTree("linkDialog2")};
    
    var seoUrl = CallPhpFunctionAjax("WebEdit", "GetSeoUrlById", "POSTOBJECT",params);
    $("#GoToPage").val(seoUrl);
}
function AddSource()
{
    if (activeTab == 1)
    {
        var selectData = GetSelectTree("linkDialog");
        var name = CallPhpFunctionAjax("WebEdit", "GetSelectedObjectName", "POSTOBJECT",{SelectedObject: selectData});
        $("#saveFolderName").html(name);
        $("#SaveTo").val(selectData);
    }
}
    $(document).ready(function(){
        $("#OtherLang").change(function(){
            Save(false,false);
            SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/FormEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
        $('#ActiveFrom').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        $('#ActiveTo').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        
    });
    function ShowForms()
    {
        if ($("#UseBootstrap").is(":checked"))
        {
            $("#FormMode").parent().parent().show();
        }
        else 
        {
            $("#FormMode").parent().parent().hide();
        }
        
        if ($("#SendAdminEmail").is(":checked"))
        {
            $("#EmailFrom").parent().parent().show();
            $("#FormEmailAdmin").parent().parent().show();
            $("#TextEmailAdmin").parent().parent().show();
        }
        else 
        {
            $("#EmailFrom").parent().parent().hide();
            $("#FormEmailAdmin").parent().parent().hide();
            $("#TextEmailAdmin").parent().parent().hide();
        }
        if ($("#SendCustomerEmail").is(":checked"))
        {
            $("#TextEmailCustomer").parent().parent().show();
        }
        else 
        {
            $("#TextEmailCustomer").parent().parent().hide();
        }
        if ($("#SendCustomerEmail").is(":checked") || $("#SendAdminEmail").is(":checked")) 
        {
            $("#GeneratePDF").parent().parent().show();
        }
        else 
        {
            $("#GeneratePDF").parent().parent().hide();
        }
        if ($("#GeneratePDF").is(":checked"))
        {
            $("#PDFTemplate").parent().parent().show();
            $("#GenerateFileName").parent().parent().show();
        }
        else 
        {
            $("#PDFTemplate").parent().parent().hide();
            $("#GenerateFileName").parent().parent().hide();
        }
        if ($("#SaveType").val() == "SaveToFolder")
        {
            $("#saveFolderName").parent().parent().show();
        }
        else 
        {
            $("#saveFolderName").parent().parent().hide();
        }
        if ($("#AfterSendFormAction").val() == "ShowText")
        {
            $("#EndText").parent().parent().show();
        }
        else 
        {
            $("#EndText").parent().parent().hide();
        }
        if ($("#AfterSendFormAction").val() == "GoToPage")
        {
            $("#GoToPage").parent().parent().show();
        }
        else 
        {
            $("#GoToPage").parent().parent().hide();
        }
        if ($("#FormMode").val()=="Wizard")
        {
            $("#ButtonNextText").parent().parent().show();
            $("#ButtonPrevText").parent().parent().show();
            $("#FormValidationMode").parent().parent().show();
            $("#ShowResult").parent().parent().show();
            $("#ResultTitle").parent().parent().show();
        }
        else
        {
            $("#ButtonNextText").parent().parent().hide();
            $("#ButtonPrevText").parent().parent().hide();
            $("#FormValidationMode").parent().parent().hide();
            $("#ShowResult").parent().parent().hide();
            $("#ResultTitle").parent().parent().hide();
        }
        
       
        
        
    }
            function WriteXmlData(xml)
            {
                xmlDoc = $.parseXML(xml);
                $xml = $(xmlDoc);
                WriteItem("ButtonSendForm", $xml);
                WriteItem("SendAdminEmail", $xml);
                
                WriteItem("EmailFrom", $xml);
                WriteItem("FormEmailAdmin", $xml);
                WriteItem("TextEmailAdmin", $xml);
                WriteItem("SendCustomerEmail", $xml);
                WriteItem("TextEmailCustomer", $xml);
                WriteItem("SaveType", $xml);
                WriteItem("AfterSendFormAction", $xml);
                WriteItem("EndText", $xml);
                WriteItem("UseBootstrap", $xml);
                WriteItem("ButtonCssClass", $xml);
                WriteItem("GoToPage", $xml);
                WriteItem("UseCaptcha", $xml);
                WriteItem("FormMode", $xml);
                WriteItem("ButtonNextText", $xml);
                WriteItem("ButtonPrevText", $xml);
                WriteItem("FormValidationMode", $xml);
                WriteItem("ShowResult", $xml);
                WriteItem("ResultTitle", $xml);
                WriteItem("GeneratePDF", $xml);
                WriteItem("PDFTemplate", $xml);
                WriteItem("GenerateFileName", $xml);
                WriteItem("SaveTo", $xml);
                WriteItem("ObjectName", $xml);
                WriteItem("SendFormAction", $xml);
                WriteItem("DetailStatisticTemplate", $xml);
                var saveTo = $("#SaveTo").val();
                if (saveTo != "")
                {
                    var name = CallPhpFunctionAjax("WebEdit", "GetSelectedObjectName", "POSTOBJECT",{SelectedObject: saveTo});
                    $("#saveFolderName").html(name);
                }
                ShowForms();
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




    function Save(publish, checkValid)
    {
ShowLoading();
    var params = PrepareParametrs("itemForm");
    params.Publish = publish;
    params.Id = $("#ObjectId").val();
    var privileges = ReadUserPrivileges("userSecurity");
    params.Privileges = privileges;
    var domainValues = PrepareParametrs("parametrs");
    params.Parametrs = domainValues;        
            
            
            var outId = CallPhpFunctionAjax("WebEdit", "SaveForm", "POSTOBJECT",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"form");
        HideLoading();
    }
    function LoadLinkDialogAddFormSave()
{
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialogSaveForm", "POSTOBJECT", null);
    $("#dialogComponentLink").html(html);
}
   function LoadLinkDialogAddLink()
{
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialogAddLinkForm", "POSTOBJECT", null);
    $("#dialogComponentLink2").html(html);
}

function SaveFormItemDetail(id,formItemId)
{
    var params = {Id:id, ItemId:formItemId,ItemValue: $("#"+formItemId).val() };
    CallPhpFunctionAjax("WebEdit", "UpdateFormStatisticItem", "POSTOBJECT",params);
    
}

