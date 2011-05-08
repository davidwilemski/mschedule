<?php
	/*
		This is the footer view. It contains the div for the bottom style bar, the small about box,
		and Google Analytics.
		This is loaded by include/template.
	*/
?>
<div id="footer">
	<div id="footer_content">
		<p>For support, mschedule issues, free coffee, or mediocre advice, email webmaster [at] mschedule [dot] com.</p>
		<p>Maintained by Bryan Kendall, David Wilemski, Tom Bombach, Ben Asher, and Jake Schwartz. &copy; Copyright <?php echo date('Y'); ?>.</p>
		<p>Designed by Paul Provenza.</p>
	</div>
</div>

<?php
	if(isset($javascript))
	{
		echo $javascript;
	}
?>
</body>
</html>