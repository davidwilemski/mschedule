<?php
//modified slightly by Kyle Mulka (mulka@umich.edu)
/*
	-------------------------------------------------------------------------------------
	schedule.inc.php
	-------------------------------------------------------------------------------------
	Author: Joe Huntley
	E-mail: PlayAirGuitar@aol.com
	-------------------------------------------------------------------------------------
	This code may be modified and used in personal and commercial applications. If 
	this code is modified and/or posted on any web site by another party otehr than the 
	author. The party shall keep this header intact and give property credit to the author
	where due. Thanks. Happy coding.
	-------------------------------------------------------------------------------------
*/



/*
	-------------------------------------------------------------------------------------
	schedule_generate():
	
		Generates a schedule in HTML based on $classes_arr
	-------------------------------------------------------------------------------------
	$classes_arr - An array that describes the classes shown
	               in the schedule. See below for more info.
	$options - An associative array containing the options on
			   how to display the schedule. See below for info
	-------------------------------------------------------------------------------------
	$classes_arr is an array that takes the following format:
	
	$classes_arr[<day>][<time>][<html|style|interval>];
	
	<day> (int) - the zero-based weekday of the corresponding day
	        of the class.
	<time> (int) - the numeric military time of the class
	[<html|style|interval>] - properties of the class cell.
	        html (string) - the html content of the class cell
			style (string) - the CSS style of the cell
			interval (int) - the time interval of the class in minutes.
					   ie. if the class is 1hr long, use 60. the
					   default is 60. Note that this value
					   must a multiple of row_interval or it will
					   be rounded to the nearest interval
	-------------------------------------------------------------------------------------
	$options is an array that takes the following format:
	
	$options[<title|start_time|end_time|days|time_style|dayheader_style|title_style|class_globalstyle>];
	
	title (string) - the title to display on top
	start_time (int) - the time of day where the schedule begins
	              in numeric military time (ie. 800 is 8am)
	end_time (int) - the time of day where the schedule ends
	           (in numeric military time (ie. 2200 is 10pm)
	time_style (string) - A CSS style string to format the time cells on
	             the left side of the schedule.
	dayheader_style (string) - A CSS style string to format the day names cells
	                   on the top of the schedule
	title_style (string) - A CSS style string to format the schedule title cell
	class_globalstyle (string) - A CSS style string to apply to all class cells 
	------------------------------------------------------------------------------------- 
*/
function schedule_generate($classes_arr, $options) {

		// if no row interval set or is zero, use 30mins
		if ( intval($options["row_interval"]) == 0 ) $options["row_interval"] = 30;	

		// define start time as 8am if not set
		if ( ! isset($options["start_time"]) ) {
			$options["start_time"] = 800;
		}else {
			// change to the nearest row interval hour down.
			$time_hour = ($options["start_time"] - $options["start_time"] % 100) / 100; 
			$time_min = $options["start_time"] % 100;
			
			$time_totalmins = $time_hour * 60 + $time_min;
			
			if ( $time_totalmins % $options["row_interval"] > 0) $time_totalmins = $time_totalmins - $time_totalmins % $options["row_interval"];
			$options["start_time"] = ($time_totalmins - $time_totalmins % 60) / 60 * 100 + $time_totalmins % 60;
		
		}
		
		
		// define end time as 10pm if not set
		if ( ! isset($options["end_time"]) ) {
			$options["end_time"] = 2200;
		}
		else {
			// change to the nearest row interval hour down.
			$time_hour = ($options["end_time"] - $options["end_time"] % 100) / 100; 
			$time_min = $options["end_time"] % 100;
			
			$time_totalmins = $time_hour * 60 + $time_min;
			
			if ( $time_totalmins % $options["row_interval"] > 0) $time_totalmins = $time_totalmins - $time_totalmins % $options["row_interval"];
			$options["end_time"] = ($time_totalmins - $time_totalmins % 60) / 60 * 100 + $time_totalmins % 60;
		
		}

		$days_arr = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		
		$days_norow = array(0, 0, 0, 0, 0, 0, 0);
		
		$html = "<table border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
		
		// output title if set in $options.
		if ( isset($options["title"]) ) {
			$cell_style = "background-color: #000000; color: #ffffff;"; // default title style 
 			$cell_style .= $options["title_style"];
			
			$html .= "	<tr>\n		<th colspan=\"8\" style=\"$cell_style\">".$options["title"]."</th>\n	</tr>\n";
		}
		
		$cell_style = "background-color: #c0c0c0; color: #000000;"; // default day header style
		$cell_style .= $options["dayheader_style"];
		
		$html .= "	<tr style=\"$cell_style\">\n		<th>&nbsp;</th>\n";
		/*
		foreach ($days_arr as $day){
			$html .= "		<th>$day</th>\n";
		}
		*/
		for($i = 1; $i < 6; $i++){
			$html .= "		<th>{$days_arr[$i]}</th>\n";
		}
		
		$html .= "	</tr>\n";
		
		$cur_time = $options["start_time"];
		while ($cur_time < $options["end_time"]) {
			$format_time = date("g:ia", strtotime(substr($cur_time, 0, strlen($cur_time) - 2).":".substr($cur_time, -2, 2)));
			
			$cell_style = "background-color: #c0c0c0; color: #000000;"; // default time cell style
			$cell_style .= $options["time_style"];			
				
			$html .= "	<tr bgcolor=\"#ffffff\">\n		<td align=\"right\" width=\"2%\" style=\"$cell_style\"><b>$format_time</b></td>\n";
			
			//for ($cur_day = 0; $cur_day < 7; $cur_day++) {
			for ($cur_day = 1; $cur_day < 6; $cur_day++) {
				// if flag is set not to print any row for the next
				// row (since a class spans more than one row), then
				// continue.
				if ($days_norow[$cur_day] > 0) {
					$days_norow[$cur_day]--;
					continue;
				}
			
			
				 // check if there is a class during this day/time
				if ( isset($classes_arr[$cur_day][$cur_time]) ) {
					
						$class_interval = intval($classes_arr[$cur_day][$cur_time]["interval"]);
						if ( $class_interval == 0 ) $class_interval = 60; // default interval is 60mins
						
						// round to nearest interval
						$class_span = intval($class_interval / $options["row_interval"]);
						
						// flag that for the next $class_span rows, we should not print a cell
						$days_norow[$cur_day] += $class_span - 1;	
					
					    if ( isset($classes_arr[$cur_day][$cur_time]["style"]) )
							$cell_style = $options["class_globalstyle"]."; ".$classes_arr[$cur_day][$cur_time]["style"];
						else
							$cell_style = $options["class_globalstyle"];
						
						$html .= "		<td width=\"20%\" rowspan=\"$class_span\" style=\"$cell_style\">".$classes_arr[$cur_day][$cur_time]["html"]."</td>\n";
						
				}		
				else {
						//$html .= "		<td width=\"17%\">&nbsp;</td>\n";
						//var_dump($days_norow);
						//echo '<p>';
						if ( isset($options["blank_cell_style"]) )
							$cell_style = $options["class_globalstyle"]."; ".$options["blank_cell_style"];
						else
							$cell_style = $options["class_globalstyle"];
						
						$html .= "		<td style=\"$cell_style\">&nbsp;</td>\n";
				
				}
				
				
			}
		
			
			$html .= "	</tr>\n";
			
			$cur_time += $options["row_interval"]; // increment to next row interval
			if ($cur_time % 100 >= 60) $cur_time = $cur_time - $cur_time % 100 + 100;
		};
				
		
		
		$html .= "</table>\n";
		
		
		return $html;	
	
}

?>
