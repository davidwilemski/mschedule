<?php
	/*
		This is the footer view. It contains the div for the bottom style bar, the small about box,
		and Google Analytics.
		This is loaded by include/template.
	*/
?>
<div id="bottom_bar"></div>
<div id="footer">
	<div id="footer_content">
		<p>For support, maintenance issues, bug reports, free coffee, and mediocre advice, email webmaster [at] mschedule [dot] com.</p>
		<p>Maintained by Bryan Kendall, David Wilemski, Tom Bombach, and Jake Schwartz. Copyright 2010.</p>
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