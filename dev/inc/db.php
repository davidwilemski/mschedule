<?
//this file conflicts with a standard php file so I doubt we can use it because it keeps giving us errors

if ( !defined('CONFIG_INCLUDED') )
	exit("configuration.php missing");

require_once $cfg['ms_rootpath']['server']."/classes/class.msdbcn.php";

$MSDB = new MSDbCn();
?>
