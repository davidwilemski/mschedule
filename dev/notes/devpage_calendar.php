<?php
require_once "inc/common.php";

//generate the contents of the page
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$content = $smarty->fetch("devpages/calendar.shtml");

//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle","WebCalendar Development Test");
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();

?>
