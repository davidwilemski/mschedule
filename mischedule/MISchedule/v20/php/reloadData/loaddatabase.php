<?php
$dbname = "mschedul_misched";
$user = "mschedul_misch";
$pass = "yey4Anew";
$folder = "/home/mschedul/wadata/";
$deleteFile = "mischedule-delete.sql";
$filePrefix = "mischedule.";
$fileSuffix = ".sql";
$tables = array('divisions', 'courses', 'sections', 'locations', 'meetings');
$maxIUser = 15;
$i = 7;


header("Content-type: text/plain");



print "\nloading database...\n";
flush();

	$command = "mysql ".$dbname." -u ".$user.$i." -p".$pass." < ".$folder.$deleteFile;
	print $command."\n";
	flush();
	system($command, $rv);
	if($rv != 0)
		print "delete failed";


$rv = 0;
foreach($tables as $table){
	if($i > $maxIUser){
		exit("ran out of users");
	}
	print "table: $table\n";
	$command = "mysql ".$dbname." -u ".$user.$i." -p".$pass." < ".$folder.$filePrefix.$table.$fileSuffix;
	print $command."\n";
	flush();
	system($command, $rv);
	/*
	while($rv != 0 && $i <= $maxIUser){
		$i++;
		system("mysql ".$dbname." -u ".$user.$i." -p".$pass." -v < ".$folder.$filePrefix.$table.$fileSuffix, $rv);
	}
	*/
	if($rv != 0){
		exit("error: returned $rv");
	}

	$i++;
}

print "done\n";