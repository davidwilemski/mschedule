<?php

require_once "XML/Unserializer.php";

$cache = array();

function parseLocation($location){
	global $cache;

	if(isset($cache[$location])){
		return $cache[$location];
	}

	$data = file_get_contents('http://cartiki.com/api/getLocationCentersOnMap.php?source=mschedule&key='.urlencode($location));

	// Instantiate the serializer
	$Unserializer = &new XML_Unserializer();

	// Serialize the data structure
	$status = $Unserializer->unserialize($data);

	// Check whether serialization worked
	if (PEAR::isError($status)) {
		return NULL;
	}

	$result = $Unserializer->getUnserializedData();

	$cache[$location] = $result['location'];

	return $result['location'];
}
