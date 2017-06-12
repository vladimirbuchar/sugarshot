<?php
namespace Controller;
class PageByAjax extends PageController {
    public function LoadPageByAjax($url,$where="",$columns="",$sort="",$limit="")
    {
        $_GET["seourl"] = $url;
        $page = $this->LoadBySeoUrl($url,$where,$columns,$sort,$limit);
        return $page;
    }
}
