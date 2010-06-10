<?php

function parseTime($time){
	$start = array();
	$end = array();

	preg_match('/^(\d?\d)(\d\d)?-(\d?\d)(\d\d)?(AM|PM)$/', $time, $matches);
	$start['hour'] = $matches[1];
	$start['minute'] = $matches[2];
	$end['hour'] = $matches[3];
	$end['minute'] = $matches[4];
	if(!$start['minute']) $start['minute'] = "00";
	if(!$end['minute']) $end['minute'] = "00";
	if($matches[5] == "PM" && $end['hour'] != 12){
	        if($start['hour'] <= $end['hour']) $start['hour'] += 12;
	        $end['hour'] += 12;
	}
	/*
	if($end['minute'] < $start['minute']){
	        $end['hour']--;
	        $end['minute'] += 60;
	}
	*/
	return array('start' => $start, 'end' => $end);
}
