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
        var data = CallPhpFunctionAjax(controllerName, "GetLinkDetail", "JSON", id);
        
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
    var params = Array();
    var ar1 = Array();
    ar1[0] = "Id";
    ar1[1] = id;
    params[0] = ar1;
    
    var outValue = CallPhpFunctionAjax("WebEdit", "DeleteTemplate", "POST", params);
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
    var parametrs = new Array();
    var ar1 = new Array();
    ar1[0] = "Type";
    ar1[1] = type;
    parametrs[0] = ar1;
    var data = "";
    data = CallPhpFunctionAjax("WebEdit", "GetActualTree", "POST", parametrs);
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
     var parametrs = new Array();
     var ar1 = new Array();
     ar1[0] = "Type";
     ar1[1] = type;
     parametrs[0] = ar1;
     var ar2 = new Array();
     ar2[0] = "Search";
     ar2[1] = searchValue;
     parametrs[1] = ar2;
     var data = "";
     if (type == "file")
         data = CallPhpFunctionAjax("FilerepositoryManger", "GetActualTree", "POST", parametrs);
     else 
         data = CallPhpFunctionAjax("WebEdit", "GetActualTree", "POST", parametrs);
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
    var params = new Array();
    var ar1 = new Array();
    ar1[0] = "Id";
    ar1[1] = id;
    params[0] = ar1;
    var ar2= new Array();
    ar2[0] = "Mode";
    ar2[1] = mode;
    params[1] = ar2;
    var ar3= new Array();
    ar3[0] = "ContentType";
    ar3[1] = type;
    params[2] = ar3;
    
    CallPhpFunctionAjax("WebEdit","MoveItemFolder","POST",params);
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
            
        
        
        var params = new Array();
        var ar1 = new Array();
        var y = 0;
        ar1[0] = "Type";
        ar1[1] = type;
        params[y] = ar1;
        y++;
        if (activeTab == 1 || activeTab == 2 || activeTab == 4)
        {
            var ar3 = new Array();
            ar3[0] = "LinkId";
            ar3[1] = GetSelectTree("linkDialog");
            params[y] = ar3;
            y++;
        }
        else if (activeTab == 3 || activeTab == 5)
        {
            var ar2 = new Array();
            ar2[0] = "LinkInfo";
            ar2[1] = GetLinkSetting("linkDialog");
            params[y] = ar2;
            y++;
        }
        var selectedObject = $("#folderTree .selected");
        var source = selectedObject.attr("id");
        
        if(IsUndefined(source)) source = selectId;
        else source = source.replace("_anchor", "");
        var ar4 = new Array();
        ar4[0] = "ParentId";
        ar4[1] = source;
        params[y] = ar4;
        y++;
        var objectLinkId = $("#ObjectLinkId").val();
        var ar5 = new Array();
        ar5[0] = "ObjectLinkId";
        ar5[1] = objectLinkId;
        params[y] = ar5;
        y++;
        var ar6 = new Array();
        ar6[0] = "Privileges";
        ar6[1] = privileges;
        params[y] = ar6;
        y++;
        CallPhpFunctionAjax("WebEdit","CreateWebLink","POST",params);
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
        var param = new Array();
        var ar1 = new Array();
        ar1[0] = "sourceId";
        ar1[1] = source;
        param[0] = ar1;
        var ar2 = new Array();
        ar2[0] = "destinationId";
        ar2[1] = dest;
        param[1] = ar2;
        if (copyModeAction == "move")
        {
            var value = CallPhpFunctionAjax("WebEdit", "MoveItem", "POST", param);
            if (value == "FALSE")
            {
                alert(GetWord("word606"));
            }
        }
        if (copyModeAction == "copy")
        {
            var value = CallPhpFunctionAjax("WebEdit", "CopyItem", "POST", param);
            if (value == "FALSE")
            {
                alert(GetWord("word607"));
                
            }
        }
    }

function LoadData(outId,type)
{
    var loadParams = new Array();
    var loadParams1 = new Array();
    loadParams1[0] = "Type";
    loadParams1[1] = type;
    loadParams[0] =loadParams1;
    var loadParams2 = new Array();
    loadParams2[0] = "Id";
    loadParams2[1] = outId;
    loadParams[1] =loadParams2;
    var outdata =  CallPhpFunctionAjax("WebEdit","GetObjectData","JSON",loadParams);
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
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialog", "POST", null);
    $("#dialogComponentLink").html(html);
}
function LoadLinkDialogCss()
{
    var html = CallPhpFunctionAjax("WebEdit", "GetTreeLinkDialogCss", "POST", null);
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
            CallPhpFunctionAjax("WebEdit","DeleteLangVersion","GET",objectId);
            GoToBack();    
        }
    }