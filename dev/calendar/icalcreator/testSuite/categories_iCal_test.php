<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setCategories( "category1" );
$c->addComponent( $e );

$e = new vevent();
$e->setCategories( "category1, category2", array('hejsan' => 'tjoflöjt', 'hoppsan', 'language' => 'en' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'se' );
$e->setCategories( "category3", array('hejsan2' => 'tjoflöjt2', 'hoppsan' ));
$e->setCategories( "category4", array('xKey' => 'xValue'));
$c->addComponent( $e );


$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>