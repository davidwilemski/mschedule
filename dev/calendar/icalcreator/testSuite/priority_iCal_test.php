<?php // priority_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$t = new vtodo();
$t->setPriority ( 3 );
$c->addComponent( $t );

$t = new vtodo();
$t->setPriority ( 2, array( 'priority' => 'HIGH', 'Important' ));
$c->addComponent( $t );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
