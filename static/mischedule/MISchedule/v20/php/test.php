<?
require_once("wafunctions.php");
require_once("divisionlist.php");

$divList = new DivisionList();
$divList->readListFromWA();
$divList->outputList();

/*
$waKey = "";
$errRet = getKeyFromWA($waKey);
if ($errRet == 0)
{
	printf("Returned successfully with key: %s\n<BR>", $waKey);
}
else printf("Error getting key\n<BR>");

$page = "";
$errRet = getWAPage("1420", $waKey, "ACC", "271", $page);
if ($errRet == 0)
{
	printf("Successfully retrived divisions:\n<BR>");
	echo $page;
}
else printf("Error getting divisions\n<BR>");
*/

?>