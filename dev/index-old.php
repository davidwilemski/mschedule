<?php
require_once "inc/common.php";

//generate the contents of the page
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$content = $smarty->fetch("index.shtml");

//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle",_TITLE_INDEX);
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();

?>
