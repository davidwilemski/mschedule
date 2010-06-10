<?php // location_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vevent();
$e->setcomment ( '1  Målilla-avdelningen' );
$e->setLocation ( 'Målilla-avdelningen' );
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment ( "2  setLanguage( 'no' ) 'Målilla-avdelningen' ");
$e->setLocation ( 'Målilla-avdelningen' );
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment ( "3  setLanguage( 'no' ) 'Målilla-avdelningen', array( 'altrep' => 'Buckingham Palace', 'Xparam', 'language' => 'se' )" );
$e->setLocation ( 'Målilla-avdelningen'
                , array( 'altrep' => 'Buckingham Palace', 'Xparam', 'language' => 'se' ));
$c->addComponent( $e );


$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
