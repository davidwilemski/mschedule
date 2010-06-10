<?
require_once "class.lynxcmdscriptgenerator.php";

define(URL_SCHEDULE, "https://wolverineaccess.umich.edu/servlets/iclientservlet/heprodnonop/?ICType=Panel&Menu=M_SR_CRSECAT_HOME&Market=GBL&PanelGroupName=M_SR_SS_CLSRC_CATG");
define(DEBUG, false);

class WAScraper
{
	var $lynx;
	var $stateNum;
	var $folder;
	var $term;
	var $messages;
	
	
	//filename - the file name where the lynx script will be outputted
	function WAScraper($filename, $folder = '.', $term = 'Winter+2005')
	{
		$this->lynx = new LynxCmdScriptGenerator($filename);
		$this->folder = $folder;
		$this->term = $term;
		$this->messages = array();
	}
	
	//called within the class to give info about what the scraper is doing
	function msg($string)
	{
		array_push($this->messages, $string);
	}
	
	//saves a web page in lynx 
	function savePage($filename)
	{
		//$this->msg("Saving page as ".$filename);
		$this->lynx->savePageToFile($filename);
	}
	
	//cleans up, puts in the quit signal, and prints the script
	function done()
	{
		//header('Content-type: text/plain');
		$this->lynx->quit();
		
		//$this->msg("Done");
		
		if(DEBUG){
			foreach($this->messages as $message){
				print "#$message\n";
			}
		}
		
		$this->lynx->printScript();	
	}
	
	
	function startCourseCatalogSession()
	{
		//$this->msg("Click UM Course Catalog");
		$this->lynx->goURL("https://wolverineaccess.umich.edu/servlets/iclientservlet/heprodnonop/?cmd=start&authType=2");
		$this->stateNum = 0;
	}
	
	function scheduleOfClasses()
	{
		//$this->msg("View Schedule Of Classes");
		$this->lynx->goURL(URL_SCHEDULE);
	}
	
	//just a short cut to get right to the Schedule Of Classes Search Page
	function directToSearch()
	{
		$this->startCourseCatalogSession();
		$this->scheduleOfClasses();
		$this->scheduleOfClassesSearch();
	}
	
	/*
	Submit a query to the Schedule Of Classes Search panel
	
	params:
		subject
		number
		checkbox - true if checkbox should be checked
	
	*/
	function scheduleOfClassesSearch($subject = '', $number = '', $openOnly = false)
	{		
		if($openOnly)
		{
			$checkbox = 'C';
		}else{
			$checkbox = 'O';
		}
		
		$this->msg("Subject Code: $subject , Catalog Number: $number , Open Sections Only? $checkbox");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_BTN_FIND';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1&CLASS_SRCH_WRK2_DESCR1='.$this->term.'&M_SR_SS_BP_DISP_SUBJECT='.$subject.'&M_SR_SS_BP_DISP_DESCR1=&M_SR_SS_BP_DISP_CATALOG_NBR='.$number.'&M_SR_SS_BP_DISP_ENRL_STAT$chk='.$checkbox.'&M_SR_SS_BP_DISP_ENRL_STAT='.$checkbox.'&CLASS_SRCH_WRK2_CLASS_SRCH_CRIT$0=');
	}
	
	//select a subject from the listing of subjects - $i is its index in the list
	function selectSubject($i)
	{
		$this->msg("Selecting Subject #$i");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_SUBJECT$'.$i;
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
	
	//select a courset from the listing of courses - $i is its index in the list
	function selectCourse($i)
	{
		$this->msg("Selecting Course #$i");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_ALL_SUBJECT$'.$i;
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
	
	//select a section from the listing of sections - $i is its index in the list
	function selectSection($i)
	{
		$this->msg("Selecting Section #$i");
		$this->stateNum++;
		$action = 'CLASS_SRCH_WRK2_M_SR_SS_CLASS_NBR$'.$i;
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
		
	//umm... was thinking about implemeneting, haven't yet
	function navigate($from, $to)
	{
		
	}
	
	
	function returnToScheduleOfClassesSearchFromSectionsResults()
	{
		$this->msg("Return");
		$this->stateNum++;
		$action = 'CLASS_SRCH_WRK2_CLOSE_PB';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1&ICFind=');
	}
	
	function returnToScheduleOfClassesSearchFromSubjectResults()
	{
		$this->msg("Return");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_BTN_SWITCH_LEVEL';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
	
	function returnToListOfSubjectsFromListOfCourses()
	{
		$this->msg("Return");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_BTN_RETURN_SEARCH$13$';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
	
	function returnToListOfCoursesFromSections()
	{
		$this->msg("Return");
		$this->stateNum++;
		$action = 'M_SR_SS_BP_WRK_BTN_CLOSE';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1&ICFind=');
	}
	
	function returnToListOfSectionsFromSectionInformation()
	{
		$this->msg("Return");
		$this->stateNum++;
		$action = 'DERIVED_SSE_DSP_CANCEL_PB';
		$this->lynx->goURL(URL_SCHEDULE.'&ICType=Panel&ICElementNum=0&ICStateNum='.$this->stateNum.'&ICAction='.$action.'&ICXPos=0&ICYPos=0&ICFocus=&ICChanged=-1');
	}
	
	
	
	///////////
	//these function are used to go through entire site
	//////////
	
	//saves the list of subjects to a file
	function refreshListOfSubjects()
	{
		$this->directToSearch();
		$this->savePage($this->folder."/subject_list.txt");
		$this->done();

	}
	
	//count = how many subjects there are in the subject list
	function refreshListOfCourses($count)
	{
		$this->directToSearch();
		for($i = 0; $i < $count; $i++){
			$subject = $i;
			$this->selectSubject($i);
			$this->savePage($this->folder."/".$subject.".txt");
			$this->returnToListOfSubjectsFromListOfCourses();
		}
		$this->done();

	}
	
	
	//array of numbers that correspond to number of courses in that subject
	//so, for example, if there were two subjects, foo, and bar, and foo had 3 courses and bar had 5, the array would be:
	//array(1 => 5, 0 => 3); 
	//this is because bar is listed before foo in the listing on WA
	//the subject numbering starts at 0 and counts up, so bar gets 0 and foo gets 1
	function refreshListOfSections($num_array)
	{
		$this->directToSearch();
		if(is_array($num_array)){
			foreach($num_array as $key => $count){
				$subject = $key;
				$this->selectSubject($subject);
				for($i = 0; $i < $count; $i++){
					$number = $i;
					$this->selectCourse($number);
					$this->savePage($this->folder."/".$subject."_".$number.".txt");
					$this->returnToListOfCoursesFromSections();
				}
				$this->returnToListOfSubjectsFromListOfCourses();
			}
		}
		$this->done();
	}
	
	//2 dimensional array (all integer values and keys)
	//first dimension corresponds to subjects (indexed by how they are layed out on WA)
	//second dimension corresponds to courses
	//final value corresponds to number of sections there are for a course
	function refreshSectionInformation($arrayOfArrays)
	{
		$this->directToSearch();
		foreach($arrayOfArrays as $key => $array){
			$subject = $key;
			$this->selectSubject($subject);
			foreach($array as $key2 => $count){
				$number = $key2;
				$this->selectCourse($number);
				for($i = 0; $i < $count; $i++){
					$this->selectSection($i);
					$this->savePage($this->folder."/".$i."_".$number."_".$i.".txt");
					$this->returnToListOfSectionsFromSectionInformation();
				}
				$this->returnToListOfCoursesFromSections();
			}
			$this->returnToListOfSubjectsFromListOfCourses();
		}
		$this->done();
	}
	
	/////////////
	//functions below use search and may not work for all subjects
	/////////////
	
	/*params:
	*	array - array of subjects to refresh
	*/
	function refreshSubjects($array)
	{
		$this->startCourseCatalogSession();
		$this->scheduleOfClasses();
		foreach($array as $subject)
		{
			$this->scheduleOfClassesSearch($subject);
			$this->savePage($this->folder."/".$subject.".txt");
			$this->returnToScheduleOfClassesSearchFromSubjectResults();
		}
		
		$this->done();
	}
		
	
	/*params:
	*	array - associative array like below with all classes to refresh:
	*	$array = array(
	*	0 => array('subject' => 'eecs','number' => 280),
	*	1 => array('subject' => 'eecs','number' => 281)
	*	);
	*	folder - the folder that the saved pages will be put (within AFS if you are running lynx on the UofM login server)
	*	term - term as taken from the combo box in WolverineAccess with +'s in place of spaces
	*
	* prints a command file to use with lynx (text based browser, availible on the UofM login servers)
	* command line:
	*  lynx -accept_all_cookies -cmd_script=<FILENAME_GOES_HERE> 
	*
	* when lynx is run with this file, it will generate text files in the given folder that consist of the pages that WolverineAccess outputed for each course
	* of course, we still need to parse these text files, but the biggest hurdle has been jumped
	*
	*/
	function refreshCourses($array)
	{
		$this->startCourseCatalogSession();
		$this->scheduleOfClasses();
		
		foreach($array as $course)
		{
			$this->scheduleOfClassesSearch($course['subject'], $course['number'], $this->term);
			$this->savePage($this->folder."/".$subject."_".$number.".txt");
			$this->returnToScheduleOfClassesSearchFromSectionsResults();
		}
		
		$this->done();
	}
}

?>