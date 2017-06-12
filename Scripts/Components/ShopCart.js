$(document).ready(function(){
    
    DisableShopCart();
    if (IsEmptyCart())
    {
        $(".shopButtons").hide();
    }
    $(".formClass input").keypress(function(e) {
        if(e.which == 13) {
            return false;
        }
    });
});
function Recount(productId)
{
    
    var params = new Array();
    var ar1 = new Array();
    ar1[0] = "ProductId";
    ar1[1] = productId;
    params[0] = ar1;
    
    var ar2 = new Array();
    ar2[0] = "Count";
    ar2[1] = $("#"+productId).val();
    params[1] = ar2;
    
    var ar3 = new Array();
    ar3[0] = "Currency";
    ar3[1] = $("#Currency").val();
    params[2] = ar3;
    
    var ar4 = new Array();
    ar4[0] = "PriceFormat";
    ar4[1] = $("#PriceFormat").val();
    params[3] = ar4;
    
    var out =  CallPhpFunctionAjax("Shop","RecountProduct","JSON",params);
    $("#"+productId+"-productPrice").html(out.Price1ks);
    $("#"+productId+"-productPriceVat").html(out.PriceVat1ks);
    $("#"+productId+"-productPriceCount").html(out.PriceCount);
    $("#"+productId+"-productPriceVatCount").html(out.PriceVatCount);
    $("#sumaPrice").html(out.ProductPriceFormated);
    $("#sumaPriceVat").html(out.ProductPriceVatFormated)
    
    
    $("#preview-"+productId+"-productPrice").html(out.Price1ks);
    $("#preview-"+productId+"-productPriceVat").html(out.PriceVat1ks);
    $("#preview-"+productId+"-productPriceCount").html(out.PriceCount);
    $("#preview-"+productId+"-productPriceVatCount").html(out.PriceVatCount);
    
    $("#preview-product-"+productId+"-Count").html($("#"+productId).val());
    
    $("#preview-sumaPrice").html(out.ProductPriceFormated);
    $("#preview-sumaPriceVat").html(out.ProductPriceVatFormated)
    
    
    
}

function DeleteProduct(productId)
{
    var params = new Array();
    var ar1 = new Array();
    ar1[0] = "ProductId";
    ar1[1] = productId;
    params[0] = ar1;
    
    var ar3 = new Array();
    ar3[0] = "Currency";
    ar3[1] = $("#Currency").val();
    params[1] = ar3;
    
    var ar4 = new Array();
    ar4[0] = "PriceFormat";
    ar4[1] = $("#PriceFormat").val();
    params[2] = ar4;
    
    var out = CallPhpFunctionAjax("Shop","DeleteProductFromCart","JSON",params);
    //alert(out.ProductPriceFormated);
    $("#sumaPrice").html(out.ProductPriceFormated);
    $("#sumaPriceVat").html(out.ProductPriceVatFormated)
    $("#product-"+productId).remove();
    
    $("#preview-sumaPrice").html(out.ProductPriceFormated);
    $("#preview-sumaPriceVat").html(out.ProductPriceVatFormated)
    $("#preview-product-"+productId).remove();
    var productCount = $(".cartItems .cartItem").length;
    
    if (productCount == 0)
    {
        $("#cart").addClass("dn");
        $("#emptycart").removeClass("dn");   
        $(".shopButtons").hide();
        DisableShopCart();
    }
    
    
}
function ChangeTransport(el)
{
    $(el).each(function() {
        var price = $(this).data('transportprice');
        var objectid = $(this).data('objectid');
        var obj = {price:price,objectid:objectid,PriceFormat:$("#PriceFormat").val(),Currency:$("#Currency").val()};
        var out = CallPhpFunctionAjax("Shop","SelectTransport","JSONOBJECT",obj);
        SetSumaPrice(out);
    });
}

function ChangePayment(el)
{
    $(el).each(function() {
        var price = $(this).data('paymentprice');
        var objectid = $(this).data('objectid');
        var obj = {price:price,objectid:objectid,PriceFormat:$("#PriceFormat").val(),Currency:$("#Currency").val()};
        var out = CallPhpFunctionAjax("Shop","SelectPayment","JSONOBJECT",obj);
        SetSumaPrice(out);
        
    });
}

function SetSumaPrice(prices)
{
    $("#SumaPriceWithOutVat").html(prices.PriceFormat);
    $("#SumaPriceWithVat").html(prices.PriceVatFormat);
}

function IsEmptyCart()
{
    return  CallPhpFunctionAjax("Shop","IsEmptyCart","POST");      
}
function DisableShopCart()
{
    $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
        if ($(this).hasClass("disabled")) {
            e.preventDefault();
        return false;
    }
    
    });
}

