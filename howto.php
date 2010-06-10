<?
include_once 'inc/common.php';
showhtmlhead("How To");
?>

<p>Instructions for things you might want to do:</p>

<p>
Look at someones schedule:
<ol>
<li>click "view" or "view schedule"
<li>type in a person's uniqname
<li>click the "View" button below
</ol>
</p>
<p>
Tell the world about your schedule:
<ul>
<li>just paste the follwing url (with <?=htmlentities("<your_uniqname_here>")?> replaced with your own uniqname of course) into email, or aim conversation for the person to click on
<li>http://www.mschedule.com/view.php?uniqname=<?=htmlentities("<your_uniqname_here>")?>
</ul>
</p>
<p>
Link to your schedule from a website, AIM profile, or blog:
<ul>
<li>do the same as above or
<li>log in, click "spread the word", and copy and paste the code
</ul>
</p>
<p>
Disable viewing of your schedule: (although that kinda defeats the purpose of the site)
<ol>
<li>log into the site
<li>click "preferences"
<li>under privacy select "private"
<li>click "Save Preferences" at the bottom of the page
</ol>
</p>

<?
showhtmlfoot();
?>