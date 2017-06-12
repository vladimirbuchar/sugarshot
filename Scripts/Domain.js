function ShowDeletedItem(tableId, functionName, controllerName, modelName, idPrefixRow)
{
    $("#"+tableId +" .showItem").val("DeleteItem");
    GridReload(tableId, functionName, controllerName,  idPrefixRow,modelName, undefined, undefined);
}

function ShowNormalItem(tableId, functionName, controllerName, modelName, idPrefixRow)
{
    $("#"+tableId +" .showItem").val("NoDeleteItem");
    GridReload(tableId, functionName, controllerName,  idPrefixRow,modelName, undefined, undefined);
}

function SaveItem(dialogId,controllerName,actionName,model)
{
    if (ValidateForm(dialogId))
    {
        var parametrs = PrepareParametrs(dialogId,"ignoreDomainsave");
        var ar = new Array();
        ar[0] = "ModelName";
        ar[1] = model;
        parametrs[parametrs.length] = ar;
        var outData = CallPhpFunctionAjax(controllerName, actionName, "JSON", parametrs);
        var insertId = outData["Id"];
        if (IsUndefined(insertId))
            insertId = outData;
        
        if (insertId == 0)
        {
            alert(outData["Errors"]);
            return false;
        }
        else
        {
            CallAfterSaveFunction(insertId);
            return true;
        }
    }
    return false;
}

function ShowEditForm(controllerName,detailAction,detailId,model)
{
    
    $(".noUpdate").attr("disabled","disabled");
    var params = Array();
    var ar = Array();
    ar[0] = "Id";
    ar[1] = detailId;
    params[0] = ar;
    var ar1 = Array();
    ar1[0] = "ModelName";
    ar1[1] = model;
    params[1] = ar1;
    var data = CallPhpFunctionAjax(controllerName, detailAction, "JSON", params);
    WriteData(data);
    CallUserLoadDetail(detailId);
}

function CallUserLoadDetail()
{
    try{
        
        if (typeof OnLoadItemDetail == 'function') { OnLoadItemDetail(); }
    }
    catch(e)
    {
        
    }
}

function CallAfterSaveFunction(id)
{
    try{
        if (typeof OnAfterSaveItem == 'function') { OnAfterSaveItem(id); }
    }
    catch(e)
    {
        
    }
}

function SetDeleteItem(name,id)
{
    $("#deleteName").html(name);
    $("#deleteId").val(id);
}


function DeleteDomianItem(controllerName,actionName,deleteId,model)
{
    var inArray = new  Array();
    var ar = Array();
    ar[0] = "Id";
    ar[1] = deleteId;
    inArray[0] = ar;
    var ar1 = Array();
    ar1[0] = "ModelName";
    ar1[1] = model;
    inArray[1] = ar1;
    var ar2 = new Array();
    ar2[0] = "DeletePernamently";
    var showMode = $(".showItem").val();
    ar2[1] =false;
    if(showMode =="DeleteItem") 
        ar2[1] =true;
    inArray[2] = ar2;
    CallPhpFunctionAjax(controllerName, actionName, "POST", inArray);
}


function DeleteSelectItem(multiselectIdentificator,  controllerName, actionName, idPrefix, model)
{
    var selectedItem = $("." + multiselectIdentificator);
    for (var i = 0; i < selectedItem.length; i++)
    {
        var item = $(selectedItem[i]);
        var isChecked = item.is(":checked");
        if (isChecked)
        {
            var parametrsAction = item.parent().parent().attr("id");
            var deleteId = parametrsAction.replace(idPrefix, "");
            DeleteDomianItem(controllerName,actionName,deleteId,model);
        }
    }
    
}
function ShowMultiDeleteDialog()
{
    $(".SelectedItem").addClass("dn");
    $(".NoSelectedItem").addClass("dn");
    if (IsSelected())
    {
        $(".SelectedItem").removeClass("dn");
    }
    else 
    {
        $(".NoSelectedItem").removeClass("dn");
    }
}


function RecoveryMultiSelect(multiselectIdentificator,  controllerName, actionName,idPrefix, model)
{
    
        var selectedItem = $("." + multiselectIdentificator);
        for (var i = 0; i < selectedItem.length; i++)
        {
            var item = $(selectedItem[i]);
            var isChecked = item.is(":checked");
            if (isChecked)
            {
                var parametrsAction = item.parent().parent().attr("id");
                parametrsAction = parametrsAction.replace(idPrefix, "");
                Recovery(controllerName, actionName, parametrsAction, model)
            }
        }
    

}

function ShowMultiRecoveryDialog()
{
    $(".SelectedItem").addClass("dn");
    $(".NoSelectedItem").addClass("dn");
    if (IsSelected())
    {
        $(".SelectedItem").removeClass("dn");
    }
    else 
    {
        $(".NoSelectedItem").removeClass("dn");
    }
}

function ShowMultiCopy()
{
    $(".SelectedItem").addClass("dn");
    $(".NoSelectedItem").addClass("dn");
    if (IsSelected())
    {
        $(".SelectedItem").removeClass("dn");
    }
    else 
    {
        $(".NoSelectedItem").removeClass("dn");
    }
}




function RecoveryDialog(name,id)
{
    $("#recoveryName").html(name);
    $("#recoveryId").val(id);
}

function Recovery(controllerName, actionName, parametrsAction, model)
{
    var inArray = Array();
    var ar = Array();
    ar[0] = "Id";
    ar[1] = parametrsAction;
    inArray[0] = ar;
    var ar1 = Array();
    ar1[0] = "ModelName";
    ar1[1] = model;
    inArray[1] = ar1;

    CallPhpFunctionAjax(controllerName, actionName, "POST", inArray);
    
}

function GridReload(tableId, functionName, controllerName, idPrefixRow, modelName, sortColumn, sortType)
{
    $(".noData").addClass("dn");
    var params = new Array();
    var ar = new Array();
    ar[0] = "ModelName";
    ar[1] = modelName;
    params[0] = ar;

    if (!IsUndefined(sortColumn))
    {
        var ar1 = new Array();
        ar1[0] = "SortColumn";
        ar1[1] = sortColumn;
        params[params.length] = ar1;
    }
    if (!IsUndefined(sortType))
    {
        var ar2 = new Array();
        ar2[0] = "SortType";
        ar2[1] = sortType;
        params[params.length] = ar2;
    }
    var filtrTextbox = $(".filtrTextbox");
    
    var where = new Array();
    var y = 0;
    for (var i = 0; i < filtrTextbox.length; i++)
    {
        var item = $(filtrTextbox[i]);
        var value = item.val();
        if (value == "")
            continue;
        var id = item.attr("id");
        var idandor = id+"-ANDOR";
        var idlikemode = id+"-LIKEMODE";
        var idandorvalue = $("#"+idandor).val();
        var likemodevalue = $("#"+idlikemode).val();
        id = id.replace("filtr-", "");
        var nAr = new Array();
        nAr[0] = id;
        nAr[1] = value;
        where[y] = nAr;
        y++;
        var nAr2 = new Array();
        nAr2[0] = "ANDOR";
        nAr2[1] = idandorvalue;
        where[y] = nAr2;
        y++;
        var nAr3 = new Array();
        nAr3[0] = "LIKEMODE";
        nAr3[1] = likemodevalue;
        where[y] = nAr3;
        y++;
        
    }
    if (where != "")
    {
        var ar3 = new Array();
        ar3[0] = "Where";
        ar3[1] = where;
        params[params.length] = ar3;
    }
    else 
    {
        var ar3 = new Array();
        ar3[0] = "Where";
        ar3[1] = "clear";
        params[params.length] = ar3;
    }
    var ar4 = new Array();
    ar4[0] = "SaveFiltrSortToSession";
    ar4[1] = true;
    params[params.length] = ar4;
    
    var ar5 =  new Array();
    ar5[0] = "ShowItem";
    ar5[1] = $("#"+tableId +" .showItem").val();
    params[params.length] = ar5;
    if ($("#"+tableId +" .showItem").val() =="DeleteItem")
    {
        $(".showInDeleted").removeClass("dn");
        $(".showInNormal").addClass("dn");
    }
    else 
    {
        $(".showInDeleted").addClass("dn");
        $(".showInNormal").removeClass("dn");
        
    }
    

    var outData = CallPhpFunctionAjax(controllerName, functionName, "JSON", params);
    var tableTr = $("#" + tableId + " table tr");
    for (var i = 0; i < tableTr.length; i++)
    {
        var tr = $(tableTr[i]);
        if (tr.hasClass("dn") || tr.hasClass("header") || tr.hasClass("filtrHeader") || tr.hasClass("noData"))
            continue;
        tr.remove();
    }
    for (row in outData)
    {
        var newRow = $("#" + tableId + " .newRow").html();
        var id = "";
        for (key in outData[row])
        {
            var value = outData[row][key];
            if (key == "Id")
                id = value;
            var find = "{" + key + "}";
            newRow = newRow.replace(new RegExp(find, 'g'), value);
            newRow = newRow.replace(new RegExp("null", 'g'), "");
            
        }
        
        //newRow = newRow.replace(new RegExp("TdMultiSelectTemplate",'q'),"TdMultiSelect");
        newRow = '<tr id="' + idPrefixRow + id + '">' + newRow + '</tr>';
        $("#" + tableId + " table").append(newRow);   
        $("#"+ idPrefixRow + id).removeClass("TdMultiSelectTemplate");
    }
    if ($("#" + tableId + " tr").length <= 3)
    {
        $(".noData").removeClass("dn");
    }
}

function ShowFirstTab()
{
    var li = $(".nav-tabs li");
    li.removeClass("active");
    $(li[0]).addClass("active");
    var tabcontent = $(".tab-content .tab-pane");
    tabcontent.removeClass("active");
    tabcontent.removeClass("in");
    $(tabcontent[0]).addClass("active");
    $(tabcontent[0]).addClass("in");
    
}
function MultiSelect(CssClass, el)
{
    $(".TdMultiSelect").html($(".TdMultiSelectTemplate").html());
    var checked = false;
    if ($(el).is(":checked"))
        checked = true;
    else
        checked = false;
    var selected = $("." + CssClass);
    for (var i = 0;i<selected.length;i++)
    {
        var item = $(selected[i]);
        item.attr("checked",checked);    
    }
}

function CopyDialog(name,id)
{
    $("#copyName").html(name);
    $("#copyId").val(id);
}

function CopyRow(parametrsAction, controllerName, actionName,  model)
{
    var params = Array();
    var ar = Array();
    ar[0] = "Id";
    ar[1] = parametrsAction;
    params[0] = ar;


    var ar1 = Array();
    ar1[0] = "ModelName";
    ar1[1] = model;
    params[1] = ar1;
    CallPhpFunctionAjax(controllerName, actionName, "JSON", params);
    
}

function MultiCopy(multiselectIdentificator, controllerName, idPrefix, actionName, model)
{
    var selectedItem = $("." + multiselectIdentificator);
    for (var i = 0; i < selectedItem.length; i++)
    {
        var item = $(selectedItem[i]);
        var isChecked = item.is(":checked");
        if (isChecked)
        {
            var parametrsAction = item.parent().parent().attr("id");
            parametrsAction = parametrsAction.replace(idPrefix, "");
            CopyRow(parametrsAction, controllerName, actionName,  model)
        }
    }
    
}

function IsSelected()
{
    var sel = $(".selectItem:checked");
    if (sel.length == 0)
        return false;
    return true;
    
}
function ShowHistory(conrollerName,historyAction,ModelName,id)
{
    var params = new Array();
    var ar1 = new Array();
    ar1[0] ="ModelName";
    ar1[1] =ModelName;
    params[0] = ar1;
    var ar2 = new Array();
    ar2[0] ="Id";
    ar2[1] =id;
    params[1] = ar2;
    var historyData = CallPhpFunctionAjax(conrollerName, historyAction, "JSON", params)
    var historyObject = $("#HistoryObject .historyTemplate").html();
    $(".historyItem").remove();
    for (row in historyData)
    {
        var idHistory = 0;
        var newRow = historyObject;
        for (key in historyData[row])
        {
            var value = historyData[row][key];
            if (key == "Id")
            {
                idHistory = value;
            }
            var find = "{" + key + "}";
            if (typeof value === 'object')
            {
                if (!IsUndefined(value)&& value != null)
                {
                    value = value["date"];
                }   
            }
            newRow = newRow.replace(new RegExp(find, 'g'), value);
            
        }
        newRow = '<tr class="historyItem">' + newRow + '</tr>';
        
        $("#HistoryObject").append(newRow);
        
    }

    
}

function RecoveryItemFromHistory(id,controllerName)
{
    var params = new Array();
    var ar = new Array();
    ar[0] = "Id";
    ar[1] = id;
    params[0] = ar;
    CallPhpFunctionAjax(controllerName, "RecoveryFromHistory", "POST", params)
}







