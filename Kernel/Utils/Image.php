<?php

namespace Utils;

use Utils\Files;
use Eventviva\ImageResize;

class Image {

    public function Resizer($img, $newfilename, $w, $h) {
        if (Files::FileExists($img))
        {
            $image = new ImageResize($img);
            $image->resizeToBestFit($w, $h);
            $image->save($newfilename);
        }
    }

    public function CreateFileName($fileName, $resize) {
        $ext = Files::GetFileExtension($fileName);
        return str_replace("." . $ext, $resize . "." . $ext, $fileName);
    }

}
