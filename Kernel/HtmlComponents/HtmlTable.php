<?php
namespace HtmlComponents;
class HtmlTable extends HtmlComponents {
    public function __construct()
    {
        $this->HtmlTag = "table";
    }
}
class HtmlTableTr extends HtmlComponents
{
    public function  __construct()
    {
        $this->HtmlTag = "tr";
    }
}
class HtmlTableTd extends HtmlComponents
{
    public $ColSpan = 0;
    public function  __construct()
    {
        $this->HtmlTag = "td";
    }
}
class HtmlTableTh extends HtmlComponents
{
    public function  __construct()
    {
        $this->HtmlTag = "th";
    }
}


