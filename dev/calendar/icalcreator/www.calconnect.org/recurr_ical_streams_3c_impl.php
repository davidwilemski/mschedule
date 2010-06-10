<?php // recurr_ical_streams_3c_impl.php

require_once '../iCalcreator.class.php';

echo "12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";

$c = new vcalendar ();
$c->setMethod( 'REQUEST' );
$c->setXprop( 'X-LOTUS-CHARSET', 'UTF-8' );

$t = new vtimezone();
$t->setTzid( 'Eastern' );

$s = new vtimezone( 'standard' );
$s->setDtstart( '19501029T020000' );
$s->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 10 
                   , 'BYDAY'      => array( -1, 'DAY' => 'SU' )
                   , 'BYHOUR'     => 2
                   , 'BYMINUTE'   => 0 ));
$s->setTzoffsetfrom( '-0400' );
$s->setTzoffsetto( '-0500' );
$t->addSubComponent( $s );

$d = new vtimezone( 'daylight' );
$d->setDtstart( '19500402T020000' );
$d->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 4 
                   , 'BYDAY'      => array( 1, 'DAY' => 'SU' )
                   , 'BYHOUR'     => 2
                   , 'BYMINUTE'   => 0 ));
$d->setTzoffsetfrom( '-0500' );
$d->setTzoffsetto( '-0400' );
$t->addSubComponent( $d );

$c->addComponent( $t );

$e = new vevent();
$e->setDtstart( '20050428T110000 Eastern' );
$e->setDtend( '20050428T120000 Eastern' );
$e->setTransp( 'OPAQUE' );
$e->setRdate( array( array( '20050428T110000 Eastern', '20050428T120000' )));
$e->setRecurrenceid( '20050428T130000Z' );
$e->setComment ( "Another single instance reschedule - time only (+2 hrs)", array( 'ALTREP' => 'CID:<FFFF__=0ABBE548DFE1F4C08f9e8a93d@coffeebean.com>' ));
$e->setSequence( 1 );
$e->setAttendee( 'iCalChair@coffeebean.com'
                , array( 'ROLE'           => 'CHAIR'
                       , 'PARTSTAT'       => 'ACCEPTED' 
                       , 'RSVP'           => 'FALSE'
                       , 'CN'             => 'iCal Chair/CoffeeBean'));
$e->setAttendee( 'iCalParticipant@coffeebean.com'
                , array( 'ROLE'           => 'REQ-PARTICIPANT'
                       , 'PARTSTAT'       => 'NEEDS-ACTION' 
                       , 'RSVP'           => 'TRUE'
                       , 'CN'             => 'iCal Participant/CoffeeBean'));
$e->setClass( 'PUBLIC' );
$e->setDescription ( 'body', array( 'ALTREP' => 'CID:<FFFE__=0ABBE548DFE1F4C08f9e8a93d@coffeebean.com>'));
$e->setSummary( 'More complicated stream (5 day recurring)' );
$e->setOrganizer( 'iCalChair@coffeebean.com', array( 'CN' => 'iCal Chair/CoffeeBean' ));
$e->setUid( '6BA1ECA4D58B306C85256FDB0071B664-Lotus_Notes_Generated' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


?>