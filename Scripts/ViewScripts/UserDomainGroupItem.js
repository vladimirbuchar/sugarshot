function CallAfterSaveFunction(id)
{
    var domainItems = $(".domainItems");
    var inArray = new Array();
    var x =0;
    for (var i = 0;i< domainItems.length; i++)
    {
        var item = $(domainItems[i]);
        if (item.is(":checked"))
        {
           var ar = new Array();
           ar[0] = item.attr("id");
           ar[1] = id;
           inArray[x] = ar;
           x++;
        }
    }
    CallPhpFunctionAjax("Settings","AddDomainItemToGroup","POST",inArray);
}
function OnLoadItemDetail() 
{
    var id = $("#Id").val();
    var data = CallPhpFunctionAjax("Settings","GetIntemsInDomainGroup","JSON",id);
    for(var i = 0; i< data.length;i++)
    {
        $("#"+data[i].ItemId).attr("checked","checked");
    }
}