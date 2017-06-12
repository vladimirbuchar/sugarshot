<?php
namespace HtmlComponents;
class FileUpload extends HtmlComponents {
    public function __construct()
    {
        $this->HtmlTag = "input";
        $this->Type = "file";
    }
}



