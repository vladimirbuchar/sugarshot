<?php

namespace Utils;

use Utils\Files;

/** třída pro práci se složkami
 * @author  Vladimír Buchar
 */
class Folders {

    /** metoda vrací seznam objektů ve složce
     * @author  vladimír Bucha
     * @param string $path cesta ke složce ze které chceme získat obsha
     * @return  array
     */
    public static function GetObjectsInFolder($path, $onlyFile = false, $firstLevel = true) {

        if (!file_exists($path))
            return array();
        $folders = scandir($path);
        $out = array();
        $child = array();
        $x = 0;
        for ($i = 0; $i < count($folders); $i++) {
            $name = $folders[$i];
            if ($name == "." || $name == "..")
                continue;
            if ($onlyFile && is_dir($path . $name))
                continue;
            if ($firstLevel) {
                $path2 = $path . "/" . $name;
                if (is_dir($path2)) {
                    $child = self::GetObjectsInFolder($path2, $onlyFile);
                }
            }
            $out[$x]["Name"] = $name;
            if (!empty($child))
                $out[$x]["Childs"] = $child;
            $x++;
        }
 
        return $out;
    }

    public static function FolderExists($path) {
        return Files::FileExists($path);
    }

    public static function CreateFolder($path, $name, $security = 0777) {
        mkdir($path . $name);
        chmod($path . $name, $security);
    }

    public static function SetPermitions($path, $name, $security = 0777) {
        chmod($path . $name, $security);
    }

    public static function CopyFolder($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    self::CopyFolder($src . '/' . $file, $dst . '/' . $file);
                } else {
                    try {
                        if (is_readable($src . '/' . $file)) {
                            copy($src . '/' . $file, $dst . '/' . $file);
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
        }
        closedir($dir);
    }
    
    public static function DeleteObjects($path,$ignoreDelete = array())
    {
        $files = self::GetObjectsInFolder($path);
        foreach ($files as $row)
        {
            $name = $row["Name"];
            if(is_dir($path.$name))
            {
                self::DeleteObjects($path.$name."/",$ignoreDelete);
            }
            if (!in_array($name, $ignoreDelete))
            {
                if (is_file($path.$name))
                {
                    unlink($path.$name);
                }
                if (is_dir($path.$name))
                {
                    rmdir($path.$name);
                }
            }
        }
    }
}
