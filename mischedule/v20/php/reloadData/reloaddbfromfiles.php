<?
require_once $_SERVER['DOCUMENT_ROOT']."/mschedule/spiders/waparsefunctions.php";
require_once "storefunctions.php";
require_once "../dbfunctions.php";

$folder = "C:\\Documents and Settings\\Kyle\\Desktop\\wadata\\pages\\";

if(isset($_GET['timeLimit'])){
	ini_set('max_execution_time', $_GET['timeLimit']);
}
header("Content-type: text/plain");

function reloadDivisionsDatabaseFromFile()
{
	global $folder;
	execQuery("TRUNCATE TABLE `divisions`");
	$file = $folder.'subject_list.txt';
	$array = parseSubjects($file);	
	storeDivisions($array);
}

function reloadCoursesDatabaseFromFiles()
{
	global $folder;
	execQuery("TRUNCATE TABLE `courses`");
	$i = 0;
	$file = $folder.$i.'.txt';
	while(is_file($file)){
		$array = parseCourses($file);
		storeCourses($array);
		$i++;
		$file = $folder.$i.'.txt';
	}
}

function reloadSectionsDatabaseFromFiles()
{
	global $folder;
	execQuery("TRUNCATE TABLE `sections`");
	execQuery("TRUNCATE TABLE `meetings`");
	execQuery("TRUNCATE TABLE `locations`");
	$i = 0;
	$j = 0;
	$file = $folder.$i.'_'.$j.'.txt';

	while(is_file($file)){
		$enteredClassNums = array();
		while(is_file($file)){
			$array = parseSections($file);
			storeSections($array, $enteredClassNums);
			$j++;
			$file = $folder.$i.'_'.$j.'.txt';
		}
		$i++;
		$j = 0;
		$file = $folder.$i.'_'.$j.'.txt';
	}
	
}

connectToDB();
switch($_GET['cmd']){
	case 'divisions';
		reloadDivisionsDatabaseFromFile();
		break;
	case 'courses';
		reloadCoursesDatabaseFromFiles();
		break;
	case 'sections';
		reloadSectionsDatabaseFromFiles();
		break;
	default:
		print "cmd: divisions, courses, or sections";
		break;
}