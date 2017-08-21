<?php
namespace Utils;
use Kernel\GlobalClass;
use Components\BootstrapDialog;
use HtmlComponents\HtmlTable;
use HtmlComponents\HtmlTableTd;
use HtmlComponents\HtmlTableTr;
use HtmlComponents\HtmlTableTh;
use HtmlComponents\Link;
use HtmlComponents\Div;
use HtmlComponents\Checkbox;
class ArrayUtils  {

    /** metoda pro převod pole do xml
     * @param type $data - array
     * @return string
     */
    public static function ArrayToXml($data, $addCdata = false,$rootName ="root",$subXml = "") {
        if (empty($data))
            return"";
        $outString = "<$rootName>\n";
        foreach ($data as $row) {
            $outString.= "\t<item>\n";
            foreach ($row as $key => $value) {
                if ($addCdata && $key != "DataItems") {
                    $outString.= "\t\t<$key><![CDATA[$value]]></$key>\n";
                } else {
                    $outString.= "\t\t<$key>$value</$key>\n";
                }
            }
            if (!empty($subXml))
                $outString.= $subXml;
            $outString.= "\t</item>\n";
        }
        $outString .= "</$rootName>\n";
        return $outString;
    }

    public static function DibiRowToXml($data,$rootName ="root",$subXml = "") {
        if (empty($data))
            return"";
        $outString = "<$rootName>\n";
        $outString.= "\t<item>\n";
        foreach ($data as $key => $value) {
            $outString.= "\t\t<$key>$value</$key>\n";
        }
        $outString .= $subXml;
        $outString.= "\t</item>\n";
        $outString .= "</$rootName>\n";
        return $outString;
    }

    public static function XmlToArray($xmlstring,$class_name="SimpleXMLElement",$options=0) {
        $xml = simplexml_load_string($xmlstring,$class_name, $options);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }
    
   

    public static function ColummToArray($data, $columnName, $columnName1 = "") {
        if (empty($data)) return array();
        $outArray = $data[0];
        $colArray = array();
        $colArray1 = array();
        foreach ($data as $row) {
            $colArray[] = $row[$columnName];
            if (!empty($columnName1)) {
                $colArray1[] = $row[$columnName1];
            }
        }
        $outArray[$columnName] = $colArray;
        if (!empty($columnName1)) {
            $outArray[$columnName1] = $colArray1;
        }

        return $outArray;
    }

    public static function ValueAsKey($array, $columnName,$moreValueOneKey = false) {
        $out = array();
        foreach ($array as $row) {
            $key = $row[$columnName];
            if ($moreValueOneKey)
            {
                $out[$key][] = $row;
            }
            else 
            {
                $out[$key] = $row;
            }
        }
        return $out;
    }

    public static function XmlToHtmlTable($data, $xmlColumn = "Data", $ignore = array(), $header = array(),$addCheckBox = false, $errorColumn ="",$editColumn = false,$idColumn="Id",$autoScroolClass ="") {
        $dialogHtml = "";
        $global = new GlobalClass();
        if ($editColumn)
        {
            $dialog = new BootstrapDialog();
            $dialog->CancelButtonText = $global->GetWord("word702");
            $dialog->DialogId = "editDialog";
            $dialog->DialogContent ="<div id='showPanel'></div>";
            $dialog->DialogTitle=$global->GetWord("word703");
            
            $dialogHtml = $dialog->GetComponentHtml();
        }
        $table = new HtmlTable();
        $table->CssClass = "table table-striped table-bordered table-hover";
        if (!empty($header)) {
            $tr = new HtmlTableTr();
            $tr->CssClass = "header";
            if ($addCheckBox)
            {
                $td = new HtmlTableTh();
                $checkboxAll = new Checkbox();
                $checkboxAll->OnClick="TableSelectAll(this,'selectbox')";
                $td->SetChild($checkboxAll);
                $tr->SetChild($td);
            }
            foreach ($header as $key => $value) {
                $td = new HtmlTableTh();
                if (in_array($key, $ignore))
                    continue;
                $td->Html = $value;
                $tr->SetChild($td);
            }
            $table->SetChild($tr);
        }
        
        foreach ($data as $row) {
            $xml = $row[$xmlColumn];
            $xmlLoad = simplexml_load_string($xml);
            $tr = new HtmlTableTr();
            
            if (!empty($errorColumn))
            {
                $error = $row[$errorColumn] == "0" ||$row[$errorColumn] == 0 ? true : false;
                if ($error)
                    $tr->CssClass = "tableError";
            }        
            if ($addCheckBox)
            {
                $td = new HtmlTableTd();
                $checkboxAll = new Checkbox();
                $checkboxAll->CssClass = "selectbox";
                $checkboxAll->Id =  !empty($row["Id"]) ? $row["Id"]:"";
                $td->SetChild($checkboxAll);
                $tr->SetChild($td);
            }
            if (empty($header))
            {
                foreach ($xmlLoad as $key => $value) {
                    if (in_array($key, $ignore))
                        continue;
                $td = new HtmlTableTd();
                $td->Html = trim($value);
                $tr->SetChild($td);
                }
            }
            else {
                
                foreach ($header as $item =>$value)
                {
                    if (in_array($item, $ignore))
                        continue;
                        $value ="";
                        if (!empty($xmlLoad->$item))
                            $value = trim($xmlLoad->$item);
                        else 
                        {
                            $value = $row[$item];
                        }
                        if (empty($value)) $value = "&nbsp;";
                        $td = new HtmlTableTd();
                        $td->Html = trim($value);
                        $tr->SetChild($td);
                   

                }
            }
            if ($editColumn)
            {
                $td = new HtmlTableTd();
                
                $editLink = new Link();
                $editLink->Html = $global->GetWord("word701");
                $editLink->DataTarget="#editDialog";
                $editLink->DataToggle="modal";
                $editLink->OnClick="FormItemDetail('".$row[$idColumn]."');";
                $td->SetChild($editLink);
                $tr->SetChild($td);
            }
            $table->SetChild($tr);
        }
        $html ="";
        if (!empty($autoScroolClass))
        {
            $div = new Div();
            $div->CssClass = $autoScroolClass;
            $div->SetChild($table);
            $html = $div->RenderHtml();
        }
        else 
        {
            $html = $table->RenderHtml();
        }
        return $dialogHtml. $html;
    }
    
    public static function GetItemDetail($data, $xmlColumn = "Data", $ignore = array(), $header = array()) 
    {
        $table = new HtmlTable();
        $table->CssClass = "table table-striped table-bordered table-hover";
        $nextData = array();
        foreach ($data as $row)
        {
            
            $xml = $row[$xmlColumn];
            $xmlLoad = simplexml_load_string($xml);
            foreach ($xmlLoad as $key => $value) {
                if (StringUtils::StartWidth($key, "DataItems"))
                {
                    $nextData[] = $value;
                    continue;
                }
                if (in_array($key, $ignore))
                    continue;
                $tr = new HtmlTableTr();
                $tdName = new HtmlTableTd();
                if (!empty($header[$key]))
                {
                    $tdName->Html = trim($header[$key]);
                }
                $td = new HtmlTableTd();
                $td->Html = trim($value);
                $tr->SetChild($tdName);
                $tr->SetChild($td);
                $table->SetChild($tr);
            }
        }
        $ignore[] = "NoLoadSubItems";
        $ignore[] = "Inquery";
        $ignore[] = "ActivatePager";
        $ignore[] = "FirstItemLoadPager";
        $ignore[] = "NextItemLoadPager";
        $ignore[] = "CopyDataToChild";
        $ignore[] = "ChildTemplateId";
        $ignore[] = "UseTemplateInChild";
        $ignore[] = "NoChild";
        $ignore[] = "ParentId";
        $ignore[] = "FormId";
        $ignore[] = "DiscusionSettings";
        $ignore[] = "DiscusionId";
        $ignore[] = "TemplateId";
        $ignore[] = "Id";
        $ignore[] = "NoIncludeSearch";
        $ignore[] = "ActiveTo";
        $ignore[] = "ActiveTo";
        $ignore[] = "SeoUrl";
        $ignore[] = "AvailableOverSeoUrl";
        $ignore[] = "ActiveFrom";
        $ignore[] = "Identificator";
        $ignore[] = "WebId";
        $ignore[] = "LangId";
        $ignore[] = "IsLast";
        $ignore[] = "GalleryId";
        $ignore[] = "GallerySettings";
        $ignore[] = "versionId";
        $ignore[] = "GroupId";
        $ignore[] = "SecurityType";
        $ignore[] = "SecurityValue";        
        $ignore[] = "SSGroupId";
        $ignore[] = "SSSecurityType";
        $ignore[] = "SSValue";
        $ignore[] = "data";
         
 
        
        
        foreach ($nextData as $row )
        {
            foreach ($row as $rowX) {
                foreach ($rowX as $item) {
                    foreach ($item as $key =>$value) {
                    
                    if (in_array($key, $ignore))
                        continue;
                    $tr = new HtmlTableTr();
                    $tdName = new HtmlTableTd();
                    if (!empty($header[$key]))
                    {
                        $tdName->Html = trim($header[$key]);
                    }
                    $td = new HtmlTableTd();
                    $td->Html = trim($value);
                    $tr->SetChild($tdName);
                    $tr->SetChild($td);
                    $table->SetChild($tr);
                    }
                
                }
            }
        }
        
        
       return $table->RenderHtml($table);
    }
    
    
    public static function SortArray($data, $column, $mode)
    {
        $sort = array();
        foreach ($data as $row)
        {
            $sort[] = $row[$column];
        }
        array_multisort($sort, $mode, $data);        
        return $data;
        
    }
    
    public static  function Distinct($data)
    {
        return array_map("unserialize", array_unique(array_map("serialize", $data)));
    }
    
    public static function CreateCheckBoxList($data,$colName,$colId = "Id")
    {
        $html = "";
        foreach ($data as $row)
        {
            $name = $row[$colName];
            $id = "checkbox_".$row[$colId];
            $div = new Div();
            $div->CssClass="form-group";
            
            $label = new \HtmlComponents\Label();
            $label->Html = $name;
            $label->For = $id;
            $label->CssClass ="control-label col-sm-2";
            $div->SetChild($label);
            
            $div2 = new Div();
            $div2->CssClass = "col-sm-1";
            
            $input = new Checkbox();
            $input->Id = $id;
            $input->CssClass="form-control";
            $div2->SetChild($input);
            $div->SetChild($div2);
            $html .= $div->RenderHtml();
        }
        return $html;
    }
    
    public static function InsertAt(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        }
        else {
                $pos   = array_search($position, array_keys($array));
                $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
    
    public static function  SortColumns($data,$colums)
    {
        $out = array();
        foreach ($data as $row)
        {
            $add = array();
            foreach ($colums as $col)
            {
                if (!empty($row[$col]))
                    $add[$col] = $row[$col];
                else 
                    $add[$col] ="";
            }
            $out[] = $add;
        }
        return $out;
    }
    
    public static function GetColumnsvalue($data,$columnName)
    {
        $out = array();
        foreach ($data as $row)
        {
            $out[] = $row[$columnName];
            
        }
        return $out;
    }
    
    public static function RemoveCData($string)
    {
        $string = StringUtils::RemoveString($string, "<![CDATA[");
        $string = StringUtils::RemoveString($string, "]]>");
        return $string;
    }
    
    public static function ObjectToArray($object)
    {
        $out = array();
       
         foreach ( (array) $object as $index => $node )
         {
           $out[$index] = ( is_object ( $node ) ) ? self::ObjectToArray( $node ) : $node;
         }
         return $out; 
        
         
    }
    
    public static function RenameColumn($data,$oldName, $newName)
    {
        
        foreach ($data as $row)
        {
            $row[$newName] = $row[$oldName];
            unset($row[$oldName]);
        }
        return $data;
    }
    
    public static function ToArray($object)
    {
        return (array)$object;
    }
    
    public  static function AddReplaceCharsToKey($array)
    {
        $result = array();
        array_walk($array, function(&$item,$key) use(&$result) { 
            if (!StringUtils::StartWidth($key, "/{"))
                $key = "/{".$key;
            if (!StringUtils::EndWith($key, "}/"))
                $key = $key."}/";
            
            $result[$key] = $item;
        },"");
        return $result;
    }
    
    public static function GetNormalArray($array,$finditemm ="")
    {
        $out = array();
        foreach ($array as $row)
        {
            $key = $row[0];
            $value = $row[1];
            if (!empty($finditemm) && $finditemm != $key)
            {
                continue;
            }
            $out[$key] = $value;
        }
        return $out;
    }
    public static function AddColumn($data,$columnName,$defaultvalue ="")
    {
        foreach ($data as $row)
        {
            $row[$columnName] = $defaultvalue;
        }
        return $data;
    }
    
    public static function GetChildToRoot($array, $childColumn)
    {
        $outArray = array();
        $isInt = true;
        foreach ($array as $key => $value)
        {
            $isInt = is_int($key);
            break;
        }
        if (!$isInt)
        {
            if (!empty($array[$childColumn]))
            {
                $child = $array[$childColumn];
                unset($array[$childColumn]);
                $array = self::GetChildToRoot(array_merge($outArray,$array[$childColumn]),$childColumn);
            }
                
            return array($array);
        }
        
        
        foreach ($array as $row)
        {
            if (!empty($row[$childColumn]))
            {
                $child = $row[$childColumn];
                unset($row[$childColumn]);
                $outArray[] = $row;
                $outArray = array_merge($outArray,self::GetChildToRoot($child,$childColumn));
            }
            else{
                
                $outArray [] = $row;
            }
        }
        
        return $outArray;
    }
    
    public static function GetDataXmlValueToRow($array)
    {
        return array_map(function($row){
            $row = ArrayUtils::ObjectToArray($row);
            if (!empty($row["Data"]))
            {
                
                $data = ArrayUtils::XmlToArray($row["Data"],"SimpleXMLElement",LIBXML_NOCDATA);
                $row = array_merge($row,$data);
                
            }
            return $row;
        }, $array); 
    }
    public static function GetAllChildToRoot($array)
    {
        $outArray = array();
        foreach ($array as $row){
            foreach ($row as $key => $value)
            {
                if (is_array($value))
                {
                    $outArray = array_merge($outArray,self::GetAllChildToRoot($value));
                }
                else
                {
                    $outArray[$key][] = $value;
                }
            }
        }
        return $outArray;
    }
    
     
    
    
    
            
            
}


