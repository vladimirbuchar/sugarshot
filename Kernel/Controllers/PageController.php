<?php
namespace Controller;
use Utils\StringUtils;

use Model\Langs;

use Types\ContentTypes;
use Utils\Forms;
abstract class PageController extends ViewController {
    
    public function PageController() {
        parent::Controllers();
    }
    protected function LoadPageByIndentificator($identificator)
    {
        $content = new \Objects\Content();
        
        $template = $content->LoadTemplateByIdentificator($identificator,self::$UserGroupId,$this->LangId,$this->WebId);
        
        return $this->PreparePage($template);
    }
    
    private function LoadPageById($id,$html,$loadTemplate = true)
    {
        $preparedTemplate = "";
        if ($loadTemplate)
        {
            $content = new \Objects\Content();
            $template = $content->LoadTemplateById($id,self::$UserGroupId,$this->LangId,$this->WebId);
            $preparedTemplate = $this->PreparePage($template);
        }
        else 
        {
            $preparedTemplate = $html;
        }
        
        if (StringUtils::ContainsString($preparedTemplate, "{templateRender}"))
            $html = str_replace("{templateRender}", $html, $preparedTemplate);
        else 
            $html = str_replace("<templateRender />", $html, $preparedTemplate);
        return $html;        
    }
    
    private function PreparePage($template)
    {
        if (!empty($template))
        {
            $html = $template["data"];
            
            if (!empty($template["TemplateId"]) && $template["TemplateId"] > 0 )
            {
                $html = $this->LoadPageById($template["TemplateId"],$html);
            }
            else 
            {
                $this->WriteHeader($template["Header"]);
            }
            return $html;
        }
    }
    
    private function WriteHeader($header)
    {
        $header = $this->PrepareHeader($header);
        $this->SetTemplateData("pageHeader", $header);
    }
    
    private function PrepareHtml($data,$template = "")
    {
        
        if (empty($data))
            return "";
        if ($template == "")
            $template = $this->LoadTemplate($data[0]["TemplateId"]);
        $html = "";
        
        foreach ($data as $row)
        {   
            $tmp ="";
            $xmlData = empty($row["Data"]) ?"" : $row["Data"];
            
            $find = array();
            $replace = array();
            
            foreach ($row as $key => $value)
            {
                if (is_array($value))
                {
                    $html .= $this->PrepareHtml($value,$template);
                }
                else 
                {
                    $find[] = "/{".$key."}/";
                    if ($key== "SeoUrl")
                        $value = "/".$value."/";
                    $replace[] = $value;
                }
            } 
            $xml = simplexml_load_string($xmlData);
            if (!empty($xml))
            {
                foreach ($xml as $key => $value)
                {
                    
                    $find[] = "/{".$key."}/";
                    $replace[] = $value;
                    
                }
            }
            
            
            $tmp = preg_replace($find, $replace, $template);
            $xml = null;
            $html .= $tmp;
        }
        return $html;
    }
    
    public function LoadTemplate($id = 0)
    {
        $content = new \Objects\Content();
        $template = $content->LoadTemplateById($id, self::$UserGroupId, $this->LangId, $this->WebId);
        return $template["data"];
    }

    protected function LoadBySeoUrl($seoUrl,$where ="",$columns="",$sort ="",$limit = "start")
    { 
        try
        {
            $html ="";
            $content = new \Objects\Content();
            $preview = false;
            if (!empty($_GET["preview"]))
            {
                $preview = true;
            }
            $out= $content->GetArticleBySeoUrl($seoUrl,self::$UserGroupId,$this->LangId,$this->WebId,$preview,false,$where,$columns,$sort,$limit);
            
            
            if (empty($out))
            {
                $this->GoHome();
            }
            
            foreach ($out as $row)
            {
                $row["TemplateId"] = $out[0]["TemplateId"];
            }
            
            $template =  $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $out[0]["TemplateId"]);
            
            
            if ($out[0]["ContentType"] == ContentTypes::USERITEM)
            {
                $html = $this->PrepareHtml($out);
            }
            else if ($out[0]["ContentType"] == ContentTypes::FORM)
            {
                
                $form = new Forms();
                $editid = empty($_GET["param1"]) ? 0:$_GET["param1"];
                
                $html = $form->GenerateFrontEndForm($out[0]["Id"],$editid);
                $html = $this->PrepareHtml($out,$html);
                
                
            }
            else if ($out[0]["ContentType"] == ContentTypes::DISCUSION)
            {
                $template = $content->GetTempateDetailByIdentificator("Discusion", self::$UserGroupId, $this->LangId, $this->WebId);
                $discomponent = new \Components\Discusion();
                $html = $discomponent->LoadComponent();
                
            }
            
            if (self::$IsApi)
                return $html;
            if(empty($template))
            {
                $this->GoHome();
            }
            $html = $this->LoadPageById($template[0]["TemplateId"], $html);
            
            //$cache->SaveToCache($this->ArticleUrl,$this->UserGroupId,$html,  Utils::Now(),  CacheType::$UserItem);
            return $html;
        }
        catch (Exception $ex)
        {
            Files::WriteLogFile($ex);
            $this->GoHome();
        }
    }
    
    protected function RenderSendEmail($id)
    {
        $content = new \Objects\Content();
        $out = $content->RenderSendEmail ($id,$this->LangId,$this->WebId);
        $template = new Content();
        $template->GetObjectById($out[0]["TemplateId"],true,array("TemplateId"));
        return $this->LoadPageById($template->TemplateId, $this->PrepareHtml($out));
    }
    
    private function PrepareHeader($html)
    {
        $replacedata = array();
        $lang =  Langs::GetInstance();
        $lang->GetObjectById($this->LangId,true,array("Title","Keywords","Description","CategoryPage"));
        $replacedata["Name"] = $lang->Title;
        $replacedata["keywords"] = $lang->Keywords;
        $replacedata["description"] = $lang->Description;
        $replacedata["categorypage"] = $lang->CategoryPage;
        if (!empty($_GET["seourl"]))
        {
            $contemt =  new \Objects\Content();
            $data = $contemt->LoadFrontendFromSeoUrl($_GET["seourl"], self::$UserGroupId, $this->LangId, $this->WebId);
            if (!empty($data))
            {
                $xmlData = $data[0]["Data"];
                if (!empty($xmlData)) {
                $xml = simplexml_load_string($xmlData);
                if (!empty($xml)) {
                    foreach ($xml as $key => $value) {
                        
                        if (!empty($value))
                        {
                            $replacedata[$key] = $value;
                           
                        }
                    }
                }
            }
            
            $replacedata["Name"] = $contemt->GetNameObjectBySeoUrl($_GET["seourl"], $lgInfo->Id);
            
            }       
        }
        
                
                
            
            
            
            
        
        foreach ($replacedata as $key =>$value)
        {
            $html = str_replace("{".$key."}", $value, $html);
        }
        return $html;
    }
    protected function RenderCss()
    {
        $content = new \Objects\Content();
        $cssList = $content->GetCssList(self::$UserGroupId,$this->LangId,true);
        return $cssList;   
    }
    
    protected function RenderJs()
    {
        $content = new \Objects\Content();
        $cssList = $content->GetJsList(self::$UserGroupId,$this->LangId,true);
        return $cssList;   
    }
    
    
}