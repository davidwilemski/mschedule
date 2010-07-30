<p>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<input type="password" name="password" size="10" /><br>
<!--<input type="text" name="command" size="20" /><br>-->
<input type="submit" value="submit" />
</form>
</p>
<?

// database info, defines dbConnect()
include 'inc/db.php';
include 'inc/spider_functions.php';
include 'inc/spider_vars.php';

//vars from user
$command = $_POST['command'];
$password = $_POST['password'];


if($password != $correct_pwd){
	exit;
}

//echo "<p>Command: ".$command."</p>";

$do_all = false;
if($command == 'all'){
	$do_all = true;
}

// get schools and relative urls
//if($command == 'schools' or $do_all){
	
	//open url as a file
	$file = openURL($start_url);
	
	dbConnect();
	
	
	//"freshen" the database for new information
	$result = sql('SHOW TABLE STATUS LIKE \'schools\'');
	$myrow = mysql_fetch_assoc($result);
	$old_comment = $myrow['Comment'];
	sql('DROP TABLE `schools`');
	sql('CREATE TABLE IF NOT EXISTS `schools` ( `school` VARCHAR( 100 ) NOT NULL ,'
	        . ' `school_rel_url` VARCHAR( 100 ) NOT NULL );'
	        . ' ');
	
	
	
	//look through each line
	while (!feof ($file)) {
		$line = fgets ($file, 1024);
		if(stristr($line, "<H2 ALIGN = center>")){
			$new_comment = strip_tags($line);
			$result = sql('SHOW TABLE STATUS LIKE \'schools\'');
			$myrow = mysql_fetch_assoc($result);
			if($old_comment == $new_comment){
				echo "Table up to date. Done";
				exit;
			}
			sql("alter table `schools` comment = '$new_comment'");
		}
		
		//if line contains the link we want, parse the school name and relative url
		if(($string = stristr($line, "<a href =")) and !stristr($line, "search_")){
			$array = explode("\"", $string);
			$school_rel_url = str_replace("index.html", "", $array[1]);
			$school = html_entity_decode(chop(strip_tags($line)));
			
			sql('INSERT INTO `schools` ( `school` , `school_rel_url` ) '
	        . ' VALUES ( \''.$school.'\', \''.$school_rel_url.'\' );'
	        . ' ');
		    
		    //debug? maybe I'll leave it in just for kicks
			echo $school, " ->  ", $school_rel_url, "<br>\n";
		}
	}//while
	fclose($file);
	
//}//if command=schools



//get department codes, full names, and relative urls
//if($command == 'divisions' or $do_all){
	dbConnect();
	
	//load schools already spidered with command: schools
	$result = sql('SELECT * FROM `schools`');
	
	
	
	sql('CREATE TABLE IF NOT EXISTS `divisions` ('
	  		.'`dept` varchar(8) NOT NULL,'
	  		.'`dept_name` varchar(60) NOT NULL,'
	  		.'`school` varchar(40) NOT NULL,'
	  		.'`dept_rel_url` varchar(200) NOT NULL'
			.')');
	
	//load info for each school
	while($myrow = mysql_fetch_row($result)){
		$school = $myrow[0];
		$school_rel_url = $myrow[1];
		
		//debug?
		starting_msg($school, $school_rel_url);
	
	
		$file = openURL($start_url.$school_rel_url);
		
		
		//delete all divisions in school from database
		sql('DELETE FROM `divisions` WHERE `school` = \''.$school.'\';'
		        . ' ');
		
		
			//debug
			//$count = 0;
		//read page for each school to get departments
		while (!feof ($file)) {
			$line = fgets ($file, 1024);
			
		
			
			if(($string = stristr($line, '<a href ='))){
				
				//debug
				/*
				echo $string;
				$count++;
				if($count == 10){
					exit;
				}
				*/
				
				
				$array = explode("\"", $string);
				$dept_rel_url = $school_rel_url.$array[1];
				$array = explode("(", html_entity_decode(strip_tags($line)));
				$dept_name = chop($array[0]);
				$dept = chop(chop($array[1]), ")");
				
				
		 		sql('INSERT INTO `divisions` ( `dept` , `dept_name` , `school` , `dept_rel_url` ) '
		        		. ' VALUES ( \''.$dept.'\', \''.$dept_name.'\', \''.$school.'\', \''.$dept_rel_url.'\' );'
		        		. ' ');
		
			    	    
			    //debug
				data_msg(array($dept, $dept_name, $school, $dept_rel_url));
				//exit;
			}
		}
		fclose($file);
			
		/*
		<A HREF =" 
		*/
	}//while
//}//if command==divisions


//this does sections as well since they are right below the course
//if($command == 'courses' or $do_all){

	dbConnect();
	
	//load divisions already spidered with command: divisions
	$result = sql('SELECT * FROM `divisions`');
	
	//debug
	//$result = sql('SELECT * FROM `divisions` LIMIT 3');
	
	
 	sql('CREATE TABLE IF NOT EXISTS `courses` ( `dept` VARCHAR( 8 ) NOT NULL ,'
        . ' `number` INT( 3 ) UNSIGNED ZEROFILL DEFAULT \'000\'  NOT NULL ,'
        . ' `course_name` VARCHAR( 100 ) NOT NULL ,'
        . ' `credit` VARCHAR( 15 ) NOT NULL ,'
        . ' `prereq` VARCHAR( 100 ) NOT NULL ,'
        . ' `lab_fee` VARCHAR( 15 ) NOT NULL );'
        . ' ');
        
        
	 sql('CREATE TABLE IF NOT EXISTS `sections` ( `classid` INT( 8 ) NOT NULL ,'
        . ' `dept` VARCHAR( 8 ) NOT NULL ,'
        . ' `number` INT( 3 ) UNSIGNED ZEROFILL DEFAULT \'000\' NOT NULL ,'
        . ' `section` INT( 3 ) UNSIGNED ZEROFILL DEFAULT \'000\' NOT NULL ,'
        . ' `code` VARCHAR( 4 ) NOT NULL ,'
        . ' `type` VARCHAR( 3 ) NOT NULL ,'
        . ' `days` VARCHAR( 7 ) NOT NULL ,'
        . ' `time` VARCHAR( 13 ) NOT NULL ,'
        . ' `location` VARCHAR( 15 ) NOT NULL ,'
        . ' `instructor` VARCHAR( 50 ) NOT NULL );'
        . ' ');
	
	//load info for each division (dept)
	while($myrow = mysql_fetch_row($result)){
		$dept = $myrow[0];
		$dept_rel_url = $myrow[3];
	
		//debug? 
		starting_msg($dept, $dept_rel_url);
		
		$file = openURL($start_url.$dept_rel_url);
		
		//delete stuff in database here
		sql('DELETE FROM `courses` WHERE `dept` = \''.$dept.'\';'
        . ' ');
        sql('DELETE FROM `sections` WHERE `dept` = \''.$dept.'\';'
        . ' ');
		
		$temp = 0; //used to skip first one which is the key
		while (!feof($file)) {
			
			$extra_flag = false;
			
			$line = html_entity_decode(fgets ($file, 1024));
			
			if(($string = stristr($line, '<b>')) and $temp++){//gets all after the first one which is the key
				
				$array = array(); //reset
				$array = explode("\t", strip_tags($string));
				
				
				
				$count = 0;
				$newarray = array(); //reset
				foreach($array as $value){
					if(trim($value) != ''){
						$newarray[$count++] = trim($value);
					}
				}
				
				$count = 0;
				$course_name = $newarray[$count++];
				$number = $newarray[$count++];
				$credit = $newarray[$count++];
				if(!preg_match("/\d\.\d\d/", $newarray[$count])){
					$prereq = $newarray[$count++];
				}
				$lab_fee = $newarray[$count++];
				
				//debug
				data_msg(array($course_name, $number, $credit, $prereq, $lab_fee));
				
				
				
				//debug
				/*
				if($prereq == '' and $lab_fee != ''){
					echo "<strong>look at me!</strong>";
				}
				*/
				
				//to accomodate weird characters in the name of course
				$course_name = addslashes($course_name);
				
				sql('INSERT INTO `courses` ( `dept` , `number` , `course_name` , `credit` , `prereq` , `lab_fee` ) '
        			. ' VALUES ( \''.$dept.'\', \''.$number.'\', \''.$course_name.'\', \''.$credit.'\', \''.$prereq.'\', \''.$lab_fee.'\' );'
        			. ' ');
				
				//I put a lot of work into this, but found a better way above
				/*
				$line = strip_tags($string);
				$array = preg_split("/\d/", $line);
				//data_msg($array);
				$course_name = trim($array[0]);
				preg_match("/[0-9][0-9][0-9]/", $line, $array);
				$number = $array[0];
				preg_match("/(\d\.\d\d\s)|(\d\.\d\d\-\d\.\d\d)/", $line, $array);
				$credit = $array[0];
				data_msg(array($course_name, $number, $credit));
				*/
			}else if(preg_match("/^\d{5,6}\s/", $line)){
				
				$array = array();
				$array = explode("\t", $line);
				data_msg($array);
				
				$count = 0;
				$newarray = array(); //reset
				foreach($array as $value){
					if(trim($value) != ''){
						$newarray[$count++] = trim($value);
					}
				}
				
				$count = 0;
				$classid = $newarray[$count++];
				$code = $newarray[$count++];
				$type = $newarray[$count++];
				$section = $newarray[$count++];
				$days = $newarray[$count++];
				$time = $newarray[$count++];
				$location = $newarray[$count++];
				$instructor = $newarray[$count++];
				
				//to accomodate weird characters for sql statment
				$location = addslashes($location);
				$instructor = addslashes($instructor);
				
				
				sql('INSERT INTO `sections` ( `classid` , `dept` , `number` , `section` , `code` , `type` , `days` , `time` , `location` , `instructor` ) '
        			. ' VALUES ( \''.$classid.'\', \''.$dept.'\', \''.$number.'\', \''.$section.'\', \''.$code.'\', \''.$type.'\', \''.$days.'\', \''.$time.'\', \''.$location.'\', \''.$instructor.'\' );'
        			. ' ');
        			
        		data_msg(array($classid, $dept, $number, $section, $code, $type, $days, $time, $location, $instructor));
        		
				
				
			}else{
				
				if(stristr($line, "search")){
					$extra_flag = false;
				}
				/*
				if($extra_flag){
					echo $line;
				}
				*/
				if(preg_match("/\-{50,50}/", $line)){
					$extra_flag = true;
				}
				//echo $line;
			}
		}
	}//while

//}//if command=courses
echo "Done";


?>