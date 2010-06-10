<?php // recurr_ical_streams_2a_impl.php

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
$e->setDtstart( '20050418T090000 Eastern' );
$e->setDtend( '20050418T100000 Eastern' );
$e->setTransp( 'OPAQUE' );
$e->setRdate( array( array( '20050418T090000', '20050418T100000' )
                   , array( '20050419T090000', '20050419T100000' )
                   , array( '20050420T090000', '20050420T100000' )
                   , array( '20050421T090000', '20050421T100000' )
                   , array( '20050422T090000', '20050422T100000' ))
            , array( 'TZID' => 'Eastern' ));
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
$e->setClass( 'PUBLIC' );
$e->setDescription( 'Body change (update) to the meeting - all instances', array( 'ALTREP'=> 'CID:<FFFF__=0ABBE548DFE235B58f9e8a93d@coffeebean.com>' ));
$e->setSummary( '5 day daily repeating meeting #2' );
$e->setXprop( 'X-LOTUS-UPDATE-SUBJECT', 'Information Update - Description has changed : 5 day daily repeating meeting #2' );
$e->setOrganizer( 'iCalChair@coffeebean.com', array( 'CN' => 'iCal Chair/CoffeeBean' ));
$e->setUid( '6882C1FE92942DA785256FDB006FEE85-Lotus_Notes_Generated' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


?>