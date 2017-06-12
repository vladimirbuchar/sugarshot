
function AlternativeContent()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetAlernativeArticle", "POST", null);
        $("#dialogComponentAlternativeContent").html(html);
    }
    function ShowAddReletedDocuments()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetReletedArticle", "POST", null);
        $("#dialogComponent").html(html);
    }
    function AddGalleryFromRepository()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryList", "POST", null);
        $("#dialogComponentGallery").html(html);
    }
    
    function AddGalleryFromArticle()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryFromArticle", "POST", null);
        $("#dialogComponentArticleGallery").html(html);
    }
    function AddDiscusionFromArticle()
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetGalleryFromArticle", "POST", null);
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
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
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
            var parmetrs = new Array();
            var ar1 = new Array();
            ar1[0] = "Id";
            ar1[1] = $("#ObjectId").val();
            parmetrs[0] =ar1;
            var discusionId = CallPhpFunctionAjax("WebEdit","GetArticleDiscusion","POST",parmetrs);
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
        var parmetrs = new Array();
        var ar1 = new Array();
        ar1[0] = "ObjectId";
        ar1[1] = $("#ObjectId").val();
        parmetrs[0] =ar1;
        var ar2 = new Array();
        ar2[0] = "ObjectType";
        ar2[1] = objectType;
        parmetrs[1] =ar2;
        return CallPhpFunctionAjax("WebEdit","GetRelatedObject","JSON",parmetrs);
    }
    
    function LoadDomainItems(value,data,readOnly )
    {
        
        var param = Array();
        var ar = Array();
        ar[0] = "Id";
        ar[1] = value;
        param[0] = ar;
        
        var ar1 = Array();
        ar1[0] = "ObjectId";
        ar1[1] = $("#ObjectId").val();
        param[2] = ar1;    
        
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
        var ar2 = Array();
        ar2[0] = "ReadOnly";
        ar2[1] = readOnly;
        param[1] = ar2;   
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
        
        var nextItem = params.length;
        var ar1 =  new Array();
        ar1[0] = "Publish";
        ar1[1] = publish;
        params[nextItem] = ar1;
        nextItem++;
        var ar2 = new Array();
        ar2[0] = "Id";
        ar2[1] = $("#ObjectId").val();
        params[nextItem] = ar2;
        nextItem++;
        var privileges = ReadUserPrivileges("userSecurity");
        var ar3 = new Array();
        ar3[0] = "Privileges";
        ar3[1] = privileges;
        params[nextItem] = ar3;
        nextItem++;
        
        var domainValues = PrepareParametrs("parametrs");
        
        var ar4 = new Array();
        ar4[0] = "Parametrs";
        ar4[1] = domainValues;
        params[nextItem] = ar4;
        nextItem++;
        var ar5 = new Array();
        ar5[0] = "GallerySettings";
        ar5[1] = $("#mediaGallerySettings").val();
        params[nextItem] = ar5;
        nextItem++;
        var ar6 = new Array();
        ar6[0] = "DiscusionSettings";
        ar6[1] = $("#discussionSettings").val();
        params[nextItem] = ar6;
        nextItem++;
        
        var ar7 = new Array();
        ar7[0] = "FormSettings";
        ar7[1] = $("#formSettings").val();
        params[nextItem] = ar7;
        nextItem++;
        
        var ar8 = new Array();
        ar8[0] = "InquerySettings";
        ar8[1] = $("#Inquery").val();
        params[nextItem] = ar8;
        nextItem++;
        
        var ar9 = new Array();
        ar9[0] = "Discusion";
        ar9[1] = $("#Discusion").val();
        params[nextItem] = ar9;
        nextItem++;

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
        var parmetrs = new Array();
        var ar1 = new Array();
        ar1[0] = "ObjectId";
        ar1[1] = $("#ObjectId").val();
        parmetrs[0] =ar1;
        
        var ar2 = new Array();
        var ar3 = new Array();
        ar3[0] = "Data";
        ar3[1] = "";
        var ObjectIdConnection = 0;
        if (mode == "document")
        {
            //var $f = $(".SelectDialog");
            ObjectIdConnection = GetSelectTree("ReletedArticle");
            ar2[0] = "ObjectIdConnection";
            ar2[1] = ObjectIdConnection;
            
        }
        else if (mode == "link")
        {
            ar2[0] = "ObjectIdConnection";
            ar2[1] = ObjectIdConnection;
            ar3[1] = $f.get(0).contentWindow.GetLinkSetting();
        }
        else if (mode == "gallery")
        {
            if ($("#mediaGallerySettings").val() == "1")
            {
                ObjectIdConnection = GetSelectTree("GalleryItemDialog");
                ar2[0] = "ObjectIdConnection";
                ar2[1] = ObjectIdConnection;
                //alert(ObjectIdConnection);
            }
            else if ($("#mediaGallerySettings").val() == 2 || $("#mediaGallerySettings").val() == 3)
            {
                var $f = $(".SelectDialogGalleryArticle");
                ObjectIdConnection = $f.get(0).contentWindow.GetSelectTree();
                ar2[0] = "ObjectIdConnection";
                ar2[1] = ObjectIdConnection;
            }
        }
        /*else if (mode == "discusion")
        {
            ObjectIdConnection = GetSelectTree("SelectDialogGalleryArticle");
            ObjectIdConnection = CallPhpFunctionAjax("WebEdit","GetDiscusionByItemId","POST",ObjectIdConnection);
            $("#DiscusionId").val(ObjectIdConnection);
        }*/
        
        parmetrs[1] =ar2;
        parmetrs[2] =ar3;
        var ar4 = new Array();
        ar4[0] = "Mode";
        ar4[1] = mode;
        parmetrs[3] =ar4;
        Save(false,false);
        if (mode != "discusion")
        {
            CallPhpFunctionAjax("WebEdit","ConnectObject","POST",parmetrs);
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
        var parmetrs = new Array();
        var ar1 = new Array();
        ar1[0] = "ObjectId";
        ar1[1] = id;
        parmetrs[0] =ar1;
        CallPhpFunctionAjax("WebEdit","DisconnectObject","POST",parmetrs);
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
        var url = CallPhpFunctionAjax("WebEdit","GetArticleUrl","POST",$("#ObjectId").val());
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
        var params = new Array();
        var ar1 = new Array();
        ar1[0] = "ObjectId";
        ar1[1] = objectId;
        params[0] = ar1;
        
        var ar2 = new Array();
        ar2[0] = "UserGroup";
        ar2[1] = userGroup;
        params[1] = ar2;
        
        var ar3 = new Array();
        ar3[0] = "AlternativeItem";
        ar3[1] = ObjectIdConnection;
        params[2] = ar3;
        CallPhpFunctionAjax("WebEdit","AddAlternativeItem","POST",params);
        GetAlternativeItem();
    }
    
    function GetAlternativeItem()
    {    
        var data = CallPhpFunctionAjax("WebEdit","GetAlternativeItem","JSON",$("#ObjectId").val());
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
        CallPhpFunctionAjax("WebEdit","DeleteAlternativeItems","GET",id);
        GetAlternativeItem();
    }
    function UploadFiles()
    {
        Save(false,false);
        var langId =  $("#LangId").val();
        var webId =  $("#WebId").val();
        var id = CallPhpFunctionAjax("WebEdit","GetRootId","GET");
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
                var parmetrs = new Array();
                var ar1 = new Array();
                ar1[0] = "ObjectId";
                ar1[1] = $("#ObjectId").val();
                parmetrs[0] =ar1;
                var ar2 = new Array();
                var ar3 = new Array();
                ar3[0] = "Data";
                ar3[1] = "";
                ar2[0] = "ObjectIdConnection";
                ar2[1] = ObjectIdConnection;
                
                 parmetrs[1] =ar2;
                 parmetrs[2] =ar3;
                 var ar4 = new Array();
                 ar4[0] = "Mode";
                 ar4[1] = "gallery";
                 parmetrs[3] =ar4;
                 CallPhpFunctionAjax("WebEdit","ConnectObject","POST",parmetrs);
                 GetGalleryItems();   
            
        
            });
        }
    }
    
    