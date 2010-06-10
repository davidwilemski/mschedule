<?php // requrrenceid_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$d1  = array ( 2001, 1, 1, 1, 1, 1, '+0500' );
$d2  = array ( 'year' => 2002, 'month' => 2, 'day' => 2, 'hour' => 2, 'min' => 2, 'sec' => 2 ); 
$d3  = array ( 2003, 3, 3 ); 
$d4  = array ( 'year' => 2004, 'month' => 4, 'day' => 4, 'tz' => 'US-Eastern' ); 
$da  = '5 May 2005 5:5:5'; 
$db  =  '5/1/2005 5.2 US-Eastern'; 
$timestamp = mktime( 9, 9, 9, 9, date('d'), date('Y'));
$st1 = array( 'timestamp' => $timestamp );
$timestamp = mktime( 10, 10, 10, 10, date('d'), date('Y') + 1 );
$st2 = array( 'timestamp' => $timestamp, 'tz' => '+0100' );

$e = new vevent();
$e->setComment( '1: '.implode( '-', $d1 ));
$e->setRecurrenceid( $d1 );
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '2: '.implode( '-', $d2)." 'range' => 'THISANDPRIOR' ");
$e->setRecurrenceid( $d2, array( 'range' => 'THISANDPRIOR' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '3: '.implode( '-', $d3 ));
$e->setRecurrenceid( $d3 );
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '4: '.implode( '-', $d4)." array( 'range' => 'THISANDFUTURE', 'yparamValue' ");
$e->setRecurrenceid( $d4, array( 'range' => 'THISANDFUTURE', 'yparamValue' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '5: '.$da );
$e->setRecurrenceid( $da );
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '6: '.$db." array( 'range' => 'THISANDPRIOR', 'xparamKey' => 'xparamValue' ");
$e->setRecurrenceid( $db, array( 'range' => 'THISANDPRIOR', 'xparamKey' => 'xparamValue' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '7: '.implode( '-', $st1)." array( 'range' => 'THISANDFUTURE', 'yparamValue' ");
$e->setRecurrenceid( $st1, array( 'range' => 'THISANDFUTURE', 'yparamValue' ));
$c->addComponent( $e );

$e = new vevent();
$e->setComment( '8: '.implode( '-', $st2)." array( 'range' => 'THISANDFUTURE', 'yparamValue' ");
$e->setRecurrenceid( $st2, array( 'range' => 'THISANDFUTURE', 'yparamValue' ));
$c->addComponent( $e );


$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>