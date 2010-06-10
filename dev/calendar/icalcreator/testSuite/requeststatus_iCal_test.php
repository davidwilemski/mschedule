<?php // requeststatus_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setRequestStatus( 1.00, '1 hejsan hejsan', '1 gammalt fel, som skickats igen' );
$e->setRequestStatus( 2.00, '2 hejsan hejsan', '2 gammalt fel, som skickats igen', array ( 'xparamKey' => 'xparamValue'));
$e->setRequestStatus( 1.00, '1 hejsan hejsan', '1 gammalt fel, som skickats igen', array( 'language' => 'se', 'yParam' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>