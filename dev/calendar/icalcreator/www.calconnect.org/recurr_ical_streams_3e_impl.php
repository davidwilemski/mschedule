<?php // recurr_ical_streams_3e_impl.php

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
$e->setDtstart( '20050425T090000 Eastern' );
$e->setDtend( '20050425T091500 Eastern' );
$e->setTransp( 'OPAQUE' );
$e->setRdate( array( array( '20050425T090000 Eastern', '20050425T091500' )
                   , array( '20050426T090000 Eastern', '20050426T091500' )
                   , array( '20050427T090000 Eastern', '20050427T091500' )
                   , array( '20050428T090000 Eastern', '20050428T091500' )
                   , array( '20050429T090000 Eastern', '20050429T091500' )));
$e->setComment ( "Set the Start and End Time to be implicit - 9 to 9:15am", array( 'ALTREP' => 'CID:<FFFF__=0ABBE548DFE147488f9e8a93d@coffeebean.com>' ));
$e->setSequence( 3 );
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
$e->setDescription ( 'body', array( 'ALTREP' => 'CID:<FFFE__=0ABBE548DFE147488f9e8a93d@coffeebean.com>'));
$e->setSummary( 'More complicated stream (5 day recurring)' );
$e->setOrganizer( 'iCalChair@coffeebean.com', array( 'CN' => 'iCal Chair/CoffeeBean' ));
$e->setUid( '6BA1ECA4D58B306C85256FDB0071B664-Lotus_Notes_Generated' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


?>