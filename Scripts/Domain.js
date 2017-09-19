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
        console.log(parametrs);
        parametrs.ModelName = model;
        var outData = CallPhpFunctionAjax(controllerName, actionName, "JSONOBJECT", parametrs);
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
    var params = {Id:detailId,ModelName:model};
    var data = CallPhpFunctionAjax(controllerName, detailAction, "JSONOBJECT", params);
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
    var showMode = $(".showItem").val();
    var inArray =  {Id:deleteId,ModelName:model,DeletePernamently: showMode =="DeleteItem"};
    CallPhpFunctionAjax(controllerName, actionName, "POSTOBJECT", inArray);
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
    var inArray = {Id:parametrsAction,ModelName:model};
    CallPhpFunctionAjax(controllerName, actionName, "POSTOBJECT", inArray);
    
}

function GridReload(tableId, functionName, controllerName, idPrefixRow, modelName, sortColumn, sortType)
{
    $(".noData").addClass("dn");
    var params = {};
    params.ModelName =modelName;
    

    if (!IsUndefined(sortColumn))
    {
        params.SortColumn =sortColumn;
    }
    if (!IsUndefined(sortType))
    {
        params.SortType =sortType;
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
        params.Where = where; 
        
    }
    else 
    {
        params.Where = "clear";
    }
    params.SaveFiltrSortToSession = true;
    params.ShowItem = $("#"+tableId +" .showItem").val();
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
    var outData = CallPhpFunctionAjax(controllerName, functionName, "JSONOBJECT", params);
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
    var params = {Id: parametrsAction, ModelName: model};
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
    var params = {ModelName:ModelName, Id: id};
    var historyData = CallPhpFunctionAjax(conrollerName, historyAction, "JSONOBJECT", params)
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
    CallPhpFunctionAjax(controllerName, "RecoveryFromHistory", "POSTOBJECT", {Id:id})
}







