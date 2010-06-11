<?php
// configuration stuff
ini_set("include_path", $_SERVER['DOCUMENT_ROOT'] . '/mschedule/update/php/');
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010_open.csv";
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
$prev_days = '';
$prev_seats = 0;
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

	$instructor = $fields[19];
	$totalseats = $fields[20];
	$seats = $fields[21];
	$section = $fields[6];
	$sectype = $fields[8];
	#not in csv 
	$waitlist = 0;
	$credits = 0;

	#make the fields sql-friendly
	$name = preg_replace("/'/", '\\', $name);
	$instructor = preg_replace("/'/", '\\', $instructor);
	$location = preg_replace("/'/", '\\', $location);
	$num  = preg_replace('/[ "]/', '', $num);
	$classnum  = preg_replace('/[ "]/', '', $classnum);
	$seats  = preg_replace('/[ "]/', '', $seats);
	$section = preg_replace('/[^0-9]/', '', $section);

	if (preg_match('/([^\"]+?) \(([^\"]+?)\)/', $fields[4], $matches))
	{
		//if we have a legit course name/number add it to the db
		if($name != "" && $num != "" && $classnum != "") {
			$linkage = 0;
			if(!$seats)
			{
				$seats = $prev_seats;
				if($seats == '')
				{
					$seats = 0;
				}
			}

			if($sectype == 'LEC')
			{
				$linkage = substr($section,strlen($section)-1);
			}
			if($classnum == 38745) {
				$seats = 100;
			}
			sql("INSERT INTO sections VALUES('$term','$matches[2]','$num','$classnum','$credits',$seats,$waitlist,'$section','$sectype','$instructor',$linkage) ON DUPLICATE KEY UPDATE openSeats=$seats, waitlistNum=$waitlist, instructor='$instructor'");
		}
	}

	$prev_classnum = $classnum;
	$prev_num = $num;
	$prev_starttimeindex = $starttimeindex;
	$prev_endtimeindex = $endtimeindex;
	$prev_seats = $seats;
	echo $classnum . "<br>";
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
