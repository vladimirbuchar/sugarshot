<?php
namespace HtmlComponents;
class Checkbox extends HtmlComponents {
    public function __construct()
    {
        $this->HtmlTag = "input";
        $this->Type = "checkbox";
    }
}



