<?php // sequence_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setSequence( 2 );
$c->addComponent( $e );

$e = new vevent();
$e->setSequence( 2, array( 'xparamKey' => 'xparamValue' ));
$c->addComponent( $e );

$e = new vevent();
$e->setSequence( 4, array( 'FOUR' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>