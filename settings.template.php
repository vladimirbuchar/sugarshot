<?php 

if (SQLMODE == "mysql") 
{
    GlobalSettings::DeclareConst("SQL_SERVER", "");
    GlobalSettings::DeclareConst("SQL_LOGIN","");
    GlobalSettings::DeclareConst("SQL_PASSWORD","");
    GlobalSettings::DeclareConst("SQL_DATABASE","");
    GlobalSettings::DeclareConst("CHARSET","utf8");
    GlobalSettings::DeclareConst("SQL_DRIVER","mysqli");
 } 
GlobalSettings::DeclareConst("ISDEBUG",TRUE);
GlobalSettings::DeclareConst("REDIRECT_ALL_EMAIL",TRUE);
GlobalSettings::DeclareConst("DEVEL_EMAIL","");
GlobalSettings::DeclareConst("SHOW_ERRORS",FALSE);
GlobalSettings::DeclareConst("USECACHE",FALSE);
    
