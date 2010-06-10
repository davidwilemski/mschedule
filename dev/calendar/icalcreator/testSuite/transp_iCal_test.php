<?php // transp_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setTransp( "Transparent" );
$c->addComponent( $e );

$e = new vevent();
$e->setTransp( "OPAQUE", array( 'visible' => 'occupied' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );
?>