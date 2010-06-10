<?
require_once("./php/checkopen.php");
require_once("../obfuscate.php");

$term = $_GET['term'];
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
<table>
<tr>
<td valign=top>
<a href="/"><img src='http://static.mschedule.com/images/topbar-605.jpg' border="0"></a>
</td>
<td valign=top rowspan=2>
<iframe src="/ad-serve.php" width="120" height="600" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
</td>
</tr>
<tr>
<td valign=top>
<!--<applet codebase='http://static.mschedule.com/applet/classes/' code='MISchedule.class' width=605 height=500>-->
<applet codebase='classes/' code='MISchedule.class' width=605 height=500>
Your browser doesn't support java.  MISchedule requires a java-enabled browser.
<param name=term value='<?=$term?>'>
<param name=request value='<?=$request?>'>
</applet>


<!--
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
<?php }else if(($term == "w08")){?>
<h2>Winter 2008</h2>
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
-->
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
