
function AlternativeContent()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetAlernativeArticle", "POSTOBJECT", null);
        $("#dialogComponentAlternativeContent").html(html);
    }
    function ShowAddReletedDocuments()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetReletedArticle", "POSTOBJECT", null);
        $("#dialogComponent").html(html);
    }
    function AddGalleryFromRepository()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryList", "POSTOBJECT", null);
        $("#dialogComponentGallery").html(html);
    }
    
    function AddGalleryFromArticle()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryFromArticle", "POSTOBJECT", null);
        $("#dialogComponentArticleGallery").html(html);
    }
    function AddDiscusionFromArticle()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryFromArticle", "POSTOBJECT", null);
        $("#dialogComponentDiscusionGallery").html(html);
    }
    function HideChildSettings(hide)
    {
        if (hide)
        {
            $("#UseTemplateInChild").parent().parent().hide();
            $("#CopyDataToChild").parent().parent().hide();
            $("#ChildTemplate").parent().parent().hide();
            
            $("#ActivatePager").parent().parent().hide();
            $("#FirstItemLoadPager").parent().parent().hide();
            $("#NextItemLoadPager").parent().parent().hide();
            $("#NoLoadSubitems").parent().parent().hide();
        }
        else 
        {
            $("#UseTemplateInChild").parent().parent().show();
            $("#CopyDataToChild").parent().parent().show();
            $("#ChildTemplate").parent().parent().show();
            $("#ActivatePager").parent().parent().show();
            $("#FirstItemLoadPager").parent().parent().show();
            $("#NextItemLoadPager").parent().parent().show();
            $("#NoLoadSubitems").parent().parent().show();
        }
    }
    
    $(document).ready(function(){
     $("#NoChild").click(function(){
        HideChildSettings($(this).is(":checked"));
     });
        HideChildSettings($("#NoChild").is(":checked"));
    
        $("#OtherLang").change(function(){
            Save(false,false);
            SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/Detail/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
        
       $('#ActiveFrom').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        $('#ActiveTo').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        $("#Template").change(function(){
            var lasttemlate = $("#lastTemplate").val();
            
            var value = $(this).val();
            if (lasttemlate != value)
            {
                if (lasttemlate == "")
                {
                    LoadDomainItems(value);
                }
                else 
                {
                    if (confirm(GetWord("word518")))
                    {
                        UnregisterHtmlEditor();
                        LoadDomainItems(value);
                    }
                    else 
                    {
                        $("#Template").val(lasttemlate);
                    }
                }
                $("#lastTemplate").val(value);
            }
            
            
        });
        $("#mediaGallerySettings").change(function(){
            var value = $(this).val();
            ShowSesttingGallery(value);
        });
        $("#discussionSettings").change(function(){
           var value = $(this).val();
           DiscusionSeting(value);
        });
        $("#GoToDiscusion").click(function(){
            SetIgnoreExit(true);
            Save(false,false);
            var parmetrs = {Id: $("#ObjectId").val()};  
            
            var discusionId = CallPhpFunctionAjax("WebEdit","GetArticleDiscusion","POSTOBJECT",parmetrs);
            Redirect("WebEdit", "Discusion", "xadm",$("#WebId").val(), $("#LangId").val(), discusionId)
        });
    });
    
    function ShowSesttingGallery(mode)
    {
        if (mode == 0)
        {
            $("#AddFromRepository").hide();
            $("#galleryObjectTable").hide();
            $("#AddFromArticle").hide();
            $("#UploadFile").hide();
        }
        else if (mode == 1)
        {
            $("#AddFromRepository").show();
            $("#galleryObjectTable").show();
            $("#AddFromArticle").hide();
            $("#UploadFile").show();
        }
        else if (mode == 2 || mode == 3)
        {
            $("#AddFromArticle").show();
            $("#AddFromRepository").hide();
            $("#galleryObjectTable").show();
            $("#UploadFile").hide();
        }        
    }
    
    function GetRelatedArticle()
    {
        var data = GetRelatedObjects("document,link");
        var html  = "<tr><th>"+GetWord("word493")+"</th><th></th></tr>";
        for(var i = 0; i<data.length; i++)
        {
            var name = data[i].Name;
            var id = data[i].ConnectionId;
            html +="<tr>";
            html +="<td>";
            html+= name;
            html +="</td>";
            html +="<td>";
            html+= "<input type='button' value='"+GetWord("word494")+"' onclick='DeleteRelation(\""+id+ "\")' class='btn btn-default' /> ";
            html +="</td>";
            
            html +="</tr>";
            
        }
        $("#reletedObjectTable").html(html);
    }
    
    function GetGalleryItems()
    {
        var data = GetRelatedObjects("gallery");
        var html  = "<tr><th>"+GetWord("word495")+"</th><th></th></tr>";
        for(var i = 0; i<data.length; i++)
        {
            var name = data[i].Name;
            var id = data[i].ConnectionId;
            html +="<tr>";
            html +="<td>";
            html+= name;
            html +="</td>";
            html +="<td>";
            html+= "<input type='button' value='"+GetWord("word388")+"' onclick='DeleteRelation(\""+id+ "\")' class='btn btn-default' /> ";
            html +="</td>";
            
            html +="</tr>";
            
        }
        $("#galleryObjectTable").html(html);
    }
    
    function GetRelatedObjects(objectType)
    {
        var parmetrs = {ObjectId: $("#ObjectId").val(), ObjectType: objectType};
        return CallPhpFunctionAjax("WebEdit","GetRelatedObject","JSONOBJECT",parmetrs);
    }
    
    function LoadDomainItems(value,data,readOnly )
    {
        
        var param = {Id:value,ObjectId:  $("#ObjectId").val()};
        
        if (IsUndefined(readOnly))
        {
            readOnly = false;
        }
        else 
        {
            if ($("#ObjectId").val() == 0)
                readOnly= false;
            else 
            {
                if(readOnly)
                   readOnly= false;
                else 
                    readOnly = true;
            }
        }
        param.ReadOnly = readOnly;
        
        var outData = CallPhpFunctionAjax("WebEdit","GetDomainFromTemplate","JSON",param);
        var html = outData["Html"];
        var xml = outData["TemplateSettings"];
        xmlDoc = $.parseXML(xml);
        $xml = $(xmlDoc);
        
        $xmlItemHideRelatedItems = $xml.find("hideRelatedItems");
        var valueHideRelatedItems = $xmlItemHideRelatedItems.text();
        if (valueHideRelatedItems == 1)
        {
            $("#liTab4").addClass("dn");
            $("#tab-4").addClass("dn");
        }
        else 
        {
            $("#liTab4").removeClass("dn");
            $("#tab-4").removeClass("dn");
        }
        
        $xmlItemHideMediaGallery = $xml.find("hideMediaGallery");
        var valueHideMediaGallery = $xmlItemHideMediaGallery.text();
        if (valueHideMediaGallery == 1)
        {
            $("#liTab6").addClass("dn");
            $("#tab-6").addClass("dn");
        }
        else 
        {
            $("#liTab6").removeClass("dn");
            $("#tab-6").removeClass("dn");
        }
        
        $xmlItemHideOthersObjects = $xml.find("hideOthersObjects");
        var valueHideOthersObjects = $xmlItemHideOthersObjects.text();
        if (valueHideOthersObjects == 1)
        {
            $("#liTab7").addClass("dn");
            $("#tab-7").addClass("dn");
        }
        else 
        {
            $("#liTab7").removeClass("dn");
            $("#tab-7").removeClass("dn");
        }
        
        $xmlItemHideAlernativeObjects = $xml.find("hideAlernativeObjects");
        var valueHideAlernativeObjects = $xmlItemHideAlernativeObjects.text();
        if (valueHideAlernativeObjects == 1)
        {
            $("#liTab10").addClass("dn");
            $("#tab-10").addClass("dn");
        }
        else 
        {
            $("#liTab10").removeClass("dn");
            $("#tab-10").removeClass("dn");
        }
        
        $("#parametrs").html(html);
        
    }
    function Save(publish,checkValid)
    {
        ShowLoading();
        if (checkValid)
        {
            
            if (!ValidateForm("parametrs"))
                return;
        }
        
        var params = PrepareParametrs("itemForm");
        params.Publish = publish; 
        params.Id = $("#ObjectId").val(); 
        
        var privileges = ReadUserPrivileges("userSecurity");
        params.Privileges = privileges;
        
        var domainValues = PrepareParametrs("parametrs");
        params.Parametrs = domainValues;
        params.GallerySettings = $("#mediaGallerySettings").val();
        params.DiscusionSettings = $("#discussionSettings").val();
        params.FormSettings = $("#formSettings").val();
        params.InquerySettings = $("#Inquery").val();
        params.Discusion = $("#Discusion").val();
        
        var outId = CallPhpFunctionAjax("WebEdit","SaveUserItem","POST",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"useritem");
        HideLoading();
        var url = window.location.href;
        url = url.replace("/0/","/"+outId+"/");
        history.pushState('data','',url);
    }
    function AddArticle(mode)
    {
        var parmetrs = {ObjectId: $("#ObjectId").val(),Data:""};
        
        
        
        var ObjectIdConnection = 0;
        if (mode == "document")
        {
            ObjectIdConnection = GetSelectTree("ReletedArticle");
            parmetrs.ObjectIdConnection = ObjectIdConnection;
            
            
        }
        else if (mode == "link")
        {
            parmetrs.ObjectIdConnection = ObjectIdConnection;
            parmetrs.Data = $f.get(0).contentWindow.GetLinkSetting();
        }
        else if (mode == "gallery")
        {
            if ($("#mediaGallerySettings").val() == "1")
            {
                ObjectIdConnection = GetSelectTree("GalleryItemDialog");
                parmetrs.ObjectIdConnection = ObjectIdConnection;
            }
            else if ($("#mediaGallerySettings").val() == 2 || $("#mediaGallerySettings").val() == 3)
            {
                ObjectIdConnection = GetSelectTree("SelectDialogGalleryArticle");
                parmetrs.ObjectIdConnection = ObjectIdConnection;
            }
        }
        parmetrs.Mode = mode;
        
        Save(false,false);
        if (mode != "discusion")
        {
            CallPhpFunctionAjax("WebEdit","ConnectObject","POSTOBJECT",parmetrs);
            if (mode == "document" || mode == "link")
            {
                GetRelatedArticle();
            }
            else if (mode == "gallery")
            {
                GetGalleryItems();
            }
        }
    }
    function SetMode()
    {
        if ($("#tab-4").hasClass("active"))
        {
            var $f = $(".SelectDialog");
            var value = $f.get(0).contentWindow.GetTab();
            if (value == 1)
                return "document";
            if (value == 3)
                return "link";
        }
        if ($("#tab-5").hasClass("active"))
        {
            return "discusion";
        }   
        if ( $("#tab-6").hasClass("active"))
        {
            return "gallery";
        }   
    }
    function DeleteRelation(id)
    {
        var parmetrs = {ObjectId:id}
        
        CallPhpFunctionAjax("WebEdit","DisconnectObject","POSTOBJECT;",parmetrs);
        if ($("#tab-3").hasClass("active"))
        {
            GetRelatedArticle();
        }
        if ($("#tab-6").hasClass("active"))
        {
            GetGalleryItems();
        }
    }
    
    function DiscusionSeting(item)
    {
        if (item == 0)
        {
            $("#AddFromArticleDiscusion").hide();
            $("#GoToDiscusion").hide();
        }
        else if (item == 1)
        {
            $("#AddFromArticleDiscusion").hide();
            $("#GoToDiscusion").show();
        }
        else if (item == 2)
        {
            $("#AddFromArticleDiscusion").show();
            $("#GoToDiscusion").show();
        }
        else if (item == 3)
        {
            $("#GoToDiscusion").show();
            $("#AddFromArticleDiscusion").show();
        }
    }
    function ShowPreview()
    {
        Save(false,false);
        var url = CallPhpFunctionAjax("WebEdit","GetArticleUrl","POSTOBJECT",{ObjectId:$("#ObjectId").val()});
        window.open(url+"?preview=true");
    }
    function SaveAlternativeItem()
    {
        var objectId = $("#ObjectId").val();
        var userGroup = $("#AlternativeItemUserGroup").val();
        if (userGroup == "" || IsUndefined(userGroup) || IsUndefined(objectId))
        {
            GetWord("word766");
        }
        Save(false,false);
        
        var ObjectIdConnection = GetSelectTree("AlternativeArticle");
        var params = {ObjectId:objectId,UserGroup:userGroup,AlternativeItem:ObjectIdConnection};
        
        CallPhpFunctionAjax("WebEdit","AddAlternativeItem","POSTOBJECT",params);
        GetAlternativeItem();
    }
    
    function GetAlternativeItem()
    {    
        var data = CallPhpFunctionAjax("WebEdit","GetAlternativeItem","JSONOBJECT",{ObjectId: $("#ObjectId").val()});
        var html  = "<tr><th>"+GetWord("word680")+"</th><th>" +GetWord("word681")+"</th><th>&nbsp;</th></tr>";
        for(var i = 0; i<data.length; i++)
        {
            var name = data[i].ItemName;
            var id = data[i].Id;
            var groupName = data[i].GroupName;
            html +="<tr>";
            html +="<td>";
            html+= groupName;
            html +="</td>";
            html +="<td>";
            html+= name;
            html +="</td>";
            html +="<td>";
            html+= "<input type='button' value='"+GetWord("word388")+"' onclick='DeleteAlternativeItems(\""+id+ "\")' class='btn btn-default' /> ";
            html +="</td>";
            html +="</tr>";
        }
        $("#alternativeItems").html(html);
    }
    function DeleteAlternativeItems(id){
        CallPhpFunctionAjax("WebEdit","DeleteAlternativeItems","GETOBJECT",{id:id});
        GetAlternativeItem();
    }
    function UploadFiles()
    {
        Save(false,false);
        var langId =  $("#LangId").val();
        var webId =  $("#WebId").val();
        var id = CallPhpFunctionAjax("WebEdit","GetRootId","GETOBJECT");
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
                },
                
            }).done(function(ObjectIdConnection){
                var parmetrs = {ObjectId: $("#ObjectId").val(),ObjectIdConnection:ObjectIdConnection,Data:"",Mode:"gallery"}
                 CallPhpFunctionAjax("WebEdit","ConnectObject","POSTOBJECT",parmetrs);
                 GetGalleryItems();   
            
        
            });
        }
    }
    
    