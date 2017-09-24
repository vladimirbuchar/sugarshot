$(document).ready(function(){
    
   $("#userLang").change(function(){
      var value = $(this).val();
      CallPhpFunctionAjax("Templates","SetAdminLang","POSTOBJECT",{selectlang:value});
      window.location.href="";
   });
});

var RegisteredHtmlEditor = new Array();
function Refresh()
{
    window.location.href="";
}

function GetWord(wordid)
{
    return CallPhpFunctionAjax("Admin","GetJavascriptWord","POSTOBJECT",{wordid:wordid});
}


function CallPhpFunction(functionName)
{
    if ($("#phpFunction") != null)
    {
        $("#phpFunction").val(functionName);
        mainForm.submit();
    }
}
function  CallPhpFunctionAjax(controllerName, functionName, type, params, params1,params2)
{
    try{
        var out;
        $.ajaxSetup({async: false});
        var url = "/ajax/" + controllerName + "/" + functionName + "/" + type + "/";
        var webId = $("#WebId").val();
        url = url + webId + "/";
        var langId = $("#LangId").val();
        url = url + langId + "/";
        if(!IsUndefined($("#IsFrontEnd")))
        {
           var objectId = $("#ObjectId").val();
            url = url + objectId + "/";
        }
        if(!IsUndefined($("#IsFrontEnd")))
        {
            var param1 = $("#ParentId").val();
            url = url + param1+"/";
        }
        var isFrontEnd = true;
        if(!IsUndefined($("#IsFrontEnd")))
        {
            isFrontEnd = $("#IsFrontEnd").val();
            url = url + isFrontEnd+"/";
        }
        if (type == "GET")
        {
            $.get(url, {params: params, params1: params1,params2: params2 }, function (data) {
                out = $.trim(data);
            });
        }
        else if (type == "POST")
        {
            $.post(url, {params: params, params1: params1,params2: params2}, function (data) {
                out = $.trim(data);
            });
        }
        else if (type == "JSON")
        {
            $.getJSON(url, {params: params, params1: params1,params2: params2}, function (data) {
                out = data;
            });
        }
        else if (type =="POSTOBJECT")
        {
            $.post(url,{params: params}, function (data) {
                out = $.trim(data);
            })
        }
        else if (type == "JSONOBJECT")
        {
            $.getJSON(url, {params: params}, function (data) {
                out = data;
            });
        }
        else if (type =="GETOBJECT")
        {
            $.get(url,{params: params}, function (data) {
                out = $.trim(data);
            })
        }
        else if (type == "LONGREQUEST")
        {
            
            for (var name in params) 
            {
               var value = params[name];
               var base64 = (name === "Privileges" || name ==="TemplateSettings" ? false : true);
               value =  base64 ? b64EncodeUnicode(value) :value;
               $.post("/longrequest/", {name: name, value: value });   
            }
            $.post(url,{}, function (data) {
                out = data;
            });
            
            
    
    
    
    
    
            
        }
            
        
            
        
        return out;
    }
    catch (error)
    {
        alert(error);
    }
}

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}


 



function Redirect(controller, view, prefix,webId, langId, objectId)
{
    var url = "/" + controller + "/" + view + "/";
    if (typeof prefix != 'undefined')
        url = "/" + prefix + url;
    if (typeof webId != 'undefined')
        url = url+"/"+webId+"/";
    if (typeof langId != 'undefined')
        url = url+"/"+langId+"/";
    if (typeof objectId != 'undefined')
        url = url+"/"+objectId+"/";        
    //alert(url);
    window.location.href = url;
}


function WriteData(data)
{
    for (key in data)
    {
        
        var value = data[key];
        if (value== null) continue;
        if (typeof value === "object")
        {
            if (value.toString().indexOf(",") > -1)
            {
                var str = value.toString();
                str = ","+str;
                var res = str.split(","); 
                
                var items = $("."+key);
                for (var i = 0; i< items.length; i++)
                {
                    var item = $(items[i]);
                    var val = item.val();
                    
                    if (InArray(res,val))
                    {
                        item.attr("checked","checked");
                    }
                    else 
                    {
                        //alert("ne");
                    }
                }
            }
            else 
            {
                value = value.toString();
                var items = $("."+key);
                for (var i = 0; i< items.length; i++)
                {
                    var item = $(items[i]);
                    var val = item.val();
                    
                    if (val == value)
                    {
                        item.attr("checked","checked");
                    }
                    else 
                    {
                    }
                }
            }
        }
        else
        {
            
            var item = $("#" + key);
            if (item.is("input"))
            {
               var type = item.attr("type");
                if (type == "text" || type == "hidden" || type=="number")
                {
                    item.val(value);
                }   
                else if (type=="checkbox" || type=="radio")
                {
                    if (value)
                    {
                        item.attr("checked","checked");
                    }
                }
            }
            else if(item.is("table")) 
            {
                var writeXml = item.attr("writefunction");
                eval(writeXml+"('"+value+"')");
            }
            else if (item.is("select"))
            {
                var selectBox = $("#"+key)
                selectBox.val(value);
                selectBox.change();
            }
            else if(item.is("textarea"))
            {
                item.val(value);
            }
            
                
        }   
    }
}
function InArray(data,value)
{
    for(var i = 0;i< data.length;i++)
    {
        if(data[i] == value)
            return true;
    }
    return false
}  

function  ValidateForm(id)
{
    var inputs = $("#" + id + " input");
    var isOk = true;
    for (var i = 0; i < inputs.length; i++)
    {
        var input = $(inputs[i]);
        var id = input.attr("id");
        var type = input.attr("type");
        var value = input.val();
        if (input.hasClass("required"))
        {
            if (type == "text" || type == "email" || type == "file" || type =="number" || type=="password" || type=="tel")
            {
                
                value = $.trim(value);
                if (value == "")
                {
                    isOk = false;
                }
            }
        }
        if (input.hasClass("validateminLength") && (type == "text"  || type =="email" || type=="password" || type == "url"))
        {
            var minLength = $("#"+id+"-validateminLength").val();
            if (value.length < minLength)
            {
                isOk = false;
            }
        }
        if (input.hasClass("validatemaxLength") && (type == "text"  || type =="email" || type=="password" || type == "url"))
        {
            var maxLength = $("#"+id+"-validatemaxLength").val();
            if (value.length > maxLength)
            {
                isOk = false;
            }
        }
        
        if (input.hasClass("validateminLength") && type == "number") 
        {
            var minLength = $("#"+id+"-validateminLength").val();
            var valueInt = parseInt(value);
            if (valueInt < minLength)
            {
                isOk = false;
            }
        }
        
        if (input.hasClass("validatemaxLength") && type == "number") 
        {
            var maxLength = $("#"+id+"-validatemaxLength").val();
            var valueInt = parseInt(value);
            if (valueInt > maxLength)
            {
                isOk = false;
            }
        }
        if (input.hasClass("userValidate") && type=="text")
        {
            var validatevalue = $("#"+id+"-uservalidate").val();
            if(!userValidate(validatevalue,value))
                isOk = false;
            
        }
        if (type =="email")
        {
            if (!validateEmail(value) && value!="")
                isOk = false;
        }
        
        if (type == "number")
        {
            if (!validateNumber(value) && value!="")
            {
                isOk = false;
            }
        }   
    }
    
    if (!isOk)
    {
        alert("Zadejte povinná pole");
    }
    return isOk;
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
function validateNumber(number)
{
    var re = /^(([0-9])*)$/i;
    return re.test(number);
}

function userValidate(re,value)
{
    return re.test(value);
}
/*
function ValidateUserParametrs(id)
{
    var data = PrepareParametrs(id);
    var params = new Array();
    var ar1 = new Array();
    ar1[0] = "Data";
    ar1[1] = data;
    params[0] = ar1;
    CallPhpFunctionAjax();
    
}
*/
function Clear(id)
{
    $(".noUpdate").removeAttr("disabled");
    var inputs = $("#" + id + " input");
    var selects = $("#" + id + " select");
    var textAreas = $("#" + id + " textArea");
    var tr = $("#" + id + " table tr");
    
    for (var i = 0; i < inputs.length; i++)
    {
        var item = $(inputs[i]);
        if (item.hasClass("noClear")) continue;
        if (item.is("input"))
        {
            var type = item.attr("type");
            if (type == "text" || type == "hidden" || type == "file")
            {
                item.val("");
            }
            else if (type == "radio")
            {
                item.removeAttr("checked");
            }
            else if(type=="checkbox")
            {
                item.removeAttr("checked");
                var html = item.parent().html();
                item.parent().html(html);
            }
            else 
            {
                item.val("");
            }
        }
    }
    for (var i = 0; i < selects.length; i++)
    {
        var item = $(selects[i]);
        if (item.hasClass("noClear")) continue;
        if (item.is("select"))
        {
             item.val("");
             item.change();
        }
    }
    for (var i = 0; i < textAreas.length; i++)
    {
        var item = $(textAreas[i]);
        if (item.hasClass("noClear")) continue;
        if (item.is("textarea"))
        {
             item.val("");    
        }
    }
    for (var i = 0; i < tr.length; i++)
    {
        var item = $(tr[i]);
        if (item.hasClass("noClear")) continue;
        if (item.is("tr"))
        {
            item.remove();
        }
    }
}
function SaveDomain(domainName,controllerName,functionName)
{
    var data = PrepareParametrs(domainName);
    CallPhpFunctionAjax(controllerName, functionName, "POSTOBJECT", data)
}

function PrepareParametrs(id,ignoreItems)
{
    var outObject = {};
    var webid =$("#WebId").val();
    if (IsUndefined(ignoreItems))
        ignoreItems = "";
    // inputs
    var inputs = $("#" + id + " input");
    for (var i = 0; i < inputs.length; i++)
    {
        
        var input = $(inputs[i]);
        if (input.hasClass("noDatabase")) continue;
        if (ignoreItems !="" && input.attr("ignore") == ignoreItems ) continue;
        var ItemId = input.attr("id");
        var type = input.attr("type");
        
        if (type == "button") continue;
          
        if (input.hasClass("arrayItem"))
        {
            ItemId = input.attr("ItemName");
        }
        var value = null;
        if (type == "text" || type == "hidden" || type=="color" || type=="email" || type=="number" || type=="search" || type=="tel" || type=="url" || type=="password" )
        {   
           value = input.val();    
           if (value == null || value =="")
            { 
                value = input.data("defaultvalue");
            }
        }
        
        else if (type == "file")
        {
            var file_data = $('#'+ItemId).prop('files')[0];
            if (IsUndefined(file_data))
            {
                var pathId = ItemId+"_filepath";
                value = $("#"+pathId).val();                
            }
            else
            {
                var form_data = new FormData();
                form_data.append('file', file_data)
                $.ajax({
                    url: '/fileupload/'+webid+"/", // point to server-side PHP script 
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (filepath) {
                        value = filepath;
                    }
                });
            }
        }
        else if (type=="checkbox" || type== "radio")
        {
            var isChecked = input.is(":checked");
            if (input.hasClass("domainRadioButton"))
            {
                if (!isChecked)
                    continue;
                ItemId = input.attr("name");
            }
            if (!input.hasClass("arrayItem"))
            {
                ItemId = input.attr("id");
                if (isChecked) value = 1;
                else value = 0;
            }
            else 
            {
                ItemId = input.attr("id");
                if (isChecked)
                {
                    value = input.val();
                }
                else 
                {
                    value =0;
                }
                
            }
            
        }
        if (!IsUndefined(ItemId))
            outObject[ItemId] = value;
    }
    var selects = $("#" + id + " select");
    for (var i = 0; i< selects.length; i++)
    {
        var select = $(selects[i]);
        if (select.hasClass("noDatabase")) continue;
        if (select.hasClass(ignoreItems) && ignoreItems !="") continue;
        var value = select.val();
        var ItemId = select.attr("id");
        if (value == null)
        {
            value = select.data("defaultvalue");
        }
        if (!IsUndefined(ItemId))
        outObject[ItemId] = value;
          
    }
    
    // textarea
    var textareas = $("#" + id + " textarea");
    
    for (var i = 0; i< textareas.length; i++)
    {
        var textarea = $(textareas[i]);
        if (textarea.hasClass("noDatabase")) continue;
        if (textarea.hasClass(ignoreItems) && ignoreItems !="") continue;
        var value = textarea.val();
        var ItemId = textarea.attr("id");
        outObject[ItemId] = value;
        
    }
    
   
    
    
    var tables = $("#" + id + " table");
    if (tables.length > 0)
    {
        
        for (var i = 0; i<tables.length; i++ )
        {
            var table = $(tables[i]);
            var ItemId = table.attr("id");
            var trs = $("#"+ItemId+" tr");
            var xml ="";
            var xmlPos = 0;
            xml +="<items>";
            for(var tri = 0; tri < trs.length; tri++)
            {
                
                var tr = $(trs[tri]);
                if (tr.hasClass("noDatabase")) continue;
                if (tr.hasClass(ignoreItems) && ignoreItems !="") continue;
                
                var tds = $(tr).find("td");
                
                xml +="<item>";
                for(var tdi = 0;tdi<tds.length;tdi++ )
                {
                    var td = $(tds[tdi]);
                    if (td.hasClass("noDatabase")) continue;
                    if (td.hasClass(ignoreItems) && ignoreItems !="") continue;
                    var xmlItemName = td.attr("xmlitem");
                    var xmlItemValue = $.trim(td.html());
                    
                    xml += "<"+xmlItemName+">"+xmlItemValue+"</"+xmlItemName+">";
                    
                }
                xml +="</item>";
                
                
            }
            xml +="</items>";
            if (!IsUndefined(ItemId))
            outObject[ItemId] = value;
            
        }
    }
    
    if (RegisteredHtmlEditor.length > 0)
    {
    
        
        for (i=0; i < tinyMCE.editors.length; i++){
           try{ 
           var content = tinyMCE.editors[i].getContent();
           if (content != "")
           {   
               
               var ItemId = tinyMCE.editors[i].id+"__ishtmleditor__";
               if (!IsUndefined(ItemId))
               {
                   outObject[ItemId] = content;
                   
                }
              }
            }
            catch (e){
                
            }
        }
    }
    
    return outObject;
}

function ConvertSystemArrayToObject(data)
{
    var out  = {};
    for (var i = 0; i< data.length; i++)
    {
        var row = data[i];
        var name = row[0];
        var value = row[1];
        out[name] = value;
    }
    return out;
}

function AddNewRowByJson(outData, tableId, idPrefixRow)
{
    var newRow = $(".newRow").html();
    for (key in outData)
    {
        newRow = newRow.replace(new RegExp("{" + key + "}", 'g'), outData[key]);
    }
    newRow = '<tr ="' + idPrefixRow + outData["Id"] + ">" + newRow + "</tr>";
    $("#" + tableId + " table").append(newRow);
}


function ExportData( functionName, controllerName, modelName)
{
    var outFile = $(".ExportType:checked").val();
    var params = {ModelName:modelName,ExportType: outFile};
    var outData = CallPhpFunctionAjax(controllerName, functionName, "POSTOBJECT", params);
    window.location.href = outData;
}
function Import( functionName, controllerName, modelName)
{
    var vebid = $("#WebId").val();
    
    var modeImport = $(".importSettings:checked").val();

                var file_data = $('#fileUpload').prop('files')[0];
                var form_data = new FormData();
                form_data.append('file', file_data)
                $.ajax({
                    url: '/fileupload/'+vebid+"/", // point to server-side PHP script 
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (filepath) {
                        var params = {ModelName:modelName,FilePath:filepath,Mode:modeImport}
                        
                        CallPhpFunctionAjax(controllerName, functionName, "POSTOBJECT", params)
                        
                    }
                });

}



function Sort(tableId, functionName, controllerName, modelName, idPrefixRow, sortColumn, sortType)
{
    GridReload(tableId, functionName, controllerName, modelName, idPrefixRow, sortColumn, sortType)
}
function Search(tableId, functionName, controllerName, modelName, idPrefixRow)
{
    GridReload(tableId, functionName, controllerName, modelName, idPrefixRow, undefined, undefined);
}

function ClearSearch(tableId, functionName, controllerName, modelName, idPrefixRow)
{
    $(".filtrTextbox").val("");
    GridReload(tableId, functionName, controllerName, modelName, idPrefixRow, undefined, undefined);
}




function OpenNewWindow(url)
{
    var win = window.open(url, '_blank');
    win.focus();
}
function IsUndefined(obj)
{
    if (typeof obj === "undefined") {
        return true;
    }
    return false;
}

function GetIframeData(iframeForm)
{
    PrepareParametrs(iframeForm)
}
function ReadUserPrivileges(formId)
{
    var checkboxes = $("#" +formId +" input:checkbox");
    var outArray = new Array();
    for(var i = 0; i<checkboxes.length; i++)
    {
        var checkbox = $(checkboxes[i]);
        var ar = Array();
        ar[0] = checkbox.attr("class");
        ar[1] = checkbox.val();
        ar[2] = checkbox.is(":checked");
        outArray[i] = ar;
    }
    return outArray;
}
function CloseDialog(dialogId)
{
    $("body").css("overflow","visible");
    Clear(dialogId);
    $("#"+dialogId).hide();
}
function GoToBack()
{
    var backLocation = document.referrer;
    if (backLocation) {
        window.location.assign(backLocation);
    }
}
function TableSelectAll(el,cssClass)
{
    
    var checked = false;
    if ($(el).is(":checked"))
        checked = true;
    else
        checked = false;
    var selected = $("." + cssClass);
    for (var i = 0;i<selected.length;i++)
    {
        var item = $(selected[i]);
        item.attr("checked",checked);    
    }
}
function ShowLoading()
{
    $("#loadingPanel").show();
}

function HideLoading()
{
    $("#loadingPanel").hide();
}
function TrClick(id)
{
    $("#EditId"+id).click();
}
function DeleteUserItemFrontend(id)
{
    if (confirm(GetWord("word700")))
    {
        CallPhpFunctionAjax("Ajax","DeleteUserItem","POSTOBJECT",{id:id });
        Refresh();
    }
}
function SaveUserProfile(formId)
{
    var data = PrepareParametrs(formId);
    CallPhpFunctionAjax("Ajax","SaveUserProfile","POSTOBJECT",data);

}
function FormItemDetail(id)
{
    var data = CallPhpFunctionAjax("WebEdit", "GetFormItemDetail", "GETOBJECT", {id:id });
    $("#showPanel").html(data);
    
}
function RegisterHtmlEditor(id)
{
    RegisteredHtmlEditor.push(id);    
}
function UnregisterHtmlEditor()
{
    if(RegisteredHtmlEditor.length >0)
    {
        for(var i = 0;i<RegisteredHtmlEditor.length; i++)
        {
            RegisteredHtmlEditor[i] = null;
        }
        
        RegisteredHtmlEditor  =new Array();
    }
    
}



function SetFirstTab(dialogId)
{
 //   alert("sdaasddsa");
    var $f = $("#"+dialogId);
    $('#'+dialogId).load(function(){
        $f.get(0).contentWindow.SetFirstTab();
      });
}

function SetIgnoreExit(value)
{
    ignoreExitQuestion = value;
}
/** dialogs*/
function GetSelectTree(dialogId)
{
    if ($("#"+dialogId+"tab-1").hasClass("active") || !IsUndefined($("#"+dialogId+"html1 .selected").attr("id")) )
    {
        var selectedObject = $("#"+dialogId+"html1 .selected");
        var id = selectedObject.attr("id");
        return id;
    }
    else if ($("#"+dialogId+"tab-2").hasClass("active")||  !IsUndefined($("#"+dialogId+"html2 .selected").attr("id")))
    {
        var selectedObject = $("#"+dialogId+"html2 .selected");
        var id = selectedObject.attr("id");
        return id;
    }
    else if ($("#"+dialogId+"tab-4").hasClass("active") || !IsUndefined($("#"+dialogId+"html4 .selected").attr("id")))
    {
        var selectedObject = $("#"+dialogId+"html4 .selected");
        var id = selectedObject.attr("id");
        return id;
    }                
    
    }
    
    function GetTab(dialogId)
    {
        //alert(lastActive);
        if ($("#"+dialogId+"tab-1").hasClass("active"))
            return 1;
        if ($("#"+dialogId+"tab-2").hasClass("active"))
            return 2;
        if ($("#"+dialogId+"tab-3").hasClass("active"))
            return 3;
        if ($("#"+dialogId+"tab-4").hasClass("active"))
            return 4;
        if ($("#"+dialogId+"tab-5").hasClass("active"))
            return 5;
        if ($("#"+dialogId+"tab-6").hasClass("active"))
            return lastActive;
    }
    
        function GetUserPrivileges(dialogId)
    {
        var inputs = $("#"+dialogId+"privilegesForm input");
        var out = new Array();
        for (var i=0; i<inputs.length; i++ )
        {
            var ar = new Array();
            var input = $(inputs[i]);
            ar[0] = input.attr("id");
            ar[1] = input.is(":checked");
            out[i] = ar;
        }
        return out;
    }
    
    function GetLinkSetting(dialogId)
    {
        if ($("#"+dialogId+"tab-3").hasClass("active"))
        {
            var outArray = new Array();
            outArray[0] = $("#"+dialogId+"Name").val();
            outArray[1] = $("#"+dialogId+"Url").val();
            //SetFirstTab();
            return outArray;
        }
        if ($("#"+dialogId+"tab-5").hasClass("active"))
        {
            var outArray = new Array();
            outArray[0] = $("#"+dialogId+"JsActionName").val();
            outArray[1] = $("#"+dialogId+"JsAction").val();
            //SetFirstTab();
            return outArray;
        }
    }
    function ReloadComponent(divId, componentName)
    {
        
    }
    