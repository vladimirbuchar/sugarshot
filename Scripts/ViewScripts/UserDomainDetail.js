
$(document).ready(function(){
             $("#ConnectedWord").change(function(){
             var value = $(this).val();
             $("#ShowName").val(value);
          });
            DisabledItems("");
            $("#Type").change(function(){
                var value = $(this).val();
                DisabledItems(value);
            });
            $("#DomainSettings").change(function(){
                if ($("#DomainSettings").is(":visible"))
                {
                    var value = $(this).val();
                
                    if (value == "standard")
                    {
                           $(".Required").hide();
                    }
                    else 
                    {
                        $(".Required").show();
                        $(".FiltrSettings").show();
                    }
                }
                    
            });
        });
        function DisabledItems(value)
        {
            $(".ShowInAdmin").show();
            $(".ShowInAdminReadOnly").show();
            $(".ShowInWeb").show();
            $(".ShowInWebReadOnly").show();
            $(".CssClass").show();
            $(".MoreHtmlAtribut").show();
            $(".Domain").hide();
            $(".DomainSettings").hide();
            $(".NoUpdate").show();
            $(".XmlSettings").show();
            $(".FiltrSettings").hide();
            $(".Autocomplete").hide();
            $(".GenerateHiddenInput").hide();
            if (value == "")
                {
                    $(".UniqueValue").hide();
                    $(".DefaultValue").hide();
                    $(".MaxLength").hide();
                    $(".MinLength").hide();
                    $(".Required").hide();
                    $(".values").hide();
                    $(".Validate").hide();
                    $(".NoUpdate").hide();
                    $(".XmlSettings").hide();
                    $(".FiltrSettings").hide();
                }
            else if (value =="textbox")
            {
                $(".UniqueValue").show();
                $(".DefaultValue").show();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").show();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
                $(".Autocomplete").show();
            }
            else if (value =="color")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }
            else if (value =="email")
            {
                $(".UniqueValue").show();
                $(".DefaultValue").show();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
            }
            else if (value =="file")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").hide();
            }
            else if (value =="hidden")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
            }            
            else if (value =="number")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }                        
            else if (value =="password")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").hide();
            }          
            else if (value =="search")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").hide();
            }                        
            else if (value =="tel")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
            }                                  
            else if (value =="url")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
            }                       
            else if (value =="textarea")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").show();
                $(".MaxLength").show();
                $(".MinLength").show();
                $(".Required").show();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }                 
            else if (value =="html")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }   
            else if (value =="range")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").hide();
            }
            else if (value =="calendar")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").hide();
                $(".values").hide();
                $(".Validate").hide();
                $(".NoUpdate").hide();
                $(".XmlSettings").show();
                
            }
            else if (value =="checkbox" || value == "checkboxOneItem")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").show();
                $(".values").show();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }
            else if (value =="radio")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").show();
                $(".values").show();
                $(".Validate").hide();
                $(".NoUpdate").show();
                $(".XmlSettings").show();
                $(".FiltrSettings").show();
            }
            else if (value =="domainData")
            {
                $(".UniqueValue").hide();
                $(".DefaultValue").hide();
                $(".MaxLength").hide();
                $(".MinLength").hide();
                $(".Required").show();
                
                
                $(".values").hide();
                $(".Validate").hide();
                //$(".ShowInAdmin").hide();
                $(".ShowInAdminReadOnly").hide();
                //$(".ShowInWeb").hide();
                $(".ShowInWebReadOnly").hide();
                $(".CssClass").hide();
                $(".MoreHtmlAtribut").hide();
                $(".Domain").show();
                $(".XmlSettings").hide(); 
                $(".DomainSettings").show();
                $(".GenerateHiddenInput").show();
            }
        }
        
        function WriteRow(data)
        {
             var xml = $(data);
             var items = xml.find("item");
             for(var i = 0;i<items.length;i++)
             {
                 var item = $(items[i]);
                 var itemValue  = item.find("itemValue");
                 var itemText = item.find("itemText");
                 $("#itemText").val(itemText.html());
                 $("#itemValue").val(itemValue.html());
                 SaveRow();
             }
             
             
        }
        function CreateNewRow()
        {
            $(".newItemRow").removeClass("dn");
        }
        
        function CancelNewRow()
        {
            $("#itemText").val("");
            $("#itemValue").val("");
            $(".newItemRow").addClass("dn");
        }
        
        function SaveRow()
        {
            var html ="";
            
                html +="<td class=\"itemValue\" xmlitem=\"itemValue\"> " ;
                html +=$("#itemValue").val();
                html +="</td>";
                html +="<td class=\"itemTextShow \" >";
                html += GetWord($("#itemText").val());
                html +="</td>";                
                html +="<td class=\"itemText dn\" xmlitem=\"itemText\">";
                html +=$("#itemText").val();
                html +="</td>";                
                html +="<td class=\"noDatabase\">";
                //html +='<input type="button" value="'+GetWord('word298') +'" onclick="EditItemDomain(this);" />';
                html += '<button onclick="EditItemDomain(this);return false;" class="noDatabase btn btn-default"><i class="fa fa-edit" aria-hidden="true"></i></button>'
                    
                html +="</td>";
                html +="<td class=\"noDatabase\">";
                html += '<button onclick="DeleteItemDomain(this);return false;" class="noDatabase btn btn-default"><i class="fa fa-times" aria-hidden="true"></i></button>'
                    
                html +="</td>";
            
            var editRow = $("#ValueList").find(".editItem");
            if (IsUndefined(editRow.html()))
            {
                
                html = "<tr class=\"removeItem\"> "+html+"</tr>";
                $("#ValueList").append(html);
            }
            else 
            {
                
                editRow.addClass("removeItem");
                editRow.html(html);
                editRow.removeClass("editItem");
            }
            CancelNewRow();
        }
        function DeleteItemDomain(el)
        {
            
            if (confirm(GetWord('word304')))
                $(el).parent().parent().remove();   
        }
        function EditItemDomain(el)
        {
            CreateNewRow();
            var parent = $(el).parent().parent();
            parent.addClass("editItem");
            var itemValue = parent.find(".itemValue");
            $("#itemValue").val(itemValue.html());
            var itemText = parent.find(".itemText");
            $("#itemText").val(itemText.html());
        }