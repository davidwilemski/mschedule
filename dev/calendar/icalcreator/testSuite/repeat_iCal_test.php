<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new valarm();
$e->setDuration( array( 'day' => 2, 'hour' => 3, 'sec' => 5 ), array( 'xparamkey' => 'xparamvalue' ));
$e->setRepeat( 2 );
$c->addComponent( $e );

$e = new valarm();
$e->setDuration( array( 'day' => 2, 'hour' => 3, 'sec' => 5 ), array( 'xparamkey' => 'xparamvalue' ));
$e->setRepeat( 2, array( 'xparamKey' => 'xparamValue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>