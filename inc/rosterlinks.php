<?php
//returs html code to link up the roster.php page with other rosters 
function rosterlinks($dept = '', $number = '', $section = ''){
	$html = '';
	if($dept){
		if($number){
			$html .= " <a href=\"roster.php?dept=$dept\">".$dept."</a>";
		}else{
			$html .= ' '.$dept;
		}
	}
	if($number){
		if($section){
			$html .= " <a href=\"roster.php?dept=$dept&number=$number\">".$number."</a>";
		}else{
			$html .= ' '.$number;
		}
	}
	if($section){
		$html .= " (".$section.")";
	}
	return $html;
}
?>