<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setClass( 'PRIVATE' );
$c->addComponent( $e );

$e = new vevent();
$e->setClass( 'CONFIDENTIAL', array( 'xparam1', 'xparamKey' => 'xparamValue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>
