<?php // xprop_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setXprop  ( 'X-PROP', 'a test setting a x-prop property in calendar' );
$c->setXprop  ( 'X-WR-CALNAME', 'Games Night Meetup', array( 'xKey' => 'xValue' ));

$e = new vevent();
$e->setXprop  ( 'X-WR-CALNAME', 'Games Night Meetup' );
$e->setComment( "'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav'");
$e->setXprop  ( 'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav' );
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'de' );
$e->setComment( "'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav'");
$e->setXprop  ( 'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav' );
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'de' );
$e->setComment( "'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav', array( 'xparamKey' => 'xparamValue', 'language' => 'en' )");
$e->setXprop  ( 'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav', array( 'xparamKey' => 'xparamValue', 'language' => 'en' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( "'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav', array( 'xparamKey' => 'xparamValue', 'language' => 'en' )");
$e->setXprop  ( 'X-ABC-MMSUBJ', 'http://load.noise.org/mysubj.wav', array( 'xparamKey' => 'xparamValue', 'language' => 'en' ));
$a = new valarm();
$a->setAction( 'AUDIO' );
$a->setDescription( 'AUDIO-decription' );
$a->setXprop( 'X-ALARM-PROPERTY', 'X-ALARM-VALUE' );
$a->setAttach( 'http://www.domain.net/audiolib/ticktack.wav' );
$e->addSubComponent( $a );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";
// $c->returnCalendar( FALSE, 'test.ics' );

?>