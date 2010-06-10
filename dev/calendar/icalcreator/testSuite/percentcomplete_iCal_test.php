<?php // percentcomplete_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$t = new vtodo();
$t->setPercentComplete ( 90 );
$c->addComponent( $t );

$t = new vtodo();
$t->setPercentComplete ( 75, array( 'xparamKey' => 'xparamValue', 'yParam' ));
$c->addComponent( $t );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
