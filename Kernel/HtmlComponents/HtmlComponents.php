<?php
namespace HtmlComponents;
use Utils\StringUtils;
class HtmlComponents {
    /** třída pro generování html */
    /** id */
    public $Id;
    /** třída css*/
    public $CssClass;
    /** použitý html tag*/
    protected $HtmlTag;
    /** obsah html tagu*/
    public $Html;
    
    /** seznam párových tagů*/
    
    /** generovaný onclick*/
    public $OnClick = "";
    public $OnDoubleClick = "";
    public $OnChange;
    /** styl*/
    public  $Style="";
    /** typ inputu */
    public $Type;
    /** hodnota inputu*/
    public $Value;
    /**selected option */
    public $Selected;
    /** label for */
    public $For;
    /** ifrmame img */
    public $Src;
    public $Width = "100%";
    public $Height ="500px";
    public $Js  ="";
    public $Name ="";
    public $Checked;
    public $DataTarget ="";
    public $DataToggle= "";
    public $frameborder = 0;
    public $Disabled = false;
    public $TabIndex ="";
    public $Role="";
    public $Arialabelledby;
    public $Ariahidden;
    public $Datadismiss;
    public $Arialabel;
    public $AriaExpanded;
    public $List;
    public $AddScriptBefore = true;
    public $DisplayNone = false;
    public $PlaceHolder ="";
    public $Multiple = false;
    
    public $DataRole = "none";
    public $IsRequired = false;
    public $Title = "";
    public $Alt ="";
    
    private $_attributs = array();
    private $_pairTags = array("table","tr", "td", "th","a","div","ul","li","span","select","option","label","iframe","textarea","h1","h2","h3","h4","h5","h6","button","datalist","i","p");
    /** podřízené html tagy*/
    private $_childs = array();
    
    
    
    
    
    /** nastavení pořízených html*/
    public function SetChild($child) {
        $this->_childs[] = $child;
    }
    /** vrac podřízené html tagy*/
    public function GetChild() {
        return $this->_childs;
    }
    
    
    
    public function AddAtrribut($key,$value)
    {
        $this->_attributs[$key] = $value;
    }
    
    public function AddAtributes($ar)
    {
        $this->_attributs = array_merge($this->_attributs,$ar);
    }
    // rendrování html 
    public function RenderHtml($item = null) {
        if ($item == null)
            $item = $this;
        $outHtml = "";
        $outHtml .= $this->RenderItemHtml($item);
        return $outHtml;
    }

    public function RenderChild($object) {
        $outHtml = "";
        foreach ($object as $item) {
            $outHtml .= $this->RenderItemHtml($item);
        }
        return $outHtml;
    }
    /** metoda pro vyrendrování html html */
    private function RenderItemHtml($item) {
        if ($item== null)
            $item = $this;
        $outHtml = "";
        
        $id = empty($item->Id) ? "" : " id =\"" . $item->Id . "\"";
        $class = empty($item->CssClass) ? "" : " class =\"" . $item->CssClass . "\"";
        $onClick = empty($item->OnClick) ? "" : " onclick =\"" . $item->OnClick . "\"";
        $doubleClick = empty($item->OnDoubleClick) ? "" : " ondblclick =\"" . $item->OnDoubleClick . "\"";
        $onchange = empty($item->OnChange) ? "" : " onchange =\"" . $item->OnChange . "\"";
        if ($item->DisplayNone)
        {
            $item->Style .="display:none;";
        }
        $style = empty($item->Style) ? "" : " style =\"" . $item->Style . "\"";
        $dataTarget = empty($item->DataTarget) ? "" : " data-target =\"" . $item->DataTarget . "\"";
        $dataToogle =empty($item->DataToggle) ? "" : " data-toggle =\"" . $item->DataToggle . "\"";
        $tabindex = empty($item->TabIndex) ? "" : " tabindex =\"" . $item->TabIndex . "\"";
        $role = empty($item->Role) ? "" : " role =\"" . $item->Role . "\"";
        $Arialabelledby = empty($item->Arialabelledby) ? "" : " aria-labelledby =\"" . $item->Arialabelledby . "\"";
        $Ariahidden = empty($item->Ariahidden) ? "" : " aria-hidden =\"" . $item->Ariahidden . "\"";
        $Datadismiss =  empty($item->Datadismiss) ? "" : " data-dismiss =\"" . $item->Datadismiss . "\"";
        $Arialabel =  empty($item->Arialabel) ? "" : " aria-label =\"" . $item->Arialabel . "\"";
        $AriaExpended =  empty($item->AriaExpanded) ? "" : " aria-expanded =\"" . $item->AriaExpanded . "\"";
        $list =empty($item->List) ? "" : " list=\"" . $item->List . "\"";
        $placeHolder = empty($item->PlaceHolder) ? "" : " placeholder=\"" . $item->PlaceHolder . "\"";
        $attributs = "";
        $multiple = "";
        $datarole = empty($item->DataRole) ? "" : " data-role=\"" . $item->DataRole . "\"";
        $title  = empty($item->Title) ? "" : " title=\"" . $item->Title . "\"";
        
        $alt  = empty($item->Alt) ? "" : " alt=\"" . $item->Alt . "\"";
        if ($item->Multiple)
        {
            $multiple =   " multiple ";
        }
        $reqired = $item->IsRequired ? "required" :"";
        
        foreach ($item->_attributs as $key => $value)
        {
            $attributs .= $key."=\"".$value."\" ";
        }
        
        $disabled = $item->Disabled ? 'disabled="disabled"':"";
        $value = "";
        
        if (in_array($item->HtmlTag, $this->_pairTags)) {
            $href ="";
            $selected ="";
            
            $colSpan ="";
            if ($item->HtmlTag == "a")
            {
                $href = $item->Type."= \"$item->Href\"";
            }
            else if ($item->HtmlTag =="td")
            {
                $colSpan = empty($item->colSpan) ? "" : " colspan =\"" . $item->colSpan . "\"";
            }
            else if ($item->HtmlTag =="option")
            {
                $value = " value = \"$item->Value\"";
            }
            if ($item->Selected)
            {
                $selected = 'selected ="selected"';
            }
            $for ="";
            if (!empty($item->For))
            {
                $for = " for = '".$item->For."'";
            }
            $src = ""; 
            if (!empty($item->Src))
            {
                $src = ' src="'.$item->Src.'"';
            }
            $border ="";
            $width ="";
            $height ="";
            if ($item->HtmlTag =="iframe")
            {
                $border = 'frameborder ="'.$item->frameborder.'"';
                $width = 'width ="'.$item->Width.'"';
                $height = 'height ="'.$item->Height.'"';
            }
            
            $outHtml .="<" . trim($item->HtmlTag . " $id $for $class $onClick $doubleClick $href $src $colSpan $style $value $selected $border $width $height $dataTarget $dataToogle $disabled $attributs $tabindex $role $Arialabelledby $Ariahidden $Datadismiss $Arialabel $list $placeHolder $multiple $onchange $datarole $AriaExpended $reqired $title $alt").">\n";
            if (!empty($item->Html))
                $outHtml .= $item->Html."\n";
            $childs = $item->GetChild();
            if (!empty($childs))
                $outHtml = $outHtml . $this->RenderChild($childs);
            $outHtml .= "</" . $item->HtmlTag . ">"."\n";;
        }
        else 
        {
            
            $type = "";
            $name ="";
            $checked = "";
            $src = ""; 
            if (!empty($item->Src))
            {
                $src = ' src="'.$item->Src.'"';
            }
            
            if ($item->HtmlTag == "input" || $item->HtmlTag == "button")
            {
                $type = " type = \"$item->Type\"";
                $value = empty($item->Value) || $item->Value =="" ? "": " value = \"$item->Value\"";
                if (!empty($item->Name))
                $name = " name = \"$item->Name\"";
                $checked = $item->Checked ?  " checked =\"checked\"" :"";
            }
            $outHtml .="<" . trim($item->HtmlTag . " $id $class $src $onClick $doubleClick $style $value $type $name $checked $dataTarget $dataToogle $disabled $attributs $tabindex $role $Arialabelledby $Ariahidden $Datadismiss $Arialabel $list $placeHolder $multiple $onchange $datarole $AriaExpended $reqired $title $alt")." />\n";
        }
        if (!empty($item->Js))
        {
            if ($item->AddScriptBefore)
            {
                if (StringUtils::StartWidth("<script", $item->Js))
                    $outHtml = $item->Js." ".$outHtml;
                else 
                    $outHtml = "<script type='text/javascript'>". $item->Js."</script> ".$outHtml;
            }
            else 
            {
                if (StringUtils::StartWidth("<script", $item->Js))
                    $outHtml = $outHtml." ".$item->Js;
                else 
                    $outHtml = $outHtml." "."<script type='text/javascript'>". $item->Js."</script>";
            }
        }
        return $outHtml;
    }
    
    

}

