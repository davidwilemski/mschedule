<?php // trigger_iCal_test.php

require_once '../iCalcreator.class.php';

/*
echo "setTrigger( year, month, day, week , hour, min, sec, relatedEnd=FALSE, after=FALSE, tz, xparams )<br />\n";
setTrigger( int year/FALSE, int month/FALSE, int day/FALSE [, int week/FALSE ] [, int hour/FALSE, int min/FALSE, int sec/FALSE ] [, bool relatedend=FALSE ] [, bool before=TRUE )
example
$c->setTrigger( FALSE, FALSE, FALSE, FALSE, 1, 2, 3, TRUE, TRUE ); // 1 hour 2 min 3 sec, before end,
*/
$c = new vcalendar ();
$c->setFormat( "xcal" );
$e = new valarm();
$e->setDescription( '1: F, F, F, FALSE, 1, 2, 3, F, T (start, after)' );
$e->setTrigger( FALSE, FALSE, FALSE, FALSE, 1, 2, 3, FALSE, TRUE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "1B: array( 'hour' => 1, 'min' => 2, 'sec' => 3 ), TRUE, FALSE (end, before)" );
$e->setTrigger( array( 'hour' => 1, 'min' => 2, 'sec' => 3 ), TRUE, FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( '2: FALSE, FALSE, FALSE, 4' );
$e->setTrigger( FALSE, FALSE, FALSE, 4 );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2b: array( 'week' => 4 )" );
$e->setTrigger( array( 'week' => 4 ) );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2c: array( 'week' => 4 ), TRUE (end)" );
$e->setTrigger( array( 'week' => 4 ), TRUE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2d: array( 'week' => 4 ), FALSE (start)" );
$e->setTrigger( array( 'week' => 4 ), FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2e: array( 'week' => 4 ), TRUE, TRUE (end, after)" );
$e->setTrigger( array( 'week' => 4 ), TRUE, TRUE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2f: array( 'week' => 4 ), FALSE, TRUE (start, after)" );
$e->setTrigger( array( 'week' => 4 ), FALSE, TRUE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2g: array( 'week' => 4 ), TRUE, FALSE (end, before)" );
$e->setTrigger( array( 'week' => 4 ), TRUE, FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "2h: array( 'week' => 4 ), FALSE, FALSE (start, before)" );
$e->setTrigger( array( 'week' => 4 ), FALSE, FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( '3: FALSE, FALSE, 5, FALSE, 1, 2, 3, FALSE' );
$e->setTrigger( FALSE, FALSE, 5, FALSE, 1, 2, 3, FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "3b: array('day'=>5,'hour'=>1,'min'=>2,'sec'=>3), F" );
$e->setTrigger( array( 'day' => 5, 'hour' => 1, 'min' => 2, 'sec' => 3 ), FALSE );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "4: 2007,6,5,F,1,2,3',F,F,'-0200', array( 'xparamKey' => 'xparamValue' )" );
$e->setTrigger( 2007, 6, 5, FALSE, 1, 2, 3, FALSE, FALSE, '-0200', array( 'xparamKey' => 'xparamValue' ) );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "4b: array( 'year' => 2007, 'month' => 6, 'day' => 5, 'hour' => 2, 'min' => 2, 'sec' => 3, 'tz' => '-0200' ), array( 'xparamKey' => 'xparamValue' )" );
$e->setTrigger( array( 'year' => 2007, 'month' => 6, 'day' => 5, 'hour' => 2, 'min' => 2, 'sec' => 3, 'tz' => '-0200' ), array( 'xparamKey' => 'xparamValue' ) );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "5: '14 august 2006 16.00.00', array( 'xparamKey' => 'xparamValue' )" );
$e->setTrigger( '14 august 2006 16.00.00', array( 'xparamKey' => 'xparamValue' ) );
$c->addComponent( $e );

$e = new valarm();
$e->setDescription( "6: '14 august 2006', array( 'xparamKey' => 'xparamValue' )" );
$e->setTrigger( '14 august 2006', array( 'xparamKey' => 'xparamValue' ) );
$c->addComponent( $e );

$a = new valarm();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$a->setTrigger( array( 'timestamp' => $timestamp ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$a->setDescription( '7a: '.$timestamp.' tre xparams' );
$c->addComponent( $a );

$a = new valarm();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$a->setTrigger( array( 'timestamp' => $timestamp, 'tz' => '-0200' ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$a->setDescription( '7b: '.$timestamp.'=now, tz=-0200 and tre xparams' );
$c->addComponent( $a );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>