<?php
// configuration stuff
ini_set("include_path", $_SERVER['DOCUMENT_ROOT'] . '/mschedule/update/php/');
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010.csv";
$term = "f10";
$mischedule = true;
include_once 'inc/db.php';
// Grab file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $courses);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
$cvs = curl_exec($ch);
curl_close($ch);
$classes = preg_split('/\n/', $cvs);
// Remove description line
unset($classes[0]);
// Loop through classes
$prev_classnum = '';
$prev_num = '';
$prev_starttimeindex = '';
$prev_endtimeindex = '';
$prev_days = array();
foreach($classes as $class) {
	if($class == null) continue;
	$fields = preg_split('/","/', $class);
	foreach($fields as $key => $field) {
		$fields[$key] = str_replace("'", "", $field);
		$fields[$key] = str_replace('"', "", $field);
	}
	$starttimeindex=0;
	$endtimeindex=0;
	$num = $fields[5];
	$name = $fields[7];
	$classnum = $fields[3];
	$location = $fields[18];
	$time = $fields[17];
	$mon = $fields[10];
	$tue = $fields[11];
	$wed = $fields[12];
	$thu = $fields[13];
	$fri = $fields[14];
	$sat = $fields[15];
	$sun = $fields[16];

	//fix case
	$thu = preg_replace('/TH/', 'Th', $thu);
	$sun =~ preg_replace('/SU/', 'Su', $sun);
	
	$days = $mon.$tue.$wed.$thu.$fri.$sat.$sun;

	//reformat time
	$timeArray = reformat_time($time); // ($time, $starttimeindex, $endtimeindex) = reformat_time($time);
	$time = $timeArray[0];
	$starttimeindex = $timeArray[1];
	$endtimeindex = $timeArray[2];

	//make the fields sql-friendly
	$name = preg_replace("/'/", "\\'", $name);
	$location = preg_replace("/'/", "\\'", $location);
	$num  = preg_replace('/[ "]/', '', $num);
	$classnum  = preg_replace('/[ "]/', '', $classnum);
	
	$instructor = $fields[19];
        $section = $fields[6];
        $section = preg_replace('/[^0-9]/', '', $section);
        $sectype = $fields[8];
        $instructor = preg_replace("/'/", "\\'", $instructor);

	if (preg_match('/([^\"]+?) \(([^\"]+?)\)/', $fields[4], $matches ))
	{
		//put the divisions in a hash container to insert after
		$divisions[$matches[2]] = $matches[1];
		
		//if we have a legit course name/number add it to the db
		if($name != "" && $num != "")
		{
			//if it's an extension of the previous class
			if($classnum == '' &&  $num == $prev_num)
			{
				$classnum = $prev_classnum;
			}


			sql("INSERT IGNORE INTO courses VALUES('$term','$matches[2]','$num','$name')");
			sql("INSERT INTO locations VALUES('$term','$matches[2]','$num','$classnum','$days $time','$location') ON DUPLICATE KEY UPDATE location='$location'");
			if($section)
			{
				$linkage = 0;
				if($sectype == 'LEC')
				{
					$linkage = substr($section,strlen($section)-1);
				}	
				sql("INSERT IGNORE INTO sections VALUES('$term','$matches[2]','$num','$classnum','0',0,0,'$section','$sectype','$instructor',$linkage)");
			}

			if(($starttimeindex && $endtimeindex) && $starttimeindex != $endtimeindex)
			{
				$stindex;
				$etindex;
				#we recalculate the time index depending on the day
				if($mon != "")
				{
					$stindex = $starttimeindex + 48*0;
					$etindex = $endtimeindex + 48*0;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($tue != "")
				{
					$stindex = $starttimeindex + 48*1;
					$etindex =  $endtimeindex + 48*1;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($wed != "")
				{
					$stindex = $starttimeindex + 48*2;
					$etindex =  $endtimeindex + 48*2;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($thu != "")
				{
					$stindex = $starttimeindex + 48*3;
					$etindex =  $endtimeindex + 48*3;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($fri != "")
				{
					$stindex = $starttimeindex + 48*4;
					$etindex =  $endtimeindex + 48*4;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($sat != "")
				{
					$stindex = $starttimeindex + 48*5;
					$etindex =  $endtimeindex + 48*5;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
				if($sun != "")
				{
					$stindex = $starttimeindex + 48*6;
					$etindex =  $endtimeindex + 48*6;
					sql("INSERT IGNORE INTO meetings VALUES('$term','$matches[2]','$num','$classnum','$stindex', '$etindex', 'Central')");
				}
			}
		}
	}
	$prev_classnum = $classnum;
	$prev_num = $num;
	$prev_starttimeindex = $starttimeindex;
	$prev_endtimeindex = $endtimeindex;
	$prev_days = array($mon, $tue, $wed, $thu, $fri, $sat, $sun);
	echo $classnum . "\n";
}

// iterate the container and put each division into the database 
foreach ( $divisions as $abbr => $desc)
{
	sql("INSERT IGNORE INTO divisions VALUES('$term','$desc','$abbr')");
}

// FIN
exit;

/* FUNCTIONS */
function reformat_time($time) {
	substr($time[0], 1);
	$stindex;
	$etindex;
	$th1='';
	$th2='';
	$tm1='';
	$tm2='';
	$ampm1=''; 

	if(preg_match('/([0-9]+?)-([0-9]+?)(AM|PM)/', $time, $matches))
	{
		$t1 = $matches[1];
		$t2 = $matches[2];
		$ampm2 = $matches[3];
	
		if($t1 != "" && $t2 != "" and $ampm2 != "")
		{
			if(strlen($t1) < 3)
			{
				$th1 = $t1;
				$tm1 = '00';
			}
			else if(strlen($t1) == 3)
			{
				$th1 = substr($t1,0,1);
				$tm1 =  substr($t1,1,2);
			}
			else 
			{
				$th1 = substr($t1,0,2);
				$tm1 = substr($t1,2,2);
			}

			if(strlen($t2) < 3)
			{
				$th2 = $t2;
				$tm2 = '00';
			}
			else if(strlen($t2) == 3)
			{
				$th2 = substr($t2,0,1);
				$tm2 = substr($t2,1,2);	
			}
			else 
			{
				$th2 = substr($t2,0,2);
				$tm2 = substr($t2,2,2);
			}

			if($ampm2 == "PM" && (($th1 > $th2 && $th1 < 12) || ($th2 == 12 && $th1 < 12)))
			{
				$ampm1 = "AM";
			}
			else
			{
				$ampm1 = $ampm2;	
			}
		}
	}
	if($th1 != '')
	{
		
		//make the mischedule time indexes
		if($th1 == 12)
		{
			$stindex = 0;
		}
		else
		{
			$stindex = $th1*2;
		}
		if($ampm1 == 'PM')
		{
			$stindex += 12*2;
		}

		if($tm1 >= 30)
		{
			$stindex++;
		}

		if($th2 == 12)
		{
			$etindex = 0;
		}
		else
		{
			$etindex = $th2*2;
		}
		if($ampm2 == 'PM')
		{
			$etindex += 12*2;
		}

		if($tm2 >= 30)
		{
			$etindex++;
		}

		return array("$th1:$tm1$ampm1-$th2:$tm2$ampm2", $stindex, $etindex);

	}
	else
	{
		$time = $time;
		$etindex = 0;
		$stindex = 0;
	}
}
