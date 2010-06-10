<?
require_once "../inc/configuration.php";
require_once "../inc/common.php";
require_once "waparsefunctions.php";

$folder = "C:\\Documents and Settings\\Kyle\\Desktop\\wadata\\pages\\";
ini_set('max_execution_time', 240);

//takes array (from parseSubjects) and puts it into the database
function storeSubjects($array){
	
	global $MSDB, $cfg;
	
	foreach($array as $key => $value){
		$MSDB->insert($cfg['db']['tables']['wa_subjects'], $value);
	}
}


//takes array (from parseCourses) and puts it into the database
function storeCourses($array){
	
	global $MSDB, $cfg;
	
	foreach($array as $key => $value){
		$MSDB->insert($cfg['db']['tables']['wa_courses'], $value);
	}
}



function storeSections($array)
{
	global $MSDB, $cfg;
	
	foreach($array as $key => $section){
		$meetings = array_pop($section);
		$MSDB->insert($cfg['db']['tables']['wa_sections'], $section);
		foreach($meetings as $key => $meeting){
			$meeting['courseID'] = $section['courseID'];
			$MSDB->insert($cfg['db']['tables']['wa_meetings'], $meeting);
		}
	}
}

function reloadSubjectsDatabaseFromFile()
{
	global $MSDB, $MSERROR, $folder;
	$MSDB->sql("TRUNCATE TABLE `mschedule_wa_subjects`");
	$file = $folder.'subject_list.txt';
	print $file;
	$array = parseSubjects($file);
	storeSubjects($array);
	
	var_dump($MSERROR->messages);
	
}

function reloadCoursesDatabaseFromFiles()
{
	global $MSDB, $MSERROR, $folder;
	$MSDB->sql("TRUNCATE TABLE `mschedule_wa_courses`");
	$i = 0;
	$file = $folder.$i.'.txt';
	while(is_file($file)){
		$array = parseCourses($file);
		storeCourses($array);
		$i++;
		$file = $folder.$i.'.txt';
	}
	var_dump($MSERROR->messages);
}

function reloadSectionsDatabaseFromFiles()
{
	global $MSDB, $MSERROR, $folder;
	$MSDB->sql("TRUNCATE TABLE `mschedule_wa_sections`");
	$MSDB->sql("TRUNCATE TABLE `mschedule_wa_meetings`");
	$i = 0;
	$j = 0;
	$file = $folder.$i.'_'.$j.'.txt';
	while(is_file($file)){
		while(is_file($file)){
			$array = parseSections($file);
			storeSections($array);
			$j++;
			$file = $folder.$i.'_'.$j.'.txt';
		}
		$i++;
		$j = 0;
		$file = $folder.$i.'_'.$j.'.txt';
	}
	var_dump($MSERROR->messages);
}

reloadSubjectsDatabaseFromFile();
reloadCoursesDatabaseFromFiles();
reloadSectionsDatabaseFromFiles();
