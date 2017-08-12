<?php

namespace Utils;

use Utils\Utils;
use PHPExcel_IOFactory;
use mPDF;

class Files {

    /** metoda pro zápis do souboru
     * @param stirng $filePath cesta k souboru
     * @param string $content 
     * @param $type typ zápis
     */
    public static function WriteFile($filePath, $content, $type = "a+") {
        try {
            $file = fopen($filePath, $type);
            fwrite($file, $content);
            fclose($file);
        } catch (Exception $e) {
            Page::ApplicationError($e);
        }
    }

    /** metoda pro načtení souboru 
     * @param $path cesta k souboru 
     *  */
    public static function ReadFile($path) {
        if (Files::FileExists($path)) {
            try {
                $file = fopen($path, "r");
                if (filesize($path) == 0)
                    return;
                $content = fread($file, filesize($path));
                fclose($file);
                return $content;
            } catch (Exception $e) {
                Page::ApplicationError($e);
            }
        }
    }

    /** metoda pro otestování zda soubor exisuje 
     * @param $path string cesta k souboru 
     */
    public static function FileExists($path) {
        if (file_exists($path))
            return true;
        else {
            return false;
        }
    }

    /** metoda pro zápis do logu */
    public static function WriteLogFile($data) {

        Files::WriteFile(LOG_PATH . ERROR_LOG_FILENAME, "\n------ ERORR TIME: " . Utils::Now() . "----\n");
        Files::WriteFile(LOG_PATH . ERROR_LOG_FILENAME, $data);
        Files::WriteFile(LOG_PATH . ERROR_LOG_FILENAME, "\n---------------------------------------\n");
    }

    /** metoda pro upload souboru */
    public static function FileUpload() {
        try {
            if ($_FILES['file']['error'] > 0) {
                Files::WriteLogFile($_FILES['file']['error']);
            } else {
                if (self::IsPHP($_FILES['file']['name'])) {

                    throw \Types\xWebExceptions::$UploadPHPFile;
                    return;
                }
                $uploadPath = 'res/' . $_FILES['file']['name'];
                $ext = self::GetFileExtension($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
                $newName = strtolower(\Utils\StringUtils::GenerateRandomString() . "." . $ext);
                if (Files::FileExists("res/" . $newName)) {
                    return self::FileUpload();
                } else {
                    rename($uploadPath, "res/" . $newName);
                    
                    if (strpos($newName, ".jpg") !== false || strpos($newName, ".gif") !== false || strpos($newName, ".png") !== false || strpos($newName, ".jpeg") !== false) {
                        $img = new Image();
                        $web = \Model\Webs::GetInstance();
                        $webId = empty($_GET["webid"]) ? 0 : $_GET["webid"];
                        if ($webId > 0) {
                            $web->GetObjectById($webId, true);
                            $tmpName = ROOT_PATH . "res/" . $newName;
                            $newFileNameB = $img->CreateFileName($tmpName, "_b");
                            $img->Resizer($tmpName, $newFileNameB, $web->BigWidth, $web->BigHeight);
                            $newFileNameM = $img->CreateFileName($tmpName, "_m");
                            $img->Resizer($tmpName, $newFileNameM, $web->MediumWidth, $web->MediumHeight);
                            $newFileNameS = $img->CreateFileName($tmpName, "_s");
                            $img->Resizer($tmpName, $newFileNameS, $web->SmallWidth, $web->SmallHeight);
                        }
                    }
                    return "res/" . $newName;
                }
            }
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    public static function UploadFiles() {
        $filePath = self::FileUpload();
        $content = new \Objects\Content();
        //$data="<items><FileUpload><![CDATA[$filePath]]></FileUpload></items>";
        $data[0][0] = "FileUpload";
        $data[0][1] = "$filePath";
        $name = basename($filePath);
        return $content->CreateFile($name, $_GET["langId"], $_GET["parentId"], false, "", "", "", array(), $data);
    }

    /** otestování zda se jedná o xml */
    public static function IsXml($file) {
        $info = pathinfo($file);
        if ($info["extension"] == "xml")
            return true;
        return false;
    }

    private static function IsPHP($file) {
        $info = pathinfo($file);
        if ($info["extension"] == "php")
            return true;
        return false;
    }

    /*     * test zda se jedná o excel */

    public static function IsExcel($file) {
        $info = pathinfo($file);
        if ($info["extension"] == "xls" || $info["extension"] == "xlsx")
            return true;
        return false;
    }

    /** načtení xls souboru */
    public static function ReadExcel($path) {
        if (Files::FileExists($path)) {
            $objPHPExcel = PHPExcel_IOFactory::load($path);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, false, false, false);
            return $sheetData;
        }
    }

    public static function GetFileExtension($file) {
        try {
            $info = pathinfo($file);
            return $info["extension"];
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

    public static function CreatePDF($html, $fileName) {
        try {
            include_once './Kernel/ExternalApi/mpdf60/mpdf.php';
            self::CreateHtmlFile($html, $fileName);
            $mpdf = new mPDF('utf-8', 'A4', '', '', 0, 0, 0, 0, 0, 0);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
            $mpdf->WriteHTML($html);
            $mpdf->Output(ROOT_PATH . "res/" . $fileName);
            return ROOT_PATH . "res/" . $fileName;
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

    public static function CreateHtmlFile($html, $fileName) {
        try {
            $path = PDF_TEMPLATES_PATH . $fileName . ".html";
            self::WriteFile($path, $html);
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

    public static function ZipFolder($path) {
        try {
            $zip = new \ZipArchive();
            $archiveName = \Utils\StringUtils::GenerateRandomString() . ".zip";
            $zippath = TEMP_PATH . $archiveName;
            $zip->open($zippath, \ZipArchive::CREATE);
            $files = Folders::GetObjectsInFolder($path, false, true);
            self::AddFolderToZip($zip, $files, $path);
            $zip->close();
            return "/Temp/" . $archiveName;
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

    private static function AddFolderToZip($zip, $files, $path, $zipPath = "") {
        try {
            foreach ($files as $file) {
                $filePath = $path . "/" . $file["Name"];
                if (is_file($filePath)) {
                    $zip->addFile($filePath, $zipPath . basename($filePath));
                }
                if (is_dir($filePath)) {
                    $child = $file["Childs"];
                    if (!empty($child)) {
                        $zipPath2 = "";

                        if (empty($zipPath))
                            $zipPath2 = $file["Name"] . "/";
                        else
                            $zipPath2 = $zipPath . $file["Name"] . "/";
                        self::AddFolderToZip($zip, $child, $filePath, $zipPath2);
                    }
                }
            }
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

    public static function DowlandFile($file_url) {
        try {
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
            readfile($file_url);
            exit;
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
        }
    }

}
