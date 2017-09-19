function CallAfterSaveFunction(id)
{
    var domainItems = $(".domainItems");
    var inArray = {};
    var x =0;
    for (var i = 0;i< domainItems.length; i++)
    {
        var item = $(domainItems[i]);
        if (item.is(":checked"))
        {
           
           var name = item.attr("id");
           var value = id;
           inArray.name = value;
           x++;
        }
    }
    CallPhpFunctionAjax("Settings","AddDomainItemToGroup","POSTOBJECT",inArray);
}
function OnLoadItemDetail() 
{
    var id = $("#Id").val();
    var data = CallPhpFunctionAjax("Settings","GetIntemsInDomainGroup","JSONOBJECT",{id:id});
    for(var i = 0; i< data.length;i++)
    {
        $("#"+data[i].ItemId).attr("checked","checked");
    }
}