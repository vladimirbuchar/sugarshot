<?php

namespace Model;
use Dibi;
class DatabaseFunction extends SqlDatabase{
/*

 DROP FUNCTION IF EXISTS GetParentFolder;
DELIMITER $$
CREATE FUNCTION GetParentFolder(id INT)
  RETURNS TEXT
  LANGUAGE SQL -- This element is optional and will be omitted from subsequent examples
BEGIN
  DECLARE myid INT UNSIGNED;
  	SELECT ID INTO myid FROM content WHERE ParentId=Id;
  	RETURN myid;
END;
$$
DELIMITER ;
 *  */
    protected $FunctionCode ="";
    public function  __construct()
    {
        //parent::
        $this->IsFunction = true;
        parent::__construct();
        
    }
    public function CreateFunction()
    {
        try{
            dibi::query("DROP FUNCTION IF EXISTS $this->ObjectName");
            dibi::query($this->FunctionCode);
        }
        catch (Exception $ex)
        {
            \Kernel\Page::ApplicationError($ex);
        }
    }   
}   
