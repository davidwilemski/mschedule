<?php // last-modified_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vevent();
$e->setComment ( "1, 2, 3, 4, 5, 6, array( 'xparamValue', 'yparamKey' => 'yparamValue' )" );
$e->setLastModified ( 1, 2, 3, 4, 5, 6, array( 'xparamValue', 'yparamKey' => 'yparamValue' ) );
$c->addComponent( $e );

$e = new vevent();
$e->setComment ( "array( 'year' => 1, 'month' => 2, 'day' => 3 ), array( 'xparamValue', 'yparamKey' => 'yparamValue' )" );
$e->setLastModified ( array( 'year' => 1, 'month' => 2, 'day' => 3 ), array( 'xparamValue', 'yparamKey' => 'yparamValue' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment ( "16 august 2006 14:40, array( 'xparamValue', 'yparamKey' => 'yparamValue' )" );
$e->setLastModified ( '16 august 2006 14:40', array( 'xparamValue', 'yparamKey' => 'yparamValue' ) );
$c->addComponent( $e );

$t = new vtodo();
$date = array( 'year' => 2006, 'month' => 10, 'day' => 10, 'tz' => '+0200' );
$date = $c->validDate( $date, TRUE ); // make localdate + offset to UTC
$t->setLastModified( $date );
$c->addComponent( $t );

$o = new vtodo();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setLastModified( array( 'timestamp' => $timestamp ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( $timestamp.' tre xparams' );
$c->addComponent( $o );
$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );
