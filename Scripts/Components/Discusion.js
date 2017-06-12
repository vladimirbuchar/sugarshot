function AddDiscusionItem()
{
    var params = PrepareParametrs("itemForm");
    var nextItem = params.length;
    var ar1 =  new Array();
    ar1[0] = "DiscusionId";
    ar1[1] = $("#DiscusionId").val();
    params[nextItem] = ar1;
    nextItem++;
    CallPhpFunctionAjax("Ajax","AddDiscusionItem","POST",params);   
 }
    
function LoadDiscusionItemDetail(id)
{
    var params = new Array();
    var ar1 =  new Array();
    ar1[0] = "DiscusionItem";
    ar1[1] = id;
    params[0] = ar1;
    var data = CallPhpFunctionAjax("Ajax","DiscusionItemDetail","JSON",params);   
    WriteData(data);
}
function SetParentId(parentId,id)
{
    $("#ParentIdDiscusion").val(parentId);
    $("#Id").val(id);
}
function DeleteDiscusionItem(id)
    {
        var params = new Array();
        var ar1 =  new Array();
        ar1[0] = "DiscusionItem";
        ar1[1] = id;
        params[0] = ar1;
        CallPhpFunctionAjax("Ajax","DeleteDiscusionItem","POST",params);   
        LoadDiscusion();   
    }
    
    function ShowHistoryDiscusionItems(id)
    {
        var params = new Array();
        var ar1 =  new Array();
        ar1[0] = "DiscusionItem";
        ar1[1] = id;
        params[0] = ar1;
        var data = CallPhpFunctionAjax("Ajax","HistoryItemDetail","JSON",params);   
        var html = "";
         html += "<tr>";
         html += "<th> "+GetWord("word407");
         html += "</th>";
         html += "<th> "+GetWord("word408");
         html += "</th>";
                  html += "<th> "+GetWord("word409");
         html += "</th>";
         html += "</tr>";
        for (var i =0; i<data.length; i++)
        {
            html+="<tr>";
                html+="<td>";
                    html+=data[i].SubjectDiscusion;
                html+="</td>";
                html+="<td>";
                    html+=data[i].TextDiscusion;
                html+="</td>";
                html+="<td>";
                    html+=data[i].DateTime;
                html+="</td>";
            html+="</tr>";
        }
        $("#itemDiscusionHistory").html(html);
    }
    function BlockDiscusionUser(userId)
    {
        var params = new Array();
        var ar1 =  new Array();
        ar1[0] = "UserId";
        ar1[1] = userId;
        params[0] = ar1;
        CallPhpFunctionAjax("WebEdit","BlockDiscusionUser","POST",params);   
        window.location.href="";
    }
    