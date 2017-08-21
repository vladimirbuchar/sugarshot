<?php
namespace Utils;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author vlada
 */
class TreeUtils {
    public static function CreateTreeDiscusion($userGroup,$langid,$search)
    {
        $content = new \Objects\Content();
        $cssList = $content->GetDiscusionList($userGroup, $langid, false, $search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    
    public static function CreateTreeInqury($userGroup,$langid,$search)
    {
        $content = new \Objects\Content();
        $cssList = $content->GetInquryList($userGroup, $langid, false, $search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
 }
