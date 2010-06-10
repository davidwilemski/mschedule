<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();

$e = new vevent();
$e->setComment( "This is a comment" );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( "'This is comment2', array( 'altrep' => 'This is an alternative annotation', 'hejsan', 'language' => 'da' )");
$e->setComment( "This is comment2", array( 'altrep' => 'This is an alternative annotation', 'hejsan', 'language' => 'da' ));
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( "'Å i åa ä e ö, sa Yngve Öst, ärligt och ångerfyllt', array( 'altrep' => 'This is an alternative annotation', 'hejsan', 'language' => 'da', 'xparamKey' => 'xparamvalue' )");
$e->setComment( "Å i åa ä e ö, sa Yngve Öst, ärligt och ångerfyllt", array( 'altrep' => 'This is an alternative annotation', 'hejsan', 'language' => 'da', 'xparamKey' => 'xparamvalue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.ics' );

?>