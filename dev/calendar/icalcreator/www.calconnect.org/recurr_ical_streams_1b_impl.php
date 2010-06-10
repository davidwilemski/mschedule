<?php // recurr_ical_streams_1b_impl.php

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
$e->setDtstart( '20050411T100000 Eastern' );
$e->setDtend( '20050411T110000 Eastern' );
$e->setTransp( "OPAQUE" );
$e->setRdate( array( array( '20050411T100000', '20050411T110000' )
                   , array( '20050412T100000', '20050412T110000' )
                   , array( '20050413T100000', '20050413T110000' )
                   , array( '20050414T100000', '20050414T110000' )
                   , array( '20050415T100000', '20050415T110000' ))
            , array( 'TZID' => 'Eastern' ));
$e->setComment( 'Reschedule of time only (+ 1 hr)', array( 'ALTREP' => 'CID:<FFFF__=0ABBE548DFFC65378f9e8a93d@coffeebean.com>'));
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
$e->setDescription ( '<!-- something missing -->', array ( 'ALTREP' => 'CID:<FFFE__=0ABBE548DFFC65378f9e8a93d@coffeebean.com>' ));
$e->setOrganizer( 'iCalChair@coffeebean.com', array( 'CN' => 'iCal Chair/CoffeeBean' ));
$e->setSummary( '5 day daily repeating meeting' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

