<?php // action_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new valarm();
$e->setDescription( 'AUDIO' );
$e->setAction( 'AUDIO' );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "'AUDIO', array( 'Glaskrasch' )");
$e->setAction( 'AUDIO', array( 'Glaskrasch' ));
$c->addComponent( $e );


$e = new valarm();
$e->setDescription( "'AUDIO', array('Glaskrasch', 'kristallkrona', 'silverbricka' )");
$e->setAction( 'AUDIO', array('Glaskrasch', 'kristallkrona', 'silverbricka' ));
$c->addComponent( $e );

$str = $c->createCalendar();
$str = str_replace( "<", "&lt;", $str );
$str = str_replace( ">", "&gt;", $str );
echo $str."<br />\n";
// $c->returnCalendar( FALSE, 'test.xml' );
?>