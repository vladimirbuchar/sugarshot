function AddToCart(productId, validateItem, errorMessage)
{
    var validValue = "";
    if (validateItem != "")
    {
        validValue = $("#"+validateItem).val();
        if ($("#"+validateItem+" option").length > 1 )
        {
        if (validValue =="" || IsUndefined(validValue))
        {
            alert(errorMessage);
            return false;
        }
      }
    }
    var productCount = $("#ProductCount-"+productId).val();
    var productMax = $("#ProductCount-"+productId).data("max");
    if (productCount > productMax)
    {
        productCount = productMax;
        $("#ProductCount-"+productId).val(productCount);
    }
    if (productCount < 1)
    {
        productCount = 1;
        $("#ProductCount-"+productId).val(productCount);
    }
    
    var obj = {ProductId:productId,Count:productCount,SelectVariant:validValue};
    CallPhpFunctionAjax("Shop","AddProductToCart","POSTOBJECT",obj);
    return true;
}