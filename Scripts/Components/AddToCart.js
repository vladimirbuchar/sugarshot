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
    
    var params = new Array();
    var ar1 = new Array();
    ar1[0] = "ProductId";
    ar1[1] = productId;
    params[0] = ar1;
    
    var ar2 = new Array();
    ar2[0] = "Count";
    ar2[1] = $("#ProductCount-"+productId).val();
    params[1] = ar2;
    var ar3 = new Array();
    ar3[0] = "SelectVariant";
    ar3[1] = validValue;
    params[2] = ar3;
    CallPhpFunctionAjax("Shop","AddProductToCart","POST",params);
    //window.location.href = cartUrl;
    return true;
}