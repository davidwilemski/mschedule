<?
require_once "class.wascrapercontrol.php";
require_once "class.wascraper.php";

//ini_set('max_execution_time', 15);

function chunkArray($array, $totalChunks, $thisChunk)
{
	$newArray = array_chunk($array, ceil(count($array)/$totalChunks), true);
	//print ceil(count($array)/$totalChunks);
	
	//var_dump($newArray);
	return $newArray[$thisChunk - 1];
}

$s = new WAScraper("C:\\Documents and Settings\\Kyle\\Desktop\\script.wa.temp.txt");
$c = new WAScraperControl();

switch($_GET['cmd']){
	case 2:
		$param = $c->getNumberOfSubjects();
		break;
	case 3:
		$param = $c->getNumbersOfCourses();
		break;
	case 4:
		$param = $c->getNumbersOfSections();
		break;
}

//print "<pre>";
//var_dump($_GET);

if(is_numeric($_GET['totalChunks']) and is_numeric($_GET['thisChunk'])){
	switch($_GET['cmd']){
		case '3':
		case '4':
			$param = chunkArray($param, $_GET['totalChunks'], $_GET['thisChunk']);
			break;
		default:
			break;
	}
}



var_dump($param);

switch($_GET['cmd']){
	case 1:
		$s->refreshListOfSubjects();
	break;
	case 2:
		$s->refreshListOfCourses($param);
		break;
	case 3:
		$s->refreshListOfSections($param);
		break;
	case 4:
		$s->refreshSectionInformation($param);
		break;
	default:
		print "no command selected<br>\n";
		print "1-subjects, 2-courses, 3-sections, 4-sectionInfo<br>\n";
		print "totalChunks, thisChunk";
		break;
}


//var_dump($num_subjects);
//var_dump($num_courses);
//var_dump($num_sections);
?>