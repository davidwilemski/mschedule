<?php // resources_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setComment( "'Ficklampa', array( 'altrep' => 'trattgrammofon' )" );
$e->setResources( 'Ficklampa', array( 'altrep' => 'trattgrammofon' ));
$e->setLocation( 'Buckingham Palace' );
$e->setComment( "array( 'Oljekanna', 'trassel' )" );
$e->setResources( array( 'Oljekanna', 'trassel' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'Ficklampa', array( 'altrep' => 'trattgrammofon' )" );
$e->setResources( 'Ficklampa', array( 'altrep' => 'trattgrammofon' ));
$e->setLocation( 'Buckingham Palace' );
$e->setComment( "array( 'Oljekanna', 'trassel' )" );
$e->setResources( array( 'Oljekanna', 'trassel' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( "array( 'Oljekanna', 'trassel' ), array( 'language' => 'se', 'yParam', 'altrep' => 'rundsmörjningsgrejjor' )" );
$e->setLanguage( 'no' );
/* echo $e->getLanguage()."\n\n"; // test ### */
$e->setLocation( 'Buckingham Palace' );
$e->setResources( array( 'Oljekanna', 'trassel' ), array( 'language' => 'se', 'yParam', 'altrep' => 'rundsmörjningsgrejjor' ));
$e->setComment( "'Ficklampa', array( 'trattgrammofon' )" );
$e->setResources( 'Ficklampa', array( 'trattgrammofon' ));
 // print_r( $e ); echo "\n\n"; // test ###
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>