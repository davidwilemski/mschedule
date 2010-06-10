<?php
require_once "inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mscourseinfo.php";

//create output and input object
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$input = new MSInputGrabber();


//generate the formvars
$searchform = new MSHTMLForm("courseCatalogSearch", "coursecatalog.php",_BUTTON_SEARCH);
$searchform->add_textField("classid",_SEARCHFIELD_CLASSID,8);
$searchform->add_textField("dept",_SEARCHFIELD_DEPT,10);
$searchform->add_textField("number",_SEARCHFIELD_NUMBER,3);
$searchform->add_textField("section",_SEARCHFIELD_SECTION,3);

//assign the form to the smarty
$smarty->assign("searchform",$searchform);

//if we got good input, do the search
if ($input->exists_input()) {
	
	//do a CourseInfo search, retrieving an array of MSCourses
	$courseinfo = new MSCourseInfo();
	$courses = $courseinfo->search(
								$input->postVar("classid"),
								$input->postVar("dept"),
								$input->postVar("number"),
								$input->postVar("section")
								);

	//put those MSCourses into the smarty
	$smarty->assign_by_ref("courses",$courses);
}

//generate the contents of the page
$content = $smarty->fetch("coursecatalog.shtml");


//debugmjp: set some debugging values
/*
if($input->is_secure())
	$MSDEBUG->add("security?","enabled");

$MSDEBUG->add("POSTED",var_export($input->arrayPOST(),true));
*/

//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle",_TITLE_COURSE_CATALOG);
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();

?>
