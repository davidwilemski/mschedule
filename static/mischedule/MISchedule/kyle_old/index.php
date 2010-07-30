<?
$request_url = "http://{$_SERVER['HTTP_HOST']}/mischedule/php/request.php";
?>
<html>
<head>
<title>MISchedule - Back From The Dead!</title>
</head>
<body> <!--bgcolor=#FFFFFF-->
<applet codebase='classes/' code='MISchedule.class' width="605" height="500">
Your browser doesn't support java.  MISchedule requires a java-enabled browser.
<param name=term value='fall04'>
<param name=request value='<?=$request_url?>'>
</applet>
</body>
</html>
