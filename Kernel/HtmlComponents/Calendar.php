<?php
namespace HtmlComponents;
class Calendar extends HtmlComponents {
    public function __construct($id)
    {
        $this->Id = $id;
        $this->HtmlTag = "input";
        $this->Type = "text";
        $this->Js.='$("#'.$this->Id.'").datetimepicker({';
        $this->Js.="dayOfWeekStart : 1,";
        $this->Js.="lang:'cs'";
        $this->Js.="});";
    }
}



