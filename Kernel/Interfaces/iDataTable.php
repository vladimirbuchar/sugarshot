<?php
namespace Inteface;
interface iDataTable {
    public function OnCreateTable();
    public function InsertDefaultData();
    public function SetValidate($mode = false);
    public function TableMigrate();
    public function TableExportSettings();
}
