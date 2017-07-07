

     function LoadPage(data){       
        WriteXmlData(data);
        var name   = CallPhpFunctionAjax("WebEdit", "GetSelectedObjectName", "POST",  $("#SelectedObject").val());
        $("#relatedObjectName").html(name);
        RenderDomainColumnsArticle($("#SelectedObject").val());
        $("#OtherLang").change(function(){
            SaveTemplate(false);
             SetIgnoreExit(true);
            CallPhpFunctionAjax("WebEdit", " ChangeLangVersion",  "POST",  null);
            var selectLang = $(this).val();
            window.location.href = "/xadm/WebEdit/DataSourceDetail/" + $("#WebId").val() + "/" +  selectLang  +  "/"  + $("#ObjectId").val() + "/0/";
        });
        RenderDomainColumns($("#Domain").val());
        
        WriteXmlData(data);
        $("#Domain").change(function(){
            var domainId = $(this).val();
            RenderDomainColumns(domainId);
            
        });
        $("#DatasourceType").change(function(){
            var type = $(this).val();
            ChageForm(type);
        });
        ChageForm($("#DatasourceType").val());
        editor = CodeMirror(document.getElementById("DataEditor"), {
          mode: "xml",
          extraKeys: {"Ctrl-Space": "autocomplete"},
          value: $("#DatasourceXmlItem").val()
        });
        editor.on('change',function(cMirror){
            $("#DatasourceXmlItem").val(cMirror.getValue());
        });
        
         editorSubItem = CodeMirror(document.getElementById("DataSumItem"), {
          mode: "xml",
          extraKeys: {"Ctrl-Space": "autocomplete"},
          value: $("#DatasourceXmlSubItem").val()
        });
        editorSubItem.on('change',function(cMirror){
            $("#DatasourceXmlSubItem").val(cMirror.getValue());
        });
    }
    function ChageForm(type)
    {
        $(".XmlImport").hide();
        $(".XmlExport").hide();
        $(".XmlExportUserItem").hide();
        $(".XmlImportUserItem").hide();
        switch (type)
        {
            case "XmlImport":
                $(".XmlImport").show();
                break;
            case "XmlExport":
                $(".XmlExport").show();
                break;
            case "XmlExportUserItem":
                $(".XmlExportUserItem").show();
                break;
            case "XmlImportUserItem":
                $(".XmlImportUserItem").show();
                break;
        }
        
    }
                    function ShowJoinObject()
                    {
                    var html = CallPhpFunctionAjax("WebEdit", "GetObjectsXml", "POST", null);
                            $("#dialogComponent").html(html);
                    }
            function AddArticle()
            {
                var ObjectIdConnection = GetSelectTree("SelectXml");
                $("#SelectedObject").val(ObjectIdConnection);
                var name = CallPhpFunctionAjax("WebEdit", "GetSelectedObjectName", "POST", ObjectIdConnection);
                $("#relatedObjectName").html(name);
                RenderDomainColumnsArticle(ObjectIdConnection);

            }
            function RenderDomainColumnsArticle(ObjectIdConnection)
            {
                if (ObjectIdConnection =="" || ObjectIdConnection == 0) return; 
                var domainId = CallPhpFunctionAjax("WebEdit", "GetDomainIdByUserItemId", "POST", ObjectIdConnection);
                var data = CallPhpFunctionAjax("WebEdit", "GetDomainColumns", "JSON", domainId);
                var html = "";
                html += "<option value= \"\">----</option>";
                html += "<option value= \"-1\">"+GetWord("word590")+"</option>";
                html += "<option value= \"-2\">"+GetWord("word591")+"</option>";
                html += "<option value= \"-3\">"+GetWord("word592")+"</option>";
                html += "<option value= \"-4\">"+GetWord("word593")+"</option>";
                for (var i = 0; i < data.length; i++)
                {
                    html += "<option value= \"" + data[i].Id + "\">" + data[i].ShowName + "</option>";
                }
                $("#ColumnTestUserImport").html(html);
                LoadDomainItems(ObjectIdConnection,"useritem");
            }
            function RenderDomainColumns(domainId)
            {
                if (domainId =="" || domainId == 0) return; 
                var data = CallPhpFunctionAjax("WebEdit", "GetDomainColumns", "JSON", domainId);
                var html = "";
                html += "<option value= \"\">----</option>";
                for (var i = 0; i < data.length; i++)
                {
                    html += "<option value= \"" + data[i].Id + "\">" + data[i].ShowName + "</option>";
                }
                $("#ColumnTest").html(html);
                LoadDomainItems(domainId,"domain");
            }
            function WriteXmlData(xml)
            {

            xmlDoc = $.parseXML(xml);
                    $xml = $(xmlDoc);
                    WriteItem("DatasourceType", $xml);
                    WriteItem("DatasourceXmlStart", $xml);
                    WriteItem("DatasourceXmlEnd", $xml);
                    WriteItem("DatasourceXmlItem", $xml);
                    WriteItem("DatasourceXmlItemStart", $xml);
                    WriteItem("DatasourceXmlItemEnd", $xml);
                    WriteItem("Domain", $xml);
                    WriteItem("DatasourceXmlUrl", $xml);
                    WriteItem("ImportMode", $xml);
                    WriteItem("ColumnTest", $xml);
                    WriteItem("ColumnTestUserImport", $xml);
                    WriteItem("SelectedObject", $xml);
                    WriteItem("DatasourceXmlSubItem", $xml);
                    WriteItem("DatasourceXmlSubItemStart", $xml);
                    WriteItem("DatasourceXmlSubItemEnd", $xml);
                    WriteItem("DatasourceXmlSubItemItemEnd", $xml);
                    WriteItem("DatasourceXmlSubItemItemStart", $xml);
                    WriteItem("ExportConditions", $xml);
                    WriteItem("ExportColumnConditions", $xml);
                    
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


            function SaveTemplate(publish)
                    {
                    ShowLoading();
                            var params = PrepareParametrs("settingTemplate");
                            var items = PrepareParametrs("parametrs");
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
    
    var ar4 = new Array();
    ar4[0] = "Data";
    ar4[1] = items;
    nextItem++;
    params[nextItem] = ar4;
   var outId = CallPhpFunctionAjax("WebEdit","SaveDataSource","POST",params);
   $("#ObjectId").val(outId);
   LoadData(outId,"datasource");
   HideLoading();
}
function CallUrl()
{
    SaveTemplate(true);
    CallPhpFunctionAjax("WebEdit","CallDataSourceImport","POST",$("#ObjectId").val());
}

function CheckFile()
{
    SaveTemplate(true);
    CallPhpFunctionAjax("WebEdit","CheckFile","POST",$("#ObjectId").val());
}
function XmlExport()
{
    SaveTemplate(true);
    var seoUrl = CallPhpFunctionAjax("WebEdit","CallDataSourceExport","POST",$("#ObjectId").val());
    window.open(seoUrl,"_blank");
}

function GenerateXmlItem()
{
    SaveTemplate(true);
    var data = CallPhpFunctionAjax("WebEdit","GenerateXmlItem","POST",$("#ObjectId").val());
    $("#DatasourceXmlItem").val(data);
    editor.setValue(data);
}

function LoadDomainItems(id,type)
{
    if (id =="" || id == 0) return; 
    if ($("#DomainItems").html() =="")
    {
        if (type =="domain")
    {
        var data = CallPhpFunctionAjax("WebEdit", "GetDomainColumns", "JSON", id);
        var html = "";
        for(var i = 0; i< data.length; i++)
        {
            var showName = data[i].ShowName;
            var Identificator = data[i].Identificator;
            html += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"}\");return false;'>"+showName+" ("+Identificator+") </a><br />";
        }
        $("#DomainItems").html(html)
    }
    if (type=="useritem")
    {
        
         var domainId = CallPhpFunctionAjax("WebEdit", "GetDomainIdByUserItemId", "POST", id);
         var data = CallPhpFunctionAjax("WebEdit", "GetDomainColumns", "JSON", domainId);
         var html = "";
         var html2 = "";
         for(var i = 0; i< data.length; i++)
        {
            var showName = data[i].ShowName;
            var Identificator = data[i].Identificator;
            html += "<a href='#' onclick='SetTextHtml(\"{"+Identificator+"}\");return false;'>"+showName+" ("+Identificator+") </a><br />";
            html2 += "<a href='#' onclick='SetTextHtmlSubItem(\"{"+Identificator+"}\");return false;'>"+showName+" ("+Identificator+") </a><br />";
        }
        $("#DomainItems").html(html);
        $("#DomainSubItems").html(html2);
    }
    }
}
function SetTextHtml(item)
{
    editor.replaceSelection(item);
}
function SetTextHtmlSubItem(item)
{
    editorSubItem.replaceSelection(item);
}