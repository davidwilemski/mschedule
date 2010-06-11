<?php // common.php
//include this file in every page on the site for commonly used functions
require_once 'inc/class.MetricsTracker.php';

$metrics = new MetricsTracker('');

$currentTerm = "fall10";
$currentTermText = "Fall 2010";
$previousTerm = "winter10";
$previousTermText = "Winter 2010";

date_default_timezone_set('America/Detroit');

//clears request vars from global scope (as if register_globals was off)
foreach ($_REQUEST as $request_key=>$request_value) {
   if (isset($$request_key))
       unset($$request_key);
}

$debug_addresses = array(
);

if(in_array($_SERVER["REMOTE_ADDR"], $debug_addresses)){
	$debug_mode = true;
}else{
	$debug_mode = false;
}

ini_set('log_errors', '1');
ini_set('display_errors', '1');
ini_set('session.bug_compat_42', '0');
ini_set('session.use_trans_sid', '0');
ini_set('sendmail_from', 'mschedule@umich.edu');
ini_set('SMTP', 'localhost');

set_error_handler("myErrorHandler");


debug("server_name = {$_SERVER['SERVER_NAME']}");
debug("php_self = ".$_SERVER['PHP_SELF']);
debug("include_path = ".get_include_path());

//initialize session
session_save_path();
session_start();
foreach ($_SESSION as $request_key=>$request_value) {
   if (isset($$request_key))
       unset($$request_key);
}

if($_REQUEST['term'] == $currentTerm){
	$_SESSION['term'] = $currentTerm;
}else if($_REQUEST['term'] == $previousTerm){
	$_SESSION['term'] = $previousTerm;
}

$term = isset($_SESSION['term']) ? $_SESSION['term'] : $currentTerm;
$_SESSION['term'] = $term;

$auth_uniqname = $_SESSION['uniqname'];

$debug_uniqnames = array(
'mulka'
);

if(in_array($_SESSION['uniqname'], $debug_uniqnames)){
	$debug_mode = true;
}

include_once 'inc/db.php';
//include_once 'inc/logvars.php';
if($debug_mode){
	ini_set('display_errors', '1');
}

//page names
$login_page = 'login.php';
$start_page = 'loginprocess.php';
$new_user_page = 'register.php';
$email_page = 'email.php';
$update_page = 'updated.php';

$myname = 'Mschedule.com';
$myaddress = 'mschedule@umich.edu';

getdata(array('status'), 'get');
if($status != '') status($status);

if(!$debug_mode){
	dbConnect();
	$dbgetdata = mysql_real_escape_string(var_export($_GET, true));
	$dbpostdata = mysql_real_escape_string(var_export($_POST, true));
	if($_SERVER['PHP_SELF'] != "/loginprocess.php" && $_SERVER['PHP_SELF'] != "/confirm.php"){
		sql("insert into `$access_log` set time = now(), ip = '{$_SERVER['REMOTE_ADDR']}', uniqname = '{$_SESSION['uniqname']}', page = '{$_SERVER['PHP_SELF']}', referer = '{$_SERVER['HTTP_REFERER']}', post = '$dbpostdata', get = '$dbgetdata'");
	}
}


//
//		Functions:
//
// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	if($errno == 8 and strstr($errstr, "Undefined ")){
		return;
	}
	global $php_errors, $myaddress, $debug_mode;
	dbconnect();
	if($debug_mode){
		echo "ERROR".$errno.' '.$errstr.' '.$errfile.' '.$errline."ERROR";
	}
	$errstr = mysql_real_escape_string($errstr);
	$sql = "insert into `$php_errors` values ($errno, '$errstr', '$errfile', $errline)";
	if(!@mysql_query($sql)){
		error_log_email($sql."\n".mysql_error());
	}
}

function error_log_email($msg){
	if(ini_get('SMTP') == 'smtp.comcast.net') return;
	global $myaddress;
	error_log($msg, 1, $myaddress);
}

function error($msg, $type = 'user') {
	switch($type){
	case 'fatal':
		$show = true;
		$exit = true;
		$report_email = true;
		break;
	case 'invalid_input':
		$show = true;
		$exit = true;
		$report_email = false;
		break;
	case 'sql':
		$show = true;
		$exit = true;
		$report_email = true;
		break;
	case 'system':
		$show = false;
		$exit = false;
		$report_email = true;
		break;
	case 'user':
	default:
		$show = true;
		$exit = true;
		$report_email = true;
		break;
	}
	
	
	global $myaddress, $error_log, $debug_msgs;
	foreach($_POST as $key => $value){
		$postdata .= $key." => ".$value."<br>\n";
	}
	foreach($_GET as $key => $value){
		$getdata .= $key." => ".$value."<br>\n";
	}
	$uniqname = $_SESSION['uniqname'];
	$fullname = $_SESSION['fullname'];
	$sid = $_COOKIE["PHPSESSID"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$message = "
Mschedule.com Error

Message: $msg<br>
Uniqname: $uniqname<br>
Full Name: $fullname<br>
Session ID: $sid<br>
IP Address: $ip<br>
Debug Messages:<br>
$debug_msgs<br>
Post data:<br>
$postdata
Get data:<br>
$getdata
</p>
";
	if($report_email){
		//$msg .= " This error has been reported.";
		error_log_email($message);
	}
	dbConnect();
	$dbmsg = mysql_real_escape_string($msg);
	$dbdebug_msgs = mysql_real_escape_string($debug_msgs);
	$dbpostdata = mysql_real_escape_string($postdata);
	$dbgetdata = mysql_real_escape_string($getdata);
	$sql = "insert into `$error_log` set uniqname = '$uniqname', ip = '$ip', message = '$dbmsg', debug = '$dbdebug_msgs', post = '$dbpostdata', get = '$dbgetdata'";
	if(!mysql_query($sql)){
		global $myaddress;
		error_log_email($sql."\n".mysql_error());
	}
	if($show){
		$body = "<p>".$msg." If you need help, please <a href=\"contact.php\">contact us</a>.</p>\n<a href=\"javascript:history.back()\">Back to Previous Page</a>";
		showHTMLPage("Error", $body);
		exit;
	}
	if($exit){
		exit;
	}
}

function status($msg){
	global $status_bar_text;

	$status_bar_text .= '   '.$msg;
}

function debug($msg) {
	global $debug_msgs;
	$debug_msgs .= $msg."<br>\n";	
}

function showHTMLHead($title='', $tags=''){
	global $status_bar_text, $debug_mode, $currentTerm, $currentTermText, $previousTerm, $previousTermText;
?>
<html>
<head>
<title> Mschedule.com - <?=strip_tags($title)?> </title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="../stylesheets/main.css">
<?=$tags?>
</head>
<body>

<div style="text-align: left; width: 800px">
<?
if($debug_mode){
	echo "<a href=\"admin.php\">";
}else{
	echo "<a href=\"/\">";
}
?>
<img style="border: 1px solid black;" src="http://static.mschedule.com/images/topbar.jpg"></a><br>
<table width="800">
<tr>
<td>
<? include 'components/signedinas.php';?>
</td>
<td align="right">
<?

$href = $_SERVER['SCRIPT_URI'];

function createQueryString($queryString){
	$rv = '?';
	foreach($queryString as $key => $value){
		$rv .= $key . '=' . $value . '&';
	}
	
	return rtrim($rv, '&');
}

$queryString = $_GET;
	if($_SESSION['term'] == $currentTerm){
		$queryString['term'] = $previousTerm; 
		$href .= createQueryString($queryString);
		print "<b>$currentTermText</b> (<a href=\"$href\">switch to $previousTermText</a>)";
	}else{
		$queryString['term'] = $currentTerm; 
		$href .= createQueryString($queryString);
		print "<b>$previousTermText</b> (<a href=\"$href\">switch to $currentTermText</a>)";
	}

	
	
?>
</td>
</tr>
</table>

<div class="outerbox" style="border: 1px solid black; padding: 4px; width: 796px;">
<? include 'components/topmenubar.php'; ?>
</div>
<div style="float: right; padding: 10px;">
<!-- Begin: AdBrite -->
<!--
<script type="text/javascript">
   var AdBrite_Title_Color = '191919';
   var AdBrite_Text_Color = '000000';
   var AdBrite_Background_Color = 'CCCCCC';
   var AdBrite_Border_Color = 'CCCCCC';
</script>
<script src="http://ads.adbrite.com/mb/text_group.php?sid=198899&zs=3132305f363030" type="text/javascript"></script>
<div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=198899&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>
-->
<!-- End: AdBrite -->
</div>
<div style="width: 800px;"><blockquote>
<iframe src="http://rcm.amazon.com/e/cm?t=mschedule-20&o=1&p=13&l=ur1&category=textbooks&banner=1RQK7WBPFE6ANNRN0302&f=ifr" width="468" height="60" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe><?
	echo "<h3> $title </h3>\n";
	
	if(isset($status_bar_text)){
		echo "<p><b>Status: </b>", $status_bar_text, "</p>\n";
	}
}

function showHTMLFoot(){
	global $myname, $status_bar_text, $debug_mode, $debug_msgs;
	
	//the percent sign below cannot be used in single line comments because it escapes php parsing mode
	?></blockquote><table><tr><td colspan=2><hr></td></tr><tr><td>
	<div class="outerbox" style="border: 1px solid black; padding: 2px;">
<? include 'components/bottommenubar.php'; ?>
	</div>
	</td><td align=right><small>Developed by Kyle Mulka.<br> Style by Matt Pizzimenti. <br>Maintained by Scott Goldman.</small></td></tr></table><?php
	
	if(count($_POST)){
		debug("<b>Post data:</b>");
		foreach($_POST as $key => $value){
			debug($key." -> ".$value);
		}
	}
	if(count($_SESSION)){
		debug("<b>Session data:</b>");
		debug("session id = ".session_id());
		foreach($_SESSION as $key => $value){
			debug($key." -> ".$value);
		}
	}
	if($debug_mode){
		echo "<p><a href=\"admin.php\">Admin</a></p>";
		echo "<p><strong>Debug info:</strong><br>";
		echo $debug_msgs;
		//$debug_msgs = mysql_real_escape_string($debug_msgs);
		//sql("insert into debug_info values ('{$_SERVER['REMOTE_ADDR']}', '{$_SESSION['uniqname']}', '$debug_msgs')");
	}

	?>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-813415-1");
pageTracker._trackPageview();
} catch(err) {}
</script>
	</body>
	</html>
	<?	
}

function showHTMLPage($title = '', $body = '', $tags = ''){ 
	showHTMLHead($title, $tags);
	echo $body;
	showHTMLFoot();
}

//so that post data is not saved with the page
//aka, you can refresh the page and it won't be submitting a class again
function clearpostdata($status = ''){
	
	if(count($_POST)){
		if($debug_mode){
			showhtmlpage("Debug Mode", "Status: $status", '<META http-equiv=\"refresh\" content=\"3; URL={$_SERVER["PHP_SELF"]}\">');
		}else{
			$string = "Location: {$_SERVER['PHP_SELF']}";
			if($status != ''){
				$string .= "?status=$status";
			}
			header($string);
		}
		exit;
	}
	
}

function getdata($array, $where = 'post', $alltype = ''){
	debug("<b>Formated post data:</b>");
	foreach($array as $value){
		//post data values accepted
		//classid, uniqname, password, name, email, submit, action, fullname, dept, number, section, text, message
		//array: classids
		if($alltype == ''){
			$type = $value;
		}else{
			$type = $alltype;
		}
		global $$value;
		switch($where){
			case 'post':
				if(isset($_POST[$value])){
					if(count($_POST[$value]) > 1){
						foreach($_POST[$value] as $key => $innervalue){
							$$value[$key] = strip_tags($innervalue);
						}
					}else{
						$$value = strip_tags($_POST[$value]);
					}
				}
				break;
			case 'get':
				if(isset($_GET[$value])){
					if(count($_GET[$value]) > 1){
						foreach($_GET[$value] as $key => $innervalue){
							$$value[$key] = strip_tags($innervalue);
						}
					}else{
						$$value = strip_tags($_GET[$value]);
					}
				}
				break;
			default:
				return;
		}
		switch($type){
			case 'uniqname':
			case 'friend_uniqname':
				$$value = strtolower($$value);
				$$value = preg_replace("/[^a-z]/", '', $$value);
				$$value = substr($$value, 0, 8);
				break;
			case 'uniqnames':
				$$value = strtolower($$value);
				$$value = preg_replace("/[^a-z ]/", ' ', $$value);
				break;
			case 'password':
			case 'code':
				//page should do its own error checking?
				break;
			case 'name':
			case 'fullname':
			case 'status':
				$$value = preg_replace("/[^a-zA-Z.,\- ]/", '', $$value);
				$$value =  trim($$value);
				break;
			case 'term':
				$$value = preg_replace("/[^a-z0-9]/", '', $$value);
				$$value = substr($$value, 0, 5);
				break;
			case 'classid':
				$$value = preg_replace("/[^0-9]/", '', $$value);
				$$value = substr($$value, 0, 5);
				break;
			case 'classids':
				$$value = preg_replace("/[^,;0-9]/", '', $$value);
				break;				
			case 'dept':
				$$value = strtoupper($$value);
				$$value = preg_replace("/[^A-Z]/", '', $$value);
				break;
			case 'number':
			case 'section':
				$$value = preg_replace("/[^0-9]/", '', $$value);
				$$value = substr($$value, 0, 3);
				break;
			case 'email':
				//$$value = preg_replace("/[\]\[\"\',\/\\]/", '', $$value);
				break;
			case 'submit':
			case 'action':
			case 'getcommon':
			case 'field_name':
				$$value = strtolower($$value);
				$$value = preg_replace("/\W/", '', $$value);
				break;
			case 'text':
			case 'message':
				break;
			default:
				$$value = '';
		}
		debug($value." -> ".$$value." (".$type.")");
	}
}

function sendemail($message, $from='', $subject = "From Web Site", $to=''){
	global $myname, $myaddress;
	if($to == ''){
		$to="$myname <$myaddress>";
	}
	if($from == ''){
		$from="$myname <$myaddress>";
	}
	//$headers = "To: $to\nFrom: $from\n";
	$headers = "From: $from\n";
	if(stristr($to, $myaddress) == false){
		$headers .= "Bcc: $myaddress\n";
	}
	$to = str_replace(",", '', $to);
	//send email
	$rv = mail($to, $subject, $message, $headers);
	debug("<b>Sent email:</b>");
	debug("To: ".$to);
	debug("Subject: ".$subject);
	debug("Message: ".$message);
	debug("Headers: ".$headers);
	return $rv;
}
?>
