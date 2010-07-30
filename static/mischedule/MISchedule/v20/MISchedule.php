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
<applet codebase='classes/' code='MISchedule.class' width=605 height=500>
Your browser doesn't support java.  MISchedule requires a java-enabled browser.
<param name=term value='<?=$term?>'>
<param name=request value='<?=$request?>'>
</applet>

</td>
<td valign=top>

<?php if($term == "f05"){ ?>
<h2>Fall 2005</h2>
<?php }else if(($term == "w06")){?>
<h2>Winter 2006</h2>
<?php }else if(($term == "f06")){?>
<h2>Fall 2006</h2>
<?php }else if(($term == "w07")){?>
<h2>Winter 2007</h2>
<?php }else{?>
<h2>Term may be invalid</h2>
<?php } ?>

<p>
Thank you for using Mschedule. Winter 2007 data may be as old as November 15th.<br>
</p>
Save, view, share, and compare the schedule you created with <a href="http://www.mschedule.com">Mschedule.com</a>.

Click "Save" after creating your schedule and logging into Mschedule.com.<p>
<img src="images/mschedule-save.gif">

<p>The automatic schedule generator was originally created by Dan Hostetler and Alex Makris. It has been modified and enhanced by <a href="http://www.kylemulka.com/">Kyle Mulka</a> to work with the new <a href="http://wolverineaccess.umich.edu/">Wolverine Access</a>. If you have questions, or problems, e-mail <span style='color:blue'><?=obfuscate('mschedule@umich.edu')?></span></p>
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
