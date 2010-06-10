<?php // summary_iCal_test.php

require_once '../iCalcreator.class.php';


$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setDescription( "Here is a newline character\nand here is another one\nperiod" );
$e->setSummary( "Here is a newline character\nand here is another one\nperiod" );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( "'This is a summary for the event', array( 'altrep' => 'This is another summary for the event', 'language' => 'de' )");
$e->setSummary( "This is a summary for the event", array( 'altrep' => 'This is another summary for the event', 'language' => 'de' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'fr' );
$e->setDescription( "setLanguage( 'fr' ); 'This is a summary for the event', array( 'altrep' => 'This is another summary for the event', 'April in Paris' )");
$e->setSummary( "This is a summary for the event", array( 'altrep' => 'This is another summary for the event', 'singing_in_the_rain' => 'April in Paris' ));
$c->addComponent( $e );


$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>