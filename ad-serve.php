<?php

require_once 'inc/common.php';
require_once 'inc/func.recordAdData.php';

$ad = 'adsense';

switch($ad){
	case 'amazon':
		header('Location: http://rcm.amazon.com/e/cm?t=mschedule-20&o=1&p=11&l=ur1&category=textbooks&banner=17P1AE8RQ1T7ZFC62V82&f=ifr');
		break;
	case 'memcatch':
?>
<a target="_parent" href="/ad-click.php?id=memcatch"><img border="0" src="http://static.mschedule.com/images/ads/memcatch.jpg" height="600" width="120"></a>
<?php
		recordAdData('serve');
		break;
	case 'adsense':
?>
<script type="text/javascript"><!--
google_ad_client = "pub-0555098270956010";
/* Next To Applet */
google_ad_slot = "1014673887";
google_ad_width = 120;
google_ad_height = 600;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php
		break;
}
