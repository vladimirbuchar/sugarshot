var childClick = false;
var selectId = 0;
var copyModeAction = "";
$(document).ready(function ()
{
    $('.tree').treegrid();
    $('.tree').on('doubletap', function (event) {
        ItemDoubleClick();
    }); 
});
    
function CreateNewObject(controllerName,viewName,prefix,type)
{
    var webId = $("#WebId").val();
    var langId = $("#LangId").val();
    var selectedObject = $(".selected");
    var id = selectedObject.attr("id");
    if(IsUndefined(id)) id = selectId;
    else id = id.replace("_anchor", "");
    var url ="";
    if (typeof type === "undefined")
        url = prefix+"/"+controllerName+"/"+viewName+"/"+webId+"/"+langId+"/0/"+id+"/";
    else 
        url = prefix+"/"+controllerName+"/"+viewName+"/"+type+"/"+webId+"/"+langId+"/0/"+id+"/";
    window.location.href = url;
    return false;
}

function EditItem(controllerName,viewName,prefix)
{
    var webId = $("#WebId").val();
    var langId = $("#LangId").val();
    var selectedObject = $(".selected");
    var id = selectedObject.attr("id");
    var contentType = selectedObject.attr("contenttype");
    
    if (contentType == "Link" || contentType=="ExternalLink" || contentType=="CssExternalLink" || contentType=="JsExternalLink" || contentType=="JavascriptAction")
    {
        $("#createLink a").click();
        var data = CallPhpFunctionAjax(controllerName, "GetLinkDetail", "JSONOBJECT", {id:id});
        
        var type = data["LinkType"];
        var linkObjectId = data["ObjectId"];
        var name = data["Name"];
        var url = data["Url"];
        SetActiveTab(type,linkObjectId,name,url,"linkDialog");
        
        
        $("#ObjectLinkId").val(id);
        return false;
    }
    if(IsUndefined(id)) id = selectId;
    else id = id.replace("_anchor", "");
    var url = prefix+"/"+controllerName+"/"+viewName+"/"+webId+"/"+langId+"/"+id+"/0/";
    window.location.href = url;
    return false;
}

function DeleteDialog()
{
    var selectedObject = $(".selected .itemName");
    var name =$.trim(selectedObject.text());
    var id = selectedObject.attr("id");
    if(IsUndefined(id)) id = selectId;
    else id = id.replace("_anchor", "");
    $("#deleteName").html(name);
}

function DeleteItem()
{
    var selectedObject = $(".selected");
    var id = selectedObject.attr("id");
    if(IsUndefined(id)) id = selectId;
    else id = id.replace("_anchor", "");
    var params = {Id:id};
    
    
    var outValue = CallPhpFunctionAjax("WebEdit", "DeleteTemplate", "POSTOBJECT", params);
    if (outValue == "FALSE")
    {
        alert(GetWord("word605"));
    }
}
   


function ItemClick(type,el,setId,dialogId)
{
    if (dialogId =="")
        $(".tree tr").removeClass("selected");
    else 
        $("#"+dialogId+" tr").removeClass("selected");
    if (type == "root")
    {
        if (setId)
        {
            selectId= $(el).attr("id");
            //alert("a");
        }
        $("#editObject").addClass("disabled");
            $("#copyObject").addClass("disabled");
            $("#moveObject").addClass("disabled");
            $("#deleteObject").addClass("disabled");
            $("#createObject").removeClass("disabled");
            $("#createLink").removeClass("disabled");
            $("#fileUpload").removeClass("disabled");
            $("#filesUpload").removeClass("disabled");
        
    }
    if (type == "child")
    {
        if (setId)
        {
            selectId = $(el).attr("id");
            //alert("b");
        }
        SetChildClick(true);
        $("#copyObject").removeClass("disabled");
        $("#moveObject").removeClass("disabled");
        $("#deleteObject").removeClass("disabled");
        $("#createObject").removeClass("disabled");
        $("#createLink").removeClass("disabled");
        $("#editObject").removeClass("disabled");
        $("#fileUpload").removeClass("disabled");
        $("#filesUpload").removeClass("disabled");
    }
    $(el).addClass("selected");
}
function SetChildClick(value)
{
    childClick = value;
}


function ReloadTree(type)
{
    var parametrs = {Type:type};
    var data = "";
    data = CallPhpFunctionAjax("WebEdit", "GetActualTree", "POSTOBJECT", parametrs);
    $(".tree").html(data);
    $('.tree').treegrid();
}
function ItemDoubleClick()
 {
     $("#editObject a").click();
 }
 function SearchAdmin(type)
 {
     ShowLoading();
     var searchValue = $("#SearchText").val();
     var parametrs = {Type:type,Search:searchValue};
     var data = CallPhpFunctionAjax("WebEdit", "GetActualTree", "POSTOBJECT", parametrs);
     $(".tree").html(data);
     $('.tree').treegrid();
     HideLoading();
 }
 function ClearSearchAdmin(type)
 {
     $("#SearchText").val("");
     SearchAdmin(type);
}
function MoveUp(id,type)
{
    MoveItem(id,"up",type);
}
function MoveDown(id,type)
{
    MoveItem(id,"down",type);
}
function MoveItem(id,mode,type)
{
    var params = {Id:id,Mode:mode,ContentType:type}
    CallPhpFunctionAjax("WebEdit","MoveItemFolder","POSTOBJECT",params);
    if (type =="UserItem" || type=="ExternalLink" || type=="Link" )
        type ="useritem";
    else if (type =="Template")
        type ="template";
    else if (type == "Css" || type=="CssExternalLink")
        type ="css";
    else if (type == "Javascript" || type=="JsExternalLink")
        type ="js";
    else if (type == "Form")
        type ="form";
    else if (type =="Mail")
        type ="mail";
    else if (type =="FileUpload" || type =="FileFolder")
        type = "file";
    else if (type=="DataSource")
        type = "DataSource";
    else if (type=="Mailing")
        type="mailing";
    else if(type == "Inquery")
        type = "inquery";
    else 
        type ="useritem";
    ReloadTree(type);
}
function CreateLink(mode)
{
    var activeTab= GetTab("linkDialog");
    var privileges =GetUserPrivileges("linkDialog");
    var type ="";
    if (activeTab == 1)
        type ="document";
    else if (activeTab == 2)
        type ="repository";
    else if (activeTab ==3)
    {
        if (mode =="css")
            type ="csslink";
        else if (mode=="js")
        {
            type ="jslink";
        }
        else 
            type ="link";
    }
    else if (activeTab == 4)
        type = "form";
    else if (activeTab == 5)
        type = "javascript"
            
        
        
        var params = {};
        params.Type = type; 
        
        if (activeTab == 1 || activeTab == 2 || activeTab == 4)
        {
            params.LinkId = GetSelectTree("linkDialog");
        }
        else if (activeTab == 3 || activeTab == 5)
        {
            params.LinkInfo = GetLinkSetting("linkDialog");
        }
        var selectedObject = $("#folderTree .selected");
        var source = selectedObject.attr("id");
        
        if(IsUndefined(source)) source = selectId;
        else source = source.replace("_anchor", "");
        params.ParentId = source; 
        
        var objectLinkId = $("#ObjectLinkId").val();
        params.ObjectLinkId = objectLinkId; 
        
        params.Privileges = privileges; 
        CallPhpFunctionAjax("WebEdit","CreateWebLink","POSTOBJECT",params);
        $("#ObjectLinkId").val("0");
    }
    function CopyMove()
    {
        var dest =GetSelectTree("copyItem");
        if (dest == 0)
            return;
        var selectedObject = $("#folderTree .selected");
        var source = selectedObject.attr("id");
        
        if (IsUndefined(source))
        {

            source = selectId;
        }
        else
        {
            source = source.replace("_anchor", "");
        }
        if (source == dest)
            return;
        var param = {sourceId:source,destinationId:dest};
        
        if (copyModeAction == "move")
        {
            var value = CallPhpFunctionAjax("WebEdit", "MoveItem", "POSTOBJECT", param);
            if (value == "FALSE")
            {
                alert(GetWord("word606"));
            }
        }
        if (copyModeAction == "copy")
        {
            var value = CallPhpFunctionAjax("WebEdit", "CopyItem", "POSTOBJECT", param);
            if (value == "FALSE")
            {
                alert(GetWord("word607"));
                
            }
        }
    }

function LoadData(outId,type)
{
    var loadParams = {Type:type,Id:outId};
    var outdata =  CallPhpFunctionAjax("WebEdit","GetObjectData","JSONOBJECT",loadParams);
    if (type== "useritem" || type=="form" || type=="filefolder" || type=="datasource") 
    {
        var seoUrl = outdata[0].SeoUrl;
        $("#SeoUrl").val(seoUrl);
    }
    for(var x = 0; x<outdata.length; x++ )
    {
        var SSGroupId = outdata[x].SSGroupId;
        var SSSecurityType = outdata[x].SSSecurityType;
        var SSValue = outdata[x].SSValue;
        var id = SSSecurityType+"_"+SSGroupId;
        if (SSValue == 1 || SSValue == "1")
        {
            $("#"+id).attr("checked","checked");
        }
    }
}
function LoadLinkDialog()
{
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialog", "POST");
    $("#dialogComponentLink").html(html);
}
function LoadLinkDialogCss()
{
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialogCss", "POST");
    $("#dialogComponentLink").html(html);
}



// vyhodit do fileuploader 
function FileDelete(fileItem)
{
    $("#"+fileItem+"_label").html("");
    $("#"+fileItem+"_filepath").val("");
    $("#"+fileItem+"_remove").hide();
}


function SetActiveTab(type,linkObjectId,name,url,dialogId)
    {   
       
        $("#"+dialogId+"liTab1").removeClass("active");
        $("#"+dialogId+"liTab2").removeClass("active");
        $("#"+dialogId+"liTab3").removeClass("active");
        $("#"+dialogId+"liTab4").removeClass("active");
        $("#"+dialogId+"liTab5").removeClass("active");
        $("#"+dialogId+"liTab6").removeClass("active");
        
        $("#"+dialogId+"tab-1").removeClass("fade");
        $("#"+dialogId+"tab-1").removeClass("in");
        $("#"+dialogId+"tab-1").removeClass("active");
        
        $("#"+dialogId+"tab-2").removeClass("fade");
        $("#"+dialogId+"tab-2").removeClass("in");
        $("#"+dialogId+"tab-2").removeClass("active");
        
        
        $("#"+dialogId+"tab-3").removeClass("fade");
        $("#"+dialogId+"tab-3").removeClass("in");
        $("#"+dialogId+"tab-3").removeClass("active");
        
        $("#"+dialogId+"tab-4").removeClass("fade");
        $("#"+dialogId+"tab-4").removeClass("in");
        $("#"+dialogId+"tab-4").removeClass("active");
        
        if (type == "form")
        {
            $("#"+dialogId+"liTab4").addClass("active");
            $("#"+dialogId+"tab-4").addClass("fade");
            $("#"+dialogId+"tab-4").addClass("in");
            $("#"+dialogId+"tab-4").addClass("active");
            $("#"+dialogId+"html4 #"+linkObjectId).addClass("selected");
        }
        else if (type=="document")
        { 
            $("#"+dialogId+"liTab1").addClass("active");
            $("#"+dialogId+"tab-1").addClass("fade");
            $("#"+dialogId+"tab-1").addClass("in");
            $("#"+dialogId+"tab-1").addClass("active");
            $("#"+dialogId+"html1 #"+linkObjectId).addClass("selected");
        }
        else if (type=="repository")
        {
            $("#"+dialogId+"liTab2").addClass("active");
            $("#"+dialogId+"tab-2").addClass("fade");
            $("#"+dialogId+"tab-2").addClass("in");
            $("#"+dialogId+"tab-2").addClass("active");
            $("#"+dialogId+"html2 #"+linkObjectId).addClass("selected");
        }
        else if (type=="link" || type=="jslink" || type=="csslink")
        {
            $("#"+dialogId+"liTab3").addClass("active");
            $("#"+dialogId+"tab-3").addClass("fade");
            $("#"+dialogId+"tab-3").addClass("in");
            $("#"+dialogId+"tab-3").addClass("active");
            $("#"+dialogId+"Name").val(name);
            $("#"+dialogId+"Url").val(url);
        }
        else if (type=="javascript")
        {
            $("#"+dialogId+"liTab5").addClass("active");
            $("#"+dialogId+"tab-5").addClass("fade");
            $("#"+dialogId+"tab-5").addClass("in");
            $("#"+dialogId+"tab-5").addClass("active");
            $("#"+dialogId+"JsActionName").val(name);
            $("#"+dialogId+"JsAction").val(url);
        }   
    }
    function DeleteLangVersion()
    {
        var objectId = $("#ObjectId").val();
        if (confirm(GetWord("word887")))
        {
            CallPhpFunctionAjax("WebEdit","DeleteLangVersion","GETOBJECT",{objectId: objectId});
            GoToBack();    
        }
    }