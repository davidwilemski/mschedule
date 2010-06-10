<?php

require_once "DB.php";

$dsn = "mysql://mschedule:blah@localhost/mschedule";

$db =& DB::connect($dsn);
if(PEAR::isError($db)){
        die($db->getMessage());
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);

