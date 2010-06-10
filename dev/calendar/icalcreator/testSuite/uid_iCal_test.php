<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setComment( 'generate (auto) uid' );
$e->setComment(  $e->getUid());
$c->addComponent( $e );

$e = new vevent();
$e->setUid( '19960401T080045Z-4000F192713-0052@host1.com' );
$e->setComment( 'set (store) uid' );
$e->setComment( $e->getUid( ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>