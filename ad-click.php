<?php

require_once 'inc/common.php';
require_once 'inc/func.recordAdData.php';

$id = $_GET['id'];

if($id == 'memcatch'){
	recordAdData('click');
	header('Location: http://www.memcatch.com/content/umstudents?a=132&k=mschedulenov09');
}
