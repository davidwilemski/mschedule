<?
$tST = time();
require_once "class.wascrapercontrol.php";
require_once "class.wascraper.php";

$folder = "C:\\Documents and Settings\\Kyle\\Desktop\\watest\\";
$subFolder = "/temp/";
$tempFolder = $_SERVER["DOCUMENT_ROOT"].$subFolder;

header("Content-type: text/plain");

//set cmd
if(isset($_GET['cmd'])){
	$cmd = $_GET['cmd'];
}else{
	print "cmd: go\ndebug: 1 or 0\ntimeLimit: max exec time\nchunks: number of chunks (defaults to number of subjects)";
	exit;
}


//set debug level
if(isset($_GET['debug'])){
	$debug = $_GET['debug'];
}else{
	$debug = 0;
}

//set timeLimit
if(isset($_GET['timeLimit'])){
	ini_set('max_execution_time', $_GET['timeLimit']);
}

//set chunks
if(isset($_GET['chunks'])){
	$chunks = $_GET['chunks'];
}else{
	$chunks = WAScraperControl::getNumberOfSubjects();
}

//takes out all the subjects that we have all the files from
function resumeTransfer($array)
{
	global $folder;
	$rv = array();
	$files = array();
	
	//reads files in folder and counts how many $i_*.txt files we have
	//$files[$i] is how many $i_*.txt files we have in folder
	$handle = opendir($folder."pages");
	$filesST = time();
	while (false !== ($file = readdir($handle))) {
		list($i, $j) = sscanf($file, "%d_%d.txt");
		if(isset($j)){
			$files[$i] = $files[$i] + 1;
		}
	}
	printf("file check time: %d\n", time() - $filesST);
   
	$rv = array_diff_assoc($array, $files);

	if($GLOBALS['debug']){
		//print "Files:\n";
		//var_dump($files);
	}
	/* the old (slow) way of doing it
	$outerStartTime = time();
	foreach($array as $i => $value){
		$startTime = time();
		if($GLOBALS['debug']){
			print "checking\n";
		}
		for($j=0; $j < $value; $j++){
			if($GLOBALS['debug']){
				print $i."_".$j."\n";
			}
			if(!in_array("$i_$j.txt", $files)){
				$rv[$i] = $array[$i];
			}
		}
		$endTime = time();
		printf("time inner %d: %d\n", $i, $endTime - $startTime);
	}
	$outerEndTime = time();
	printf("time outer: %d\n", $outerEndTime - $outerStartTime);
	*/
	return $rv;
}


//header("Content-type: text/plain");


$c = new WAScraperControl();
$param = $c->getNumbersOfCourses();
if($debug){
	print "param before resume:\n";
	var_dump($param);
}
$param = resumeTransfer($param);
if($debug){
	print "param after resume:\n";
	var_dump($param);
}
if(is_array($param) and count($param) > 0){
	$params = array_chunk($param, ceil(count($param)/$chunks), true);
}else{
	print "nothing to do<br>";
	$params = array();
}
if($debug){
	print "<pre>";
	var_dump($params);
	exit;
}

$rlST = time();
foreach($params as $thisChunk => $param){
	$s = new WAScraper($tempFolder."script.wa.sections.".$thisChunk.".txt");
	$s->refreshListOfSections($param);
}
//printf("refresh list time: %d\n", time() - $rlST);

$zST = time();
chdir($tempFolder);
$fp=fopen($tempFolder."script.wa.all.sh", 'wb');
foreach($params as $thisChunk => $value){
	fwrite($fp, "lynx -accept_all_cookies -cmd_script=script.wa.sections.$thisChunk.txt &\n");
}


exec("C:\PACL\PACOMP.EXE -u scripts.zip script.wa.*");
//printf("zip time: %d\n", time() - $zST);
unlink("script.wa.all.sh");
foreach($params as $thisChunk => $value){
	unlink("script.wa.sections.$thisChunk.txt");
}
//printf("total time: %d\n", time() - $tST);
//header("Content-type: application/zip");
header("Location: ".$subFolder."scripts.zip");
//readfile("scripts.zip");