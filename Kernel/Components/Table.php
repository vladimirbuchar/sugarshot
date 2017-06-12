<?php
namespace Components;
use HtmlComponents\Div;
use HtmlComponents\HiddenInput;
use HtmlComponents\Button;
use HtmlComponents\Ul;
use HtmlComponents\Li;
use HtmlComponents\Link;
use HtmlComponents\HtmlTable;
use HtmlComponents\HtmlTableTd;
use HtmlComponents\HtmlTableTr;
use HtmlComponents\HtmlTableTh;
use HtmlComponents\Checkbox;
class Table extends UserComponents {
    // prověřit použití
    //$aAdd->OnClick = "ShowDialog('$this->JoinAddDialogId','$this->ControllerName','$this->AddAction','$this->TableId','$this->PrefixIdRow','$this->DataSource');return false;";
    //$this->AddItemMode == "dialog"
    //$aAdd->OnClick = "ShowEditDialog('$this->JoinAddDialogId','$this->ControllerName','$this->AddAction','$this->TableId','$this->DetailFunction','$id','$this->PrefixIdRow','$this->DataSource');return false;";                
    
    public $DeleteButtonText = "word49";
    public $DeleteMessage = "word50";
    public $NoDataMessage = "word51";
    public $AddButtonText = "word52";
    public $EditLinkText="word48"; 
    public $MultiDeleteText="word53";
    public $MultiDeleteQuestion= "word54";
    public $CopyItemText="word55";
    public $ShowCopySelectedText ="word56";
    public $ShowCopySelectedQuestion="word57";
    public $ExportAllButtonText="word58";
    public $ImportText = "word59";
    public $SearchButtonText = "word60";
    public $SearchCancelButtonText = "word61";
    public $DeletedItemText="word62";
    public $NoDeleteButtonItemText = "word63";
    public $RecoveryButtonText="word64";
    public $RecoveryQuestion="word65";
    public $RecoveryMultiItemText = "word66";
    public $RecoveryMultiItemQuestion="word67";
    public $HistoryButtonText = "word68";
    public $NoObjectSelect  = "word120";
    public $AddScriptAction ="";
    public $TableId ="WebList";
    public $CssClass = "tableCss";
    public $Header =array();
    public $Data =array();
    public $ColName ="";
    public $IdColumn="Id";
    public $HideColumns = array();
    public $ShowEdit = TRUE;
    public $ShowDelete =TRUE; 
    public $DeleteAction="DeleteItem";
    public $ShowDeleteQuestion =true;
    public $ControllerName = "Settings";
    public $ShowAddButton =TRUE;
    public $JoinAddDialogId = "AddItemDialog";
    public $AddAction="AddItem";
    public $TemplateRow="";
    public $DetailFunction = "GetDetailItem";
    public $MultiSelect = true;
    public $MultiDelete = true;
    public $MultiDeleteAction="DeleteSelectItem";
    public $MultiselectIdentificator="selectItem";
    public $PrefixIdRow ="row-";
    public $ShowCopyItem=TRUE;
    public $CopyItemAciton="CopyItem";
    public $CopyServerAction = "CopyItem";
    public $ShowCopySelected = true;
    public $ShowCopySelectedAction="MultiCopy";
    public $ModelName="";
    public $RefreschTable = "LoadTable";
    public $ShowExportAllButton =TRUE;
    public $ExportAllClientAction = "ExportData";
    public $ExportAllServerAction ="ExportData";
    public $ExportDialog = "ExportDialog";
    public $ShowImport = TRUE;
    public $ImportDialogId = "ImportDialog";
    public $ImportClientFunction = "Import";
    public $ImportServerFunction = "Import";
    public $ShowSort = false;
    public $SortAscText ="ASC";
    public $SortClientFunction ="Sort";
    public $SortDescText ="DESC";
    public $ShowFiltr = FALSE;
    public $SearchClientAction = "Search";
    public $SearchClientActionClear = "ClearSearch";
    public $ShowDeletedItem = TRUE;
    public $ShowDeletedItemClientAction="ShowDeletedItem";
    public $ShowNoDeleteButtonItem = TRUE;
    public $NoDeleteButtonItemClientAction ="ShowNormalItem";
    public $ShowRecoveryButton = TRUE;
    public $RecoveryButtonClientAction = "RecoveryItem";
    public $RecoveryServerAction="RecoveryItem";
    public $ShowRecoveryQuestionDialog = TRUE;
    public $ShowRecoveryMultiSelect =TRUE;
    public $RecoveryMultiItemClientAction="RecoveryMultiSelect";
    public $ShowHistoryButton = TRUE;
    public $HistoryButtonClientAction = "ShowHistory";
    public $HistoryButtonServerAction = "ShowHistory";
    public $ReloadClientFunction="GridReload";
    public $SpecialLinks= array();
    public $WebIdColumn = "";
    public $ViewName = "";
    public $Mode = "table"; // div or table 
    public $AceptEmptyData = false;
    public $ScrollClass ="";
    private $_width = 0;
    public function __construct() {
        parent::__construct();
    }

    public function GetComponentHtml() {
        
    $this->DeleteButtonText = $this->GetWord($this->DeleteButtonText);
    $this->DeleteMessage = $this->GetWord($this->DeleteMessage);
    $this->NoDataMessage = $this->GetWord($this->NoDataMessage);
    $this->AddButtonText = $this->GetWord($this->AddButtonText);
    $this->EditLinkText=$this->GetWord($this->EditLinkText); 
    $this->MultiDeleteText= $this->GetWord($this->MultiDeleteText);
    $this->MultiDeleteQuestion= $this->GetWord($this->MultiDeleteQuestion);
    $this->CopyItemText= $this->GetWord($this->CopyItemText);
    $this->ShowCopySelectedText = $this->GetWord($this->ShowCopySelectedText);
    $this->ShowCopySelectedQuestion=$this->GetWord($this->ShowCopySelectedQuestion);
    $this->ExportAllButtonText= $this->GetWord($this->ExportAllButtonText);
    $this->ImportText = $this->GetWord($this->ImportText);
    $this->SearchButtonText = $this->GetWord($this->SearchButtonText);
    $this->SearchCancelButtonText = $this->GetWord($this->SearchCancelButtonText);
    $this->DeletedItemText= $this->GetWord($this->DeletedItemText);
    $this->NoDeleteButtonItemText = $this->GetWord($this->NoDeleteButtonItemText);
    $this->RecoveryButtonText= $this->GetWord($this->RecoveryButtonText);
    $this->RecoveryQuestion= $this->GetWord($this->RecoveryQuestion);
    $this->RecoveryMultiItemText = $this->GetWord($this->RecoveryMultiItemText);
    $this->RecoveryMultiItemQuestion= $this->GetWord($this->RecoveryMultiItemQuestion);
    $this->HistoryButtonText = $this->GetWord($this->HistoryButtonText);
    $this->NoObjectSelect  = $this->GetWord($this->NoObjectSelect);
        
        
        $this->DataSource = empty($this->ViewName)?$this->ModelName:$this->ViewName;
        $mainDiv = new Div();
        $mainDiv->CssClass = $this->CssClass;
        $mainDiv->Id = $this->TableId;
        $leftDiv = new Div();
        $showMode = new HiddenInput();
        $showMode->CssClass = "showItem";
        $showMode->Value = "NoDeleteItem";
        $leftDiv->SetChild($showMode);
        $leftDiv->CssClass = "leftColumn";
        $refresshButton = new Button();
        $refresshButton->Id = "RefreshButton";
        $refresshButton->CssClass = "dn";
        $refresshButton->OnClick = $this->ReloadClientFunction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow');return false;";
        $leftDiv->SetChild($refresshButton);
        $menuUl = new Ul();
        $menuUl->Id = "TableMenu";
        if ($this->ShowAddButton) {
            $li = new Li();
            $li->CssClass = "showInNormal btn btn-default";
            $aAdd = new Link();
            $aAdd->Html = $this->AddButtonText;
            $aAdd->OnClick = "Clear('$this->JoinAddDialogId');ShowFirstTab();CallUserLoadDetail();";
            $aAdd->Href ="#";
            $aAdd->DataTarget = "#$this->JoinAddDialogId";
            $aAdd->DataToggle = "modal";
            $li->SetChild($aAdd);
            $menuUl->SetChild($li);
               
        }


        if ($this->MultiDelete) {
            $li = new Li();
            $dAdd = new Link();
            $dAdd->Html = $this->MultiDeleteText;
            $dAdd->Href ="#";
            $dAdd->DataTarget = "#MultiDelete";
            $dAdd->DataToggle = "modal";
            $dAdd->OnClick = "ShowMultiDeleteDialog()";
            //$dAdd->OnClick = "$this->MultiDeleteAction('$this->MultiselectIdentificator','$this->MultiDeleteQuestion','$this->ControllerName','$this->DeleteAction','$this->TableId','$this->PrefixIdRow','$this->DataSource','$this->NoObjectSelect'); return false;";
            $li->SetChild($dAdd);
            $li->CssClass="btn btn-default";
            $menuUl->SetChild($li);
        }

        if ($this->ShowRecoveryMultiSelect) {
            $li = new Li();
            $dAdd = new Link();
            $dAdd->Html = $this->RecoveryMultiItemText;
            $dAdd->DataTarget = "#MultiRecovery";
            $dAdd->DataToggle = "modal";
            $dAdd->OnClick = "ShowMultiRecoveryDialog()";
            $li->CssClass = "showInDeleted dn btn btn-default";
            
            $li->SetChild($dAdd);
            $menuUl->SetChild($li);
        }


        if ($this->ShowCopySelected) {
            $li = new Li();
            $li->CssClass = "showInNormal  btn btn-default" ;
            $cAdd = new Link();
            $cAdd->Html = $this->ShowCopySelectedText;
            $cAdd->DataTarget = "#MultiCopyDialog";
            $cAdd->DataToggle = "modal";
            $cAdd->OnClick = "ShowMultiCopy()";
            $li->SetChild($cAdd);
            $menuUl->SetChild($li);
        }
        if ($this->ShowExportAllButton) {
            $li = new Li();
            $li->CssClass = "showInNormal  btn btn-default";
            $eAllLink = new Link();
            $eAllLink->Html = $this->ExportAllButtonText;
            $eAllLink->DataTarget = "#ExportDialog";
            $eAllLink->DataToggle = "modal";
            $li->SetChild($eAllLink);
            
            $menuUl->SetChild($li);
        }
        if ($this->ShowImport) {
            $li = new Li();
            $li->CssClass = "showInNormal  btn btn-default";
            $iLink = new Link();
            $iLink->Html = $this->ImportText;
            $iLink->DataTarget = "#ImportDialog";
            $iLink->DataToggle = "modal";
            
            $li->SetChild($iLink);
            
            $menuUl->SetChild($li);
        }
        if ($this->ShowDeletedItem) {
            $li = new Li();
            $li->CssClass = "showInNormal  btn btn-default";
            $sdLink = new Link();
            $sdLink->Html = $this->DeletedItemText;
            $sdLink->OnClick = $this->ShowDeletedItemClientAction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow'); return false;";
            $li->SetChild($sdLink);
            
            $menuUl->SetChild($li);
        }

        if ($this->ShowNoDeleteButtonItem) {
            $li = new Li();
            $sdLink1 = new Link();
            $li->CssClass = "showInDeleted dn btn btn-default";
            $sdLink1->Html = $this->NoDeleteButtonItemText;
            $sdLink1->OnClick = $this->NoDeleteButtonItemClientAction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow'); return false;";
            $li->SetChild($sdLink1);
            
            $menuUl->SetChild($li);
        }

        $leftDiv->SetChild($menuUl);

        $rightDiv = new Div();
        $rightDiv->CssClass = "rightColumn ".$this->ScrollClass;
        
        $htmlTable = $this->Mode == "table" ? new HtmlTable() : new Div();
        $htmlTable->CssClass= $this->Mode == "table" ? "table table-striped table-bordered table-hover" :"divTable";
        $htmlTable->Id="dataTable-".$this->TableId;
        $tdCount = 1;

        if (!empty($this->Header)) {
            $tr =  $this->Mode == "table" ? new HtmlTableTr() : new Div();
            $tr->CssClass = $this->Mode == "table" ? "header" : "header row";
            if ($this->MultiSelect)
            {
                $tdCount++;
            }
            foreach ($this->Header as $row) {
                $tdCount++;
            }
            if ($this->ShowEdit) {
                $tdCount++;
            }
            if ($this->ShowDelete) {
                $tdCount++;
            }
            if ($this->ShowHistoryButton) {
                $tdCount++;
            }
            if ($this->ShowCopyItem) {
                $tdCount++;
            }
            if (!empty($this->SpecialLinks))
            {
                for ($c = 0; $c< count($this->SpecialLinks);$c++)
                {
                    $tdCount++;
                }
            }
            $this->_width = 100/$tdCount;
            $this->_width = $this->_width."%";
            if ($this->MultiSelect) {
                $td =  $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $checkbox = new Checkbox();
                $checkbox->OnClick = "MultiSelect('$this->MultiselectIdentificator',this)";
                $td->SetChild($checkbox);
                $tr->SetChild($td);
                
            }

            foreach ($this->Header as $row) {
                $th = $this->Mode == "table" ? new HtmlTableTh() : new Div();
                $th->Html = $row->ShowName;
                $th->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $th->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                if ($this->ShowSort) {
                    $sortASC = new Link();
                    $sortASC->Html = $this->SortAscText;
                    $sortASC->OnClick = $this->SortClientFunction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow','$row->ColumnName','ASC'); return false";
                    $th->SetChild($sortASC);

                    $sortDESC = new Link();
                    $sortDESC->Html = $this->SortDescText;
                    $sortDESC->OnClick = $this->SortClientFunction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow','$row->ColumnName','DESC'); return false";
                    $th->SetChild($sortDESC);
                }
                $tr->SetChild($th);
            }


            if ($this->ShowEdit) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $td->Html = "&nbsp;";
                $tr->SetChild($td);
            }
            if ($this->ShowDelete) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $td->Html = "&nbsp;";
                $tr->SetChild($td);
            }
            if ($this->ShowHistoryButton) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $td->Html = "&nbsp;";
                $tr->SetChild($td);
                
            }
            
            if ($this->ShowCopyItem) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $td->Html = "&nbsp;";
                $tr->SetChild($td);
            }
            if (!empty($this->SpecialLinks))
            {
                for ($c = 0; $c< count($this->SpecialLinks);$c++)
                {
                    $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                    $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                    $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                    $td->Html = "&nbsp;";
                    $tr->SetChild($td);
                }
            }

            $htmlTable->SetChild($tr);
            if ($this->ShowFiltr) {
                $tr = $this->Mode == "table" ? new HtmlTableTr() : new Div();
               
                $tr->CssClass = "filtrHeader";
                if ($this->MultiSelect) {
                    $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                    $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                    $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                    $tr->SetChild($td);
                }
                $length = count($this->Header);
                $actualColumn = 0;
                foreach ($this->Header as $row) {
                    $td = new HtmlTableTd();
                    $actualColumn++;

                    if (!empty($row->FiltrType)) {
                        if ($row->FiltrType == TableHeaderFiltrType::$Textbox) {
                            $filtrTextBox = new Textbox();
                            $filtrTextBox->Id = "filtr-" . $row->ColumnName;
                            $filtrTextBox->CssClass = "filtrTextbox";
                            $filtrTextBox->Value = $row->Value1;
                             $filtrCombobox = new Select();
                            $option1 = new Option();
                            $option1->Html = $this->GetWord("word121");
                            $option1->Value = "LIKE";
                            $option1->Selected = $option1->Value == $row->Value3 ? true: false;

                            $option2 = new Option();
                            $option2->Html = $this->GetWord("word122");
                            $option2->Value = "%LIKE";
                            $option2->Selected = $option2->Value == $row->Value3 ? true: false;

                            $option3 = new Option();
                            $option3->Html = $this->GetWord("word123");
                            $option3->Value = "LIKE%";
                            $option3->Selected = $option3->Value == $row->Value3 ? true: false;

                            $option4 = new Option();
                            $option4->Html = $this->GetWord("word124");
                            $option4->Value = "%LIKE%";
                            $option4->Selected = $option4->Value == $row->Value3 ? true: false; 


                            $option5 = new Option();
                            $option5->Html = $this->GetWord("word125");
                            $option5->Value = "NOT LIKE";
                            $option5->Selected = $option5->Value == $row->Value3 ? true: false;

                            $option6 = new Option();
                            $option6->Html = $this->GetWord("word126");
                            $option6->Value = "NOT %LIKE";
                            $option6->Selected = $option6->Value == $row->Value3 ? true: false;

                            $option7 = new Option();
                            $option7->Html = $this->GetWord("word127");
                            $option7->Value = "NOT LIKE%";
                            $option7->Selected = $option7->Value == $row->Value3 ? true: false;

                            $option8 = new Option();
                            $option8->Html = $this->GetWord("word128");
                            $option8->Value = "NOT %LIKE%";
                            $option8->Selected = $option8->Value == $row->Value3 ? true: false;

                            $filtrCombobox->SetChild($option1);
                            $filtrCombobox->SetChild($option2);
                            $filtrCombobox->SetChild($option3);
                            $filtrCombobox->SetChild($option4);
                            $filtrCombobox->SetChild($option5);
                            $filtrCombobox->SetChild($option6);
                            $filtrCombobox->SetChild($option7);
                            $filtrCombobox->SetChild($option8);
                            $filtrCombobox->Id="filtr-" . $row->ColumnName."-LIKEMODE";

                            


                            $td->SetChild($filtrCombobox);
                            $td->SetChild($filtrTextBox);
                            
                        }
                        
                        $filtrCombobox2 = new Select();
                            $option9 = new Option();
                            $option9->Value = "AND";
                            $option9->Selected = $option9->Value == $row->Value2 ? true: false;
                            $option9->Html = $this->GetWord("word129");

                            $option10 = new Option();
                            $option10->Value = "OR";
                            $option10->Selected = $option10->Value == $row->Value2 ? true: false;
                            $option10->Html = $this->GetWord("word130");
                            
                            $filtrCombobox2->SetChild($option9);
                            $filtrCombobox2->SetChild($option10);
                            $filtrCombobox2->Id = "filtr-" . $row->ColumnName."-ANDOR";
                        if ($actualColumn < $length && $row->FiltrType != TableHeaderFiltrType::$None )
                                $td->SetChild($filtrCombobox2);
                    } else {
                        $td->Html = "";
                    }
                    $tr->SetChild($td);
                }
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $searchLink = new Link();
                $searchLink->Html = $this->SearchButtonText;
                $searchLink->OnClick = $this->SearchClientAction . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow'); return false;";

                $td->SetChild($searchLink);
                $tr->SetChild($td);

                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $searchClearLink = new Link();
                $searchClearLink->Html = $this->SearchCancelButtonText;
                $searchClearLink->OnClick = $this->SearchClientActionClear . "('$this->TableId','$this->RefreschTable','$this->ControllerName','$this->DataSource','$this->PrefixIdRow'); return false;";
                $td->SetChild($searchClearLink);
                $tr->SetChild($td);
                $htmlTable->SetChild($tr);
            }
            $id = "";
            $name = "";
            $webId = "";
            $webColumn = "";

            if (!empty($this->Data)) {
                $this->GenerateNoData($htmlTable, $tdCount, "dn");
                
                
                foreach ($this->Data as $row) {
                    $tr = $this->Mode == "table" ? new HtmlTableTr() : new Div();
                    $tr->CssClass = $this->Mode == "div" ? "row" : "";
                    $this->GenerateMultiselect($tr);
                    $id = "";
                    $name = "";
                    $webId = "";
                    
                    $webColumn = "";
                    foreach ($row as $key => $value) {
                        if ($key == $this->IdColumn) {
                            $id = $value;
                            $tr->Id = $this->PrefixIdRow . $id;
                            
                        }
                        if ($key == $this->ColName) {
                            $name = $value;
                        }
                        if ($key == $this->WebIdColumn)
                        {
                            $webId = $value;
                            $webColumn = $key;
                        }
                        if (in_array($key, $this->HideColumns))
                            continue;
                        $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                        $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                        $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                        if ($this->ShowEdit)
                        {
                            $td->OnDoubleClick = "TrClick('$id')";
                        }
                        $td->Html = $value;
                        $tr->SetChild($td);
                    }
                    $this->GenerateEditTd($tr, $id);
                    $this->GenerateHistoryTd($tr, $id);
                    $this->GenerateDeleteTd($tr, $name, $id);
                    $this->GenerateRecoveryTd($tr, $name, $id);
                    $this->GenerateCopy($tr, $name, $id);
                    $this->GenerateSpecialLinks($tr,$webColumn,$webId,$this->IdColumn,$id);
                    $htmlTable->SetChild($tr);
                }
                
            }
            else {
                $this->GenerateNoData($htmlTable, $tdCount, "");
            }
            if (!empty($this->TemplateRow)) {
                $hideTextBox = new Div();
                $hideTextBox->CssClass = "templateRow dn";

                $templateRowHtml = "";
                $idCol = "";
                $nameCol = "";
                $templateRowHtml .= $this->GenerateMultiselect(null);

                foreach ($this->TemplateRow as $row) {
                    if ($this->IdColumn == $row)
                        $idCol = $row;
                    if ($this->ColName == $row)
                        $nameCol = $row;
                    if (in_array($row, $this->HideColumns))
                        continue;
                    $templateRowHtml.="<td>{" . $row . "}</td>";
                }
                if ($this->ShowEdit) {
                    $templateRowHtml.=$this->GenerateEditTd(null, "{" . $idCol . "}");
                }
                if ($this->ShowHistoryButton) {
                    $templateRowHtml.=$this->GenerateHistoryTd(null, "{" . $idCol . "}");
                }

                if ($this->ShowDelete) {
                    $templateRowHtml.=$this->GenerateDeleteTd(null, "{" . $nameCol . "}", "{" . $idCol . "}");
                }
                if ($this->ShowRecoveryButton) {
                    $templateRowHtml.=$this->GenerateRecoveryTd(null, "{" . $nameCol . "}", "{" . $idCol . "}");
                }
                if ($this->ShowCopyItem) {
                    $templateRowHtml .=$this->GenerateCopy(null, "{" . $nameCol . "}", "{" . $idCol . "}");
                }
                if (!empty($this->SpecialLinks))
                {
                    $templateRowHtml .=$this->GenerateSpecialLinks(null, $webColumn,$webId,$this->IdColumn,"{" . $idCol . "}");
                }

                $tr = $this->Mode == "table" ? new HtmlTableTr() : new Div();
                $tr->Id = $this->PrefixIdRow . "{Id}";
                $tr->CssClass = "newRow dn";
                $tr->Html = $templateRowHtml;
                $htmlTable->SetChild($tr);
            }
            $rightDiv->SetChild($htmlTable);
            $mainDiv->SetChild($leftDiv);
            $mainDiv->SetChild($rightDiv);

            return $mainDiv->RenderHtml($mainDiv);
        }
    }

    private function GenerateDeleteTd($tr, $name, $id) {
        if ($this->ShowDelete) {
            if ($this->ShowDeleteQuestion) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $deleteLink = new Link();
                $deleteLink->Html = $this->DeleteButtonText;
                $deleteLink->Href = "#";
                $deleteLink->DataTarget = "#DeleteDialog";
                $deleteLink->DataToggle = "modal";
                $deleteLink->OnClick = "SetDeleteItem('$name','$id');return false;";
                $td->SetChild($deleteLink);
                if ($tr != null)
                    $tr->SetChild($td);
                else {
                    return $td->RenderHtml($td);
                }
            }
        }
    }

    private function GenerateRecoveryTd($tr, $name, $id) {
        if ($this->ShowRecoveryButton) {
            if ($this->ShowRecoveryQuestionDialog) {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1 showInDeleted dn" :"showInDeleted dn";
                
                $deleteLink = new Link();
                $deleteLink->Html = $this->RecoveryButtonText;
                $deleteLink->Href = "#";
                $deleteLink->DataTarget = "#RecoveryDialog";
                $deleteLink->DataToggle = "modal";
                $deleteLink->OnClick="RecoveryDialog('$name','$id')";
                $td->SetChild($deleteLink);
                if ($tr != null)
                    $tr->SetChild($td);
                else {
                    return $td->RenderHtml($td);
                }
            }
        }
    }

    private function GenerateNoData($htmlTable, $tdCount, $dn) {
        //$tdCount = $tdCount-1;
        $tr = $this->Mode == "table" ? new HtmlTableTr() : new Div();
        $tr->CssClass = "noData $dn";
        $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
        $td->Html = $this->NoDataMessage;
        $td->ColSpan = $tdCount;
        $tr->SetChild($td);
        if ($htmlTable != null)
            $htmlTable->SetChild($tr);
        else
            return $tr->RenderHtml($tr);
    }

    private function GenerateCopy($tr, $name, $id) {
        if ($this->ShowCopyItem) {
            $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
            $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
            $td->CssClass=$this->Mode == "div" ? "col-md-1 showInNormal" :"showInNormal";
            
            $cAction = new Link();
            $cAction->Html = $this->CopyItemText;
            $cAction->DataTarget = "#CopyDialog";
            $cAction->DataToggle = "modal";
            $cAction->OnClick="CopyDialog('$name','$id')";
            $td->SetChild($cAction);
            if ($tr != null)
                $tr->SetChild($td);
            else {
                return $td->RenderHtml($td);
            }
        }
    }

    private function GenerateHistoryTd($tr, $id) {
        if ($this->ShowHistoryButton) {
            $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
            $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
            $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
            $aAdd = new Link();
            $aAdd->Html = $this->HistoryButtonText;
            $aAdd->DataTarget = "#HistoryDialog";
            $aAdd->DataToggle = "modal";
            $aAdd->OnClick = "$this->HistoryButtonClientAction('$this->ControllerName','$this->HistoryButtonServerAction','$this->ModelName','$id');return false;";
            $td->SetChild($aAdd);
            if ($tr != null)
                $tr->SetChild($td);
            else
                return $td->RenderHtml($td);
        }
    }

    private function GenerateEditTd($tr, $id) {
        if ($this->ShowEdit) {
            $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
            $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
            $td->CssClass=$this->Mode == "div" ? "col-md-1 showInNormal" :"showInNormal";
            
            $aAdd = new Link();
            $aAdd->Html = $this->EditLinkText;
            $aAdd->DataTarget = "#$this->JoinAddDialogId";
            $aAdd->DataToggle = "modal";
            $aAdd->Id = "EditId$id";
            $aAdd->OnClick = "ShowFirstTab();ShowEditForm('$this->ControllerName','$this->DetailFunction','$id','$this->ModelName'); return false;";
            $td->SetChild($aAdd);
            if ($tr != null)
                $tr->SetChild($td);
            else
                return $td->RenderHtml($td);
            
            
        }
    }

    private function GenerateMultiselect($tr) {
        if ($this->MultiSelect) {
            $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
            $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
            $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
            $checkbox = new Checkbox();
            $checkbox->CssClass = $this->MultiselectIdentificator;
            $td->SetChild($checkbox);
            $td->CssClass = "TdMultiSelect";

            if ($tr != null)
                $tr->SetChild($td);
            else {
                $td->CssClass = "TdMultiSelectTemplate TdMultiSelect";
                return $td->RenderHtml($td);
            }
        }
    }
    private function  GenerateSpecialLinks($tr,$webColumn,$webId,$idColumn,$id)
    {
        if (!empty($this->SpecialLinks))
        {
            $html = "";
            foreach ($this->SpecialLinks as $link)
            {
                $td = $this->Mode == "table" ? new HtmlTableTd() : new Div();
                $td->Style=$this->Mode == "div" ? "width:$this->_width; float:left;" :"";
                $td->CssClass=$this->Mode == "div" ? "col-md-1" :"";
                $link = str_replace("{".$webColumn."}", $webId, $link);
                $link = str_replace("{".$idColumn."}", $id, $link);
                $td->Html =  $link;
                if ($tr != null)
                    $tr->SetChild($td);
                else 
                    $html .= $td->RenderHtml ($td);
            }
            if (!empty($html))
                return $html;
            
                
        }
    }

}
