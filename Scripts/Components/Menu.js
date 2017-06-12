$(document).ready(function(){
    var li = $(".menuList li.active");
    li.find("ul:first").addClass("active");
    ActiveAllParents(li);
});
function ActiveAllParents(li)
{
    for (var i =1; i< 20;i++)
    {
        if (li.parent().hasClass("menuList"))
            break;
        li = li.parent();
        li.addClass("active");
        
    }
}
