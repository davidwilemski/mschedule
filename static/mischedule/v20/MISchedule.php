<?
require_once("./php/checkopen.php");
require_once("../obfuscate.php");

$term = $_GET['term'];
if (!isset($term))
{
    echo ("no term specified. please return to <a href='..'> the main page</a>");
    exit;
}
$request = "http://{$_SERVER['SERVER_NAME']}/mschedule/static/mischedule/v20/php/request.php";
$base = "http://{$_SERVER['SERVER_NAME']}/mschedule/static/mischedule/v20/";
?>

<html>
<head>
<meta http-equiv="Content-Language" content='en-US'>
<base href='<?= $base ?>' />
<title>Mschedule.com</title>
</head>
<body bgcolor=#FFFFFF >
<table>
<tr>
<td valign=top>
<a href="/mschedule/static/mischedule/v20/MISchedule.php?term=<?= $term ?>"><img src='/mschedule/static/mischedule/v20/images/title.gif' border="0"></a>
</td>
</tr>
<tr>
<td valign=top>
<!--<applet codebase='http://static.mschedule.com/applet/classes/' code='MISchedule.class' width=605 height=500>-->
<applet codebase='/mschedule/static/mischedule/v20/classes/' code='MISchedule.class' width=605 height=500>
<param name='term' value='<?=$term?>'>
<param name='request' value='<?=$request?>'>
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
<?php }else if(($term == "f10")){?>
<h2>Fall 2010</h2>
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
<script type="text/javascript"><!--
google_ad_client = "pub-1250650128629309";
/* full banner 468x60, created 6/14/10 */
google_ad_slot = "9558980438";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-9959666-3']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>
</html>
