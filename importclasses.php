<?
include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/db.php';

getdata(array('submit', 'text', 'classids'), 'get');
getdata(array('submit', 'text', 'classids'));

$classids = $_POST['classids'];

if($submit == "importclasses"){

	$array = array();
	$array = preg_split("/\s+/", $text);
	$newarray = array();
	$count = 0;
	foreach($array as $value){
		//echo $value;
		if(preg_match("/\d{5,5}/", $value, $output)){
			$newarray[$count++] = $output[0];
		}
	}

	dbConnect();

	$where_statment = '';
	foreach($newarray as $key => $value){
		$where_statment .= "`classid` = '$value'";
		sql("insert ignore into inserted_classids set classid='$value'");
		if(($key + 1) < count($newarray)){
			$where_statment .= " or ";
		}
	}
	if($where_statment == ''){
		error("No valid class ids found. They need to be 5 digits and they need to have a space between them. Any invalid class numbers will be ignored, including random text, aka other info from Wolverine Access.");
	}
	$result = sql("select * from `$classes` where ".$where_statment);

	showHTMLHead("Import Classes");
	echo "<form method=\"post\" action=\"{$_SERVER['PHP_SELF']}\">";
	echo "<table border=1>";
	
	$foundclassids = array();
	$lastrowcount = 0;
	for($i = 1; $myrow = mysql_fetch_row($result); $i++){
		echo "<tr>";
		debug($i);
		?><td><input type="checkbox" name="classids[<?=$i?>]" value="<?=$myrow[0]?>" checked=checked><td><?
		foreach($myrow as $value){
			echo "<td>$value</td>";
		}	
		echo "</tr>";
		$foundclassids[] = $myrow[0];
		$lastrowcount = count($myrow);
	}
	$notfoundclassids = array_diff($newarray, $foundclassids);
	foreach($notfoundclassids as $notfoundclassid){
		echo "<tr>";
		?><td><input type="checkbox" name="classids[<?=$i?>]" value="<?=$notfoundclassid?>" checked=checked><td><?
		echo "<td>$notfoundclassid</td>";
		for($j = 0; $j < $lastrowcount; $j++){
			echo "<td></td>";
		}
		echo "</tr>";
		$i++;
	}
	
	
	echo "</table><input type=\"submit\" name=\"submit\" value=\"Add Selected Classes\"></form>";

	showHTMLFoot();
	exit;
}else if($submit == "addselectedclasses"){
	/*
	echo "adding classes:";
	$value = '';
	foreach($classids as $value){
		echo $value."<br>\n";
	}
	*/
	dbConnect();
	
	foreach($classids as $classid){
		if(mysql_result(sql("select count(*) from $user_class where `uniqname` = '$auth_uniqname' and `classid` = '$classid'"), 0, 'count(*)') > 0){
			debug("Classid, $classid, already in schedule for, $auth_uniqname");
			continue;
		}
		$result = sql("select `dept`, `number`, `section` from $classes where `classid` = $classid");
		$myrow = mysql_fetch_row($result);
		$dept = $myrow[0];
		$number = $myrow[1];
		$section = $myrow[2];
		sql("insert ignore into $user_class values ('$auth_uniqname', '$classid', '$dept', '$number', '$section')");
	}
	if($debug_mode){
		showHTMLPage("Debug Mode", '', '<META http-equiv=\"refresh\" content=\"3; URL=myschedule.php\">');
		exit;
	}else{
		header("Location: myschedule.php");
	}
	
}else{
	showHTMLHead("Import Classes");
?>
<p>
The first thing we recommend doing is importing your classes.<br> 
It's really not that hard. Just follow the instructions, and <br>
if you get stuck, <a href="contact.php">contact us</a> and we'll help you out.
</p>

<p>
Currently you have 3 options for adding classes:
</p>

<ol>
<li><b>Copy and paste your schedule from Wolverine Access</b>
	<ol>
		<li>Go to <a target="_new" href="http://wolverineaccess.umich.edu/">
Wolverine Access</a>
		<li>Click <b>Student Business</b>
		<li>Log in if you need to
		<li>Click <b>View Class Schedule</b>
		<li>Click <b>Fall 2004</b>
		<li>Highlight the area where the <b>Cls#</b>s are
		<li>Right Click -> Copy
		<li>In the box below: Right Click -> Paste
		<li>Click the <b>Import Classes</b> button below
	</ol>
<li><b>Enter the 5 digit class numbers</b>
<ul>
<li>Enter them below, separated by one or more spaces
<li>No other puctuation is needed
</ul>
<li><b>Enter the department, number, and section (last resort please)</b></li>
<ol>
<li>Go to the <a href="myschedule.php">my schedule</a> page
<li>Enter the department, number, and section into the boxes
<li>Click the <b>Add</b> button
</ol>
</ol>
<p>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<textarea name="text" rows=10 cols=50></textarea><br>
<input type="submit" name="submit" value="Import Classes">
</form>
</p>
<?
	showHTMLFoot();
	exit;
}
?>