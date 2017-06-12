<?php
namespace Model;
class PluginList extends DatabaseViews{
    public function PluginList()
    {
        $this->ObjectName = "PluginList";
        $this->SqlView = "SELECT * FROM Plugins
 

";
                
    }
    public function TableExportSettings()
    {
        
    }


}
