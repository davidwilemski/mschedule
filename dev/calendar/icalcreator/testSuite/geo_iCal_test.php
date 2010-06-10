<?php // completed_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vevent();
$e->setgeo ( 11.2345, -32.5678 );
$c->addComponent( $e );

$e = new vevent();
$e->setgeo ( 11.2345, -32.5678, array( 'xparamValue', 'yparamKey' => 'yparamValue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
