function AddDiscusionItem()
{
    var params = PrepareParametrs("itemForm");
    params.DiscusionId = DiscusionId:$("#DiscusionId").val();
    CallPhpFunctionAjax("Ajax","AddDiscusionItem","POSTOBJECT",params);   
}
    
function LoadDiscusionItemDetail(id)
{
    var params = {DiscusionItem:id}
    var data = CallPhpFunctionAjax("Ajax","DiscusionItemDetail","JSONOBJECT",params);   
    WriteData(data);
}
function SetParentId(parentId,id)
{
    $("#ParentIdDiscusion").val(parentId);
    $("#Id").val(id);
}
function DeleteDiscusionItem(id)
    {
        var params = {DiscusionItem:id}
        CallPhpFunctionAjax("Ajax","DeleteDiscusionItem","POSTOBJECT",params);   
        LoadDiscusion();   
    }
    
    function ShowHistoryDiscusionItems(id)
    {
        var params = {DiscusionItem:id};
        var data = CallPhpFunctionAjax("Ajax","HistoryItemDetail","JSONO|BJECT",params);   
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
        var params = {UserId:userId}
        CallPhpFunctionAjax("WebEdit","BlockDiscusionUser","POSTOBJECT",params);   
        window.location.href="";
    }
    