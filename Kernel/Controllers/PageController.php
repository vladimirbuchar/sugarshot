<?php
namespace Controller;
use Utils\StringUtils;
use Model\ContentVersion;
use Model\Langs;
use Model\Cache;
use Types\ContentTypes;
use Kernel\Forms;
abstract class PageController extends Controllers {
    
    public function PageController() {
        parent::Controllers();
    }
    protected function LoadPageByIndentificator($identificator)
    {
        $content = ContentVersion::GetInstance();
        
        $template = $content->LoadTemplateByIdentificator($identificator,self::$UserGroupId,$this->LangId,$this->WebId);
        
        return $this->PreparePage($template);
    }
    
    private function LoadPageById($id,$html,$loadTemplate = true)
    {
        $preparedTemplate = "";
        if ($loadTemplate)
        {
            $content = ContentVersion::GetInstance();
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
        $content = ContentVersion::GetInstance();
        $template = $content->LoadTemplateById($id, self::$UserGroupId, $this->LangId, $this->WebId);
        return $template["data"];
    }

    protected function LoadBySeoUrl($seoUrl,$where ="",$columns="",$sort ="",$limit = "start")
    { 
        try
        {
            $html ="";
            $content = ContentVersion::GetInstance();
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
            
            
            if ($out[0]["ContentType"] == ContentTypes::$UserItem)
            {
                $html = $this->PrepareHtml($out);
            }
            else if ($out[0]["ContentType"] == ContentTypes::$Form)
            {
                
                $form = new Forms();
                $editid = empty($_GET["param1"]) ? 0:$_GET["param1"];
                
                $html = $form->GenerateFrontEndForm($out[0]["Id"],$editid);
                $html = $this->PrepareHtml($out,$html);
                
                
            }
            else if ($out[0]["ContentType"] == ContentTypes::$Discusion)
            {
                $template = $content->GetTempateDetailByIdentificator("Discusion", self::$UserGroupId, $this->LangId, $this->WebId);
                $discomponent = new \Components\Discusion();
                $html = $discomponent->LoadComponent();
                
            }
            
            if (self::$IsAjax)
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
        $content = ContentVersion::GetInstance();
        $out = $content->RenderSendEmail ($id,$this->LangId,$this->WebId);
        $template = new Content();
        $template->GetObjectById($out[0]["TemplateId"],true);
        return $this->LoadPageById($template->TemplateId, $this->PrepareHtml($out));
    }
    
    private function PrepareHeader($html)
    {
        
        $replacedata = array();
        $lang =  Langs::GetInstance();
        $lgInfo = $lang->GetObjectById($this->LangId);
        $replacedata["Name"] = $lgInfo->Title;
        $replacedata["keywords"] = $lgInfo->Keywords;
        $replacedata["description"] = $lgInfo->Description;
        $replacedata["categorypage"] = $lgInfo->CategoryPage;
        if (!empty($_GET["seourl"]))
        {
            $contemt =  new \Model\ContentVersion();
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
            $replacedata["Name"] = $data[0]["Name"];
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
        $content = ContentVersion::GetInstance();
        $cssList = $content->GetCssList(self::$UserGroupId,$this->LangId,true);
        return $cssList;   
    }
    
    protected function RenderJs()
    {
        $content = ContentVersion::GetInstance();
        $cssList = $content->GetJsList(self::$UserGroupId,$this->LangId,true);
        return $cssList;   
    }
    
    
}