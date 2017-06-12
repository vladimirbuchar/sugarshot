    function SetCopyMoveAction(action)
    {
        copyModeAction = action;
    }
    
    function UploadFiles()
    { 
        var langId =  $("#LangId").val();
        var webId =  $("#WebId").val();
        var selectedObject = $(".selected");
        var id = selectedObject.attr("id");
        if(IsUndefined(id)) id = selectId;
        else id = id.replace("_anchor", "");
        
        
        $.ajaxSetup({async: false});
        var filesCount = $("#FileUploader").prop('files').length;
        for (var i = 0; i<filesCount; i++ )
        {
            var file_data = $("#FileUploader").prop('files')[i];
            var form_data = new FormData();
            form_data.append('file', file_data)
            $.ajax({
                url: '/filesupload/'+langId+"/"+id+"/"+webId+"/", // point to server-side PHP script 
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (filepath) {
                    value = filepath;
                }
            });
        }
    }
    
    