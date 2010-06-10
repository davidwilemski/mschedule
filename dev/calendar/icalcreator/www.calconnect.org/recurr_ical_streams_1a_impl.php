<?php // recurr_ical_streams_1a_impl.php

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
$e->setUid( 'E88157FE01BE8A5C85256FDB006EBCC3-Lotus_Notes_Generated' );
$e->setClass( 'PUBLIC' );
$e->setDtstart( '20050411T090000 Eastern' );
$e->setDtend( '20050411T100000 Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'COUNT'      => 5 ));
$e->setSequence( 0 );
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
$e->setOrganizer( 'iCalChair@coffeebean.com', array( 'CN' => 'iCal Chair/CoffeeBean' ));
$e->setSummary( '5 day daily repeating meeting' );
$e->setTransp( "OPAQUE" );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


?>