<?php

function recordAdData($action){
	global $metrics;
	$data = array(
		'session' => session_id(),
		'ip' => $_SERVER['REMOTE_ADDR'],
		'user_agent' => $_SERVER['HTTP_USER_AGENT'],
		'timestamp' => time(),
		'action' => $action,
	);
	file_put_contents('/var/www/mschedule/prod/output/ads.log', json_encode($data)."\n", FILE_APPEND);

	$steps = array('serve' => 1, 'click' => 2);
	//$metrics->track($action);
	//$metrics->track_funnel('main', $steps[$action], $action);
}
