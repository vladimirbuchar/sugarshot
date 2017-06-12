$(document).ready(function(){
    $(".xwebformitem").change(function(){
        var showPage = parseInt($("#ActiveStep").val());
        showPage = showPage+1;
        BlockNextPages(showPage);
    });
});
async function SendUserForm(formId)
{
    $.ajaxSetup({async: false});
    var params = new Array();
    var ar = new Array();
    ar[0] = "FormId";
    ar[1] = formId;
    params[0] =ar;
    var ar2 = new Array();
    ar2[0]="parametrs";
    ar2[1] = PrepareParametrs("form-"+formId);
    params[1] =ar2;
    var out = await CallPhpFunctionAjax("Forms","SendFormWeb","JSON",params);
    if (out["Errors"].length > 0)
    {
        var captcha = out["captchaImage"];
	if (captcha !="")
        {
             $("#captchaform"+formId).attr("src",captcha);
            $("#form"+formId+"_captcha").val("");
   	}
	return;
    }
    if (out["AfterSendFormAction"] == "ShowText")
    {
        $("#form-"+formId).html(out["EndText"]);
    }
    if (out["AfterSendFormAction"] == "Refresh")
    {
	window.location.href="";
    }
    if (out["AfterSendFormAction"] == "GoToPage")
    {
	window.location.href = out["RedirectUrl"];
    }   

    $(".formErrors").html("");
    RegenerateCaptcha(formId);
    Clear("form-"+formId);
}

function BlockNextPages(startBlock)
{
    
    var li =  $(".nav-tabs li");
    for (var i = startBlock; i<=li.length; i++)
    {
        $("#step-"+i).addClass("disabled");
        $("#step-"+i+" a").addClass("disabled");
    }
    
}

function HideTab(tabid)
{
    $("#step-"+tabid).removeClass("active");
    $("#step"+tabid).removeClass("in");
    $("#step"+tabid).removeClass("active");
}

function GoToTab(tabid)
{
    $("#step-"+tabid).removeClass("disabled");
    $("#step-"+tabid+" a").removeClass("disabled");
    $("#step-"+tabid).addClass("active");
    $("#step"+tabid).addClass("active");
    $("#step"+tabid).addClass("in");
    
}

function NextButtonClick(actualPage,showPage)
{
    HideTab(actualPage);
    GoToTab(showPage);
    $("#ActiveStep").val(showPage);
    $("body").scrollTop(5);
    
}

function PrevButtonClick(actualPage,showPage)
{
    HideTab(actualPage);
    GoToTab(showPage);
    $("#ActiveStep").val(showPage);
    $("body").scrollTop(5);
}


function SetActiveStep(stepId)
{
    $("#ActiveStep").val(stepId);
}


/// todo test
function Search()
{

}



 

function RegenerateCaptcha(formId)
{
    var params = new Array();
    var ar1 = new Array();                                     
    ar1[0] ="Id";
    ar1[1] =formId;
    params[0]=ar1;                            
    var captcha = CallPhpFunctionAjax("Forms","RegenerateCaptcha","POST",params);
    if (captcha !="")
    	$("#captchaform"+formId).attr("src",captcha);
}



function Logout()
{
    CallPhpFunctionAjax("Ajax","UserLogout","GET");
    Refresh();
}
function SaveSurveyAnswer(id)
{
    var data = PrepareParametrs(id);
    CallPhpFunctionAjax("Ajax","SaveSurveyAnswer","POST",data);
    $("#"+id+" input").attr("disabled","disabled");
}




