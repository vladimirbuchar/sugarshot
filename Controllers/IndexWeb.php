<?php

namespace Controller;

class IndexWeb extends PageController {

    private $_isHomePage = false;
    private $_seoUrl = "";
    private $_renderMailHtml = false;
    private $_id = 0;

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetViewPermition("IndexPage", array("*"));
    }

    public function IndexPage() {

        $this->SetTemplateData("langId", $this->LangId);
        $this->SetTemplateData("webId", $this->WebId);

        $normalPage = empty($_POST["pageAjax"]) ? true : $_POST["pageAjax"] == 1 ? true : false;
        $this->SetTemplateData("NormalPage", $normalPage);

        $this->_seoUrl = empty($_GET["seourl"]) ? "" : $_GET["seourl"];

        $this->_renderMailHtml = empty($_GET["renderHtml"]) ? false : ($_GET["renderHtml"] == "TRUE" ? true : false);
        $this->_isHomePage = empty($_GET["seourl"]) && !$this->_renderMailHtml ? true : false;
        $this->_id = empty($_GET["id"]) ? 0 : $_GET["id"];
        $this->SetTemplateData("pageHtml", "");
        $this->SetTemplateData("pageHeader", "");

        $html = "";

        if ($this->_renderMailHtml) {
            $html = $this->RenderSendEmail($this->_id);
        } else if ($this->_isHomePage) {
            $html = $this->LoadPageByIndentificator("Homepage");
        } else {

            $html = $this->LoadBySeoUrl($this->_seoUrl);
        }
        $this->SetTemplateData("pageHtml", $html);
        $css = $this->RenderCss();
        $js = $this->RenderJs();

        $this->SetTemplateData("CssStyles", $css);
        $this->SetTemplateData("JsScript", $js);
    }

}
