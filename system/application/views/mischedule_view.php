<?php 

require_once("static/mischedule/v20/php/checkopen.php");

$request = base_url() . "static/mischedule/v20/php/request.php";
$base = base_url() . "static/mischedule/v20/";

echo $request;

?>

<applet codebase=<?=base_url() . 'static/mischedule/v20/classes/'?> code='MISchedule.class' width=605 height=500>
<param name='term' value='<?=$term?>'>
<param name='request' value='<?=$request?>'>
</applet>