var FiltrItems = new Array();
var sortMode = new Array();

function ReloadListPage(valueDivId,seoUrl,action)
{
    var defaultSettings = new Array();
    var params = new Array();
    params[0] = "action";
    params[1] = action;
    defaultSettings[0] = params;
    
    var params1 = new Array();
    params1[0] = "seoUrl";
    params1[1] = seoUrl;
    defaultSettings[1] = params1;
    
    var params2 = new Array();
    params2[0] = "divId";
    params2[1] = valueDivId;
    defaultSettings[2] = params2;
    
    /*LIMIT */
    var limit = new Array();
    
    var paramsLimit = new Array();
    paramsLimit[0] = "showPager";
    paramsLimit[1] = $("#showPager").val();
    limit[0] = paramsLimit;
    
    var pagerNextLoadItems = new Array();
    pagerNextLoadItems[0] = "pagerNextLoadItems";
    pagerNextLoadItems[1] = $("#pagerNextLoadItems").val();
    limit[1] = pagerNextLoadItems;
    
    var pagerLoadItems = new Array();
    if (action == "limit")
    {
        pagerLoadItems[0] = "pagerLoadItems";
        pagerLoadItems[1] = parseInt($("#pagerLoadItems").val())+parseInt($("#pagerNextLoadItems").val());
    }
    else
    {
        pagerLoadItems[0] = "pagerLoadItems";
        pagerLoadItems[1] = $("#pagerLoadItems").val();
    }
    limit[2] = pagerLoadItems;
    
    /** SORT*/
    var sort = new Array();
    var showSort = new Array();
    showSort[0] = "showSort";
    showSort[1] = $("#showSort").val();
    sort[0] = showSort;
    
    var sortDomain = new Array();
    sortDomain[0] = "sortDomain";
    sortDomain[1] = $("#sortDomain").val();
    sort[1] = sortDomain;
    
    var showSortByName = new Array();
    showSortByName[0] = "showSortByName";
    showSortByName[1] = $("#showSortByName").val();
    sort[2] = showSortByName;
    
    var wordSortByName = new Array();
    wordSortByName[0] = "wordSortByName";
    wordSortByName[1] = $("#wordSortByName").val();
    sort[3] = wordSortByName;
    
    var sortASC = new Array();
    sortASC[0] = "sortASC";
    sortASC[1] = $("#sortASC").val();
    sort[4] = sortASC;
    
    var sortDESC = new Array();
    sortDESC[0] = "sortDESC";
    sortDESC[1] = $("#sortDESC").val();
    sort[5] = sortDESC;
    
    var sortQuery = new Array();
    sortQuery[0] = "sortQuery";
    if (action == "sort")
    {
        sortQuery[1] = $("#selectSort").val();
    }
    else 
        sortQuery[1] = $("#sortQuery").val();
    sort[6] = sortQuery;
    
    var out = CallPhpFunctionAjax("ArticleList","Filter","GET",defaultSettings,limit,sort);
    $("#"+valueDivId).html(out);
}



/// ostan í 
/*function AddLimit(valueDivId,parent)
{
    var params = new Array();
    params[0] ="add";
    params[1] =parent;
    
   StartFiltr(FiltrItems,"",params,valueDivId); 
}


function SetFiltrBetween(column,value1,value2,position,parent,valueDivId)
{
    var params = new Array();
    params[0] = "BETWEEN";
    params[1] = column;
    params[2] = parent;
    params[3] = value1;
    params[4] = value2;
    params[5] ="";
    FiltrItems[position] = params;
    StartFiltr(FiltrItems,"","none",valueDivId);
}

function SetFilterText(column,value1,position,parent,valueDivId,mode)
{
    var params = new Array();
    params[0] = mode;
    params[1] = column;
    params[2] = parent;
    params[3] = value1;
    params[4]  ="";
    params[5] ="";
    FiltrItems[position] = params;
    StartFiltr(FiltrItems,"","none",valueDivId);
}
function SetFilterMN(column,value1,value2,position,parent,valueDivId,groupName)
{
    var params = new Array();
    params[0] = "SelectMN";
    params[1] = column;
    params[2] = parent;
    params[3] = value1;
    params[4]  =value2;
    params[5] =groupName;
    FiltrItems[position] = params;
    StartFiltr(FiltrItems,"","none",valueDivId);
}
function SetFilter1N(column,value1,value2,position,parent,valueDivId)
{
    var params = new Array();
    params[0] = "Select1N";
    params[1] = column;
    params[2] = parent;
    params[3] = value1;
    params[4]  =value2;
    params[5] ="";
    FiltrItems[position] = params;
    StartFiltr(FiltrItems,"","none",valueDivId);
}
function Sort(column,valueDivId,mode,parent)
{
    var params = new Array();
    params[0] =column;
    params[1] =mode;
    params[2] = parent;
    sortMode[0] = params;
    StartFiltr(FiltrItems,sortMode,"none",valueDivId);
}


function StartFiltr(params,sort,limit,valueDivId)
{
    var out = CallPhpFunctionAjax("ArticleList","Filter","GET",params,sort,limit);
    $("#"+valueDivId).html(out);
    
}*/