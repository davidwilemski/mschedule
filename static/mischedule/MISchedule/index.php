<?
include "obfuscate.php";
?>

<title>Mschedule.com</title>

<body>
<p><a href="/"><img src='/images/topbar.jpg'  border="0"></a>
</p>

<table cellspacing=5>
<tr>

<td valign=top>


<td valign=top>
    <blockquote>
    <h2>Welcome to Mschedule!</h2>
    <blockquote>
    <span style='color:000066;font-family:arial;font-size:12pt;'>
    <p>Mschedule is a website that helps University of Michigan students prepare and share
    course schedules. Based on the classes you want to take, and the times you're available,
    Mschedule generates up to 10 ideal schedules that fit your needs. Then, you can save your
    schedule to share with your friends.
    </span>
     
    </blockquote>
    
    <table cellspacing=6>
    <tr>
    <td valign=top width=30%>
        <span style='font-size:14pt'>
        <span style='color:000066; text-decoration:underline'><b>Choose a term below to start scheduling:</b><br></span>
        <ul>
	    <li> <a target="_blank" href="./v20/MISchedule.php?term=w07">Winter 2007</a> - <span style='color: red;'>NEW</span><br>
        <li> <a target="_blank" href="./v20/MISchedule.php?term=f06">Fall 2006</a><br>
        <!--<li> <a target="_blank" href="./v20/MISchedule.php?term=w06">Winter 2006</a><br>-->
        <!-- <li> <a target="_blank" href="./v20/MISchedule.php?term=f05">Fall 2005</a><br>
        <!-- <li><a href="./v20/MISchedule.php?term=w05">Winter 2005</a><br> -->
        </ul>
        </span>
    <tr>
    <td>
        <span style='font-size:14pt'>
        <span style='color:000066; text-decoration:underline'><b>Other stuff:</b><br></span>
        <ul>
        <li>Get <a href='help/help.html'>Help</a> with Mschedule
        <li>Download the <a href='develop.php'>Source Code</a>
        <li>Save, View, Share, and Compare your schedule with <a href='http://www.mschedule.com/'>Mschedule.com</a>
		<li><? include "donate.php"?>
        </ul>
        </span>
    </table>


    </table>

</table>

<blockquote>The automatic schedule generator was originally created by Dan Hostetler and Alex Makris. It has been modified and enhanced by <a href="http://www.kylemulka.com/">Kyle Mulka</a> to work with the new <a href="http://wolverineaccess.umich.edu/">Wolverine Access</a>. If you have questions, or problems, e-mail <span style='color:blue'><?=obfuscate('mschedule@umich.edu')?></span>



