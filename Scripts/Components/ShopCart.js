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
    var productCount = $("#"+productId).val();
    var productMax = $("#"+productId).data("max");
    if (productCount > productMax)
    {
        productCount = productMax;
        $("#"+productId).val(productCount);
    }
    if (productCount < 1)
    {
        productCount = 1;
        $("#"+productId).val(productCount);
    }
    var params = {ProductId:productId, Count:productCount,Currency:$("#Currency").val(),PriceFormat:$("#PriceFormat").val() };
    
    var out =  CallPhpFunctionAjax("Shop","RecountProduct","JSONOBJECT",params);
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
    var params = {ProductId:productId,Currency:$("#Currency").val(),PriceFormat:$("#PriceFormat").val()}
    var out = CallPhpFunctionAjax("Shop","DeleteProductFromCart","JSONOBJECT",params);
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

