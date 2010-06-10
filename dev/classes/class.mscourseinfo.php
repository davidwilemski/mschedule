<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.msdbcn.php";
$MSBD = new MSDbCn();
require_once $cfg['ms_rootpath']['server']."/classes/class.mscourse.php";


class MSCourseInfo
{

	//returns an array of MSCourse matching the given 
	function search($courseID, $subject = '', $number = '', $section = '')
	{
		global $MSDB, $cfg;
		if($subject != '' or $courseID != ''){
			$where_statment = '1';
			if($courseID != ''){
				$where_statment .= " AND `courseID` = '$courseID'";
			}
			if($subject != ''){
				$where_statment .= " AND `subject` = '$subject'";
			}
			if($number != ''){
				$where_statment .= " AND `number` = '$number'";
			}
			if($section != ''){
				$where_statment .= " AND `section` = '$section'";
			}
			
			$result = $MSDB->sql("select * from `{$cfg['db']['tables']['wa_sections']}` where ".$where_statment." order by subject, number, section");
		}
		/*
		else{
			echo "<b>My Schedule</b>";
			$result = sql("SELECT t2.* "
		        . " FROM `$user_class` AS t1, `$classes` AS t2 "
		        . " WHERE t1.uniqname = '$auth_uniqname' AND "
		        . " t1.classid = t2.classid"
		        . " ORDER BY t2.`dept`, t2.`number`, t2.`section`");
		}
		echo "<table border=1>";
		for($i = 1; $myrow = mysql_fetch_row($result); $i++){
			echo "<tr>";
			//debug($i);
			foreach($myrow as $value){
				echo "<td>$value</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		*/
		$rv = array();
		while($row = mysql_fetch_assoc($result)){
			array_push($rv, new MSCourse($row));
		}
		
		return $rv;
		
	}
	
	
}
?>