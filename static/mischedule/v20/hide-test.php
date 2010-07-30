<?
require_once("./php/checkopen.php");
require_once("../obfuscate.php");

$term = 'f07';

if (!isset($term))
{
    echo ("no term specified. please return to <a href='..'> the main page</a>");
    exit;
}
$request = "http://{$_SERVER['SERVER_NAME']}/mischedule/v20/php/request.php";

?>

<html>
<head>
<title>Mschedule.com</title>
</head>
<body bgcolor=#FFFFFF >
<a href="/"><img src='/images/topbar.jpg' border="0"></a>


<table>
<tr>
<td valign=top>
	
	<!-- Begin: AdBrite -->
<!--
	<script type="text/javascript">
	   var AdBrite_Title_Color = '000000';
	   var AdBrite_Text_Color = 'FFFFFF';
	   var AdBrite_Background_Color = '3D81EE';
	   var AdBrite_Border_Color = '000000';
	</script>
	<script src="http://ads.adbrite.com/mb/text_group.php?sid=194390&zs=3132305f363030" type="text/javascript"></script>
	<div><a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=194390&afsid=1" style="font-weight:bold;font-family:Arial;font-size:13px;">Your Ad Here</a></div>
-->
	<!-- End: AdBrite -->
</td>
<td valign=top>
<div style="height: 500px; width: 605px; position: relative;">
<div id="hi" style="visibility: hidden; height: 100%; width: 100%; background-color: blue;">hi!</div>
<div id="applet" style="float: left; position: absolute; top: 0px; height: 100%; width: 100%;">
<applet codebase='classes/' code='MISchedule.class' width=605 height=500>
Your browser doesn't support java.  MISchedule requires a java-enabled browser.
<param name=term value='<?=$term?>'>
<param name=request value='<?=$request?>'>
</applet>
</div>
<div style="clear:both">
</div>
</div>

</td>
<td valign=top>

<input type="button" onclick="toggle()">
<script>
var onTop = 'applet';
function toggle(){
	if(onTop == 'applet'){
		document.getElementById('hi').style.visibility = 'visible';
		document.getElementById('applet').style.visibility = 'hidden';
		onTop = 'hi';
	}else{
		document.getElementById('applet').style.visibility = 'visible';
		document.getElementById('hi').style.visibility = 'hidden';
		onTop = 'applet';
	}
}

</script>


<?php if($term == "f05"){ ?>
<h2>Fall 2005</h2>
<?php }else if(($term == "w06")){?>
<h2>Winter 2006</h2>
<?php }else if(($term == "f06")){?>
<h2>Fall 2006</h2>
<?php }else if(($term == "w07")){?>
<h2>Winter 2007</h2>
<?php }else if(($term == "f07")){?>
<h2>Fall 2007</h2>
<?php }else{?>
<h2>Term may be invalid</h2>
<?php } ?>

<p>
Thank you for using Mschedule. Fall 2007 data may be as old as March 27th.<br>
</p>
Save, view, share, and compare the schedule you created with <a href="http://www.mschedule.com">Mschedule.com</a>.

Click "Save" after creating your schedule and logging into Mschedule.com.<p>
<img src="images/mschedule-save.gif">
<p>The automatic schedule generator was originally created by Dan Hostetler and Alex Makris. It is currently being maintained by Scott Goldman. 
</p>
<p>
If you have questions, or problems, please e-mail <span style='color:blue'><?=obfuscate('mschedule@umich.edu')?></span></p>
</td>
</tr>
</table>

<!--<? include "../donate.php"?>-->

<!-- Begin: AdBrite -->
<!--
<script type="text/javascript" src="http://ads.adbrite.com/mb/text_group.php?sid=194418&br=1&dk=73747564656e74206c6f616e5f305f325f776562"></script>
-->
<!-- End: AdBrite -->
</body>
</html>
