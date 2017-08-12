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
    public static function GetObjectsInFolder($path,$onlyFile = false,$firstLevel = true) {
        
        $folders = scandir($path);
        $out = array();
        $child =array();
        $x = 0;
        for ($i = 0; $i < count($folders); $i++) {
            $name = $folders[$i];
            if ($name == "." || $name == "..")
                continue;
            if ($onlyFile && is_dir($path.$name))
                continue;
            if ($firstLevel)
            {
                $path2 = $path."/".$name;
                if (is_dir($path2))
                {
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
    
    public static function  FolderExists($path)
    {
        return Files::FileExists($path);
    }

    public static function CreateFolder($path,$name,$security=0777)
    {
        mkdir($path.$name);
        chmod($path.$name,$security);
    }
    
    public static function  CopyFolder($src,$dst)
    {
          $dir = opendir($src); 
          @mkdir($dst); 
          while(false !== ( $file = readdir($dir)) ) { 
              if (( $file != '.' ) && ( $file != '..' )) { 
                  if ( is_dir($src . '/' . $file) ) { 
                      self::CopyFolder($src . '/' . $file,$dst . '/' . $file); 
                      
                  } 
                  else { 
                      try{
                          if (is_readable($src . '/' . $file))
                          {
                            copy($src . '/' . $file,$dst . '/' . $file); 
                          }
                      }
                      catch(Exception $e)
                      {
                          
                      }
                      
                  } 
            } 
        } 
        closedir($dir); 
    }


    
    
    public static function FileExplorer() {
        $_POST['dir'] = urldecode($_POST['dir']);
        $root = FILE_REPOSITORY_PATH;
        if (file_exists($root . $_POST['dir'])) {
            $files = scandir($root . $_POST['dir']);
            
            $_SESSION["LastFolder"] =$root . $_POST['dir']."";
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file)) {
                        echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
                    }
                }
                // All files
                foreach ($files as $file) {
                    if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file)) {
                        $ext = preg_replace('/^.*\./', '', $file);
                        echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
                    }
                }
                echo "</ul>";
            }
        }
    }

}
