<?php // miniopusecasesv1.1_impl.php

require_once '../iCalcreator.class.php';

echo "12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";



/*     ################################################## */
echo  "1.1 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();
$c->setMethod( 'REQUEST' );

$e = new vevent();
$e->setDtstart( '20060928T090000 CEST' );
$e->setDtend( '20060928T100000 CEST' );
$e->setTransp( 'OPAQUE' );
$e->setDescription ( "Let's play tennis next Wednesday" );
$e->setAttendee( 'player1@tennis.org'
                , array( 'ROLE'           => 'CHAIR'
                       , 'PARTSTAT'       => 'ACCEPTED' 
                       , 'RSVP'           => 'FALSE'
                       , 'CN'             => 'player 1/tennis'));
$e->setAttendee( 'player2@tennis.org'
                , array( 'ROLE'           => 'REQ-PARTICIPANT'
                       , 'PARTSTAT'       => 'NEEDS-ACTION' 
                       , 'RSVP'           => 'TRUE'
                       , 'CN'             => 'player 2/tennis'));
$e->setClass( 'PUBLIC' );
$e->setOrganizer( 'player1@tennis.org' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.2 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T140000 CEST' );
$e->setDescription ( "At 2 pm I need to take my pills." );
$e->setClass( 'PRIVATE' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.2 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T183000 CEST' );
$e->setDescription ( "Party at my house starting at 6:30 pm." );
$e->setClass( 'PUBLIC' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.2 Example #3                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20051214T190000 CEST' );
$e->setDescription ( "Rolling Stones, Red Rocks Ampitheatre, 12/14/05, 7:00 pm" );
$e->setLocation ( 'Red Rocks Ampitheatre' );
$e->setClass( 'PUBLIC' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.2 Example #4                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T153000 CEST' );
$e->setDescription ( "Leave at 3:30 pm to go pickup the kids." );
$e->setClass( 'PRIVATE' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.2 Example #5                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T150000 CEST' );
$e->setDescription ( " A reminder that I need to turn in a project report at 3pm" );
$e->setClass( 'PRIVATE' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "1.3 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T150000' );
$e->setDtend( '20060928T160000' );
$e->setDescription ( ". ..a meeting.. ." );

$a = new valarm();
$a->setAction( 'DISPLAY' );
$a->setDescription ( " I want to be reminded 5 minutes before a meeting starts." );
$a->setTrigger( FALSE, FALSE, FALSE, FALSE, FALSE, 5);

$e->addSubComponent( $a );

$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.1 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060928T090000' );
$e->setDtend( '20060928T110000' );
$e->setDescription ( "Class is on Tue/Thu of each week" );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'BYDAY'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'TH' ))));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.1 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060928T090000' );
$e->setDtend( '20060928T110000' );
$e->setDescription ( "Every Wednesday we have a meeting" );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'BYDAY'      => array( 'DAY' => 'WE' )));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.1 Example #3                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060928T090000' );
$e->setDtend( '20060928T110000' );
$e->setDescription ( "Every year on July 4th" );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 7
                   , 'BYMONTHDAY' => 4));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.1 Example #4                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060928T190000' );
$e->setDtend( '20060928T230000' );
$e->setDescription ( "Every 3 Sundays play poker" );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'INTERVAL'   => 3
                   , 'BYDAY'      => array( 'DAY' => 'SU' )));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.1 Example #5                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T110000' );
$e->setDtend( '20060928T111500' );
$e->setDescription ( "Every 4 hours take a 15 min break" );
$e->setRrule( array( 'FREQ'       => "HOURLY"
                   , 'INTERVAL'   => 4));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.2 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060928T110000' );
$e->setDtend( '20060928T150000' );
$e->setDescription ( "Every 3rd Tuesday of the month go to the beach" );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYDAY'      => array( 3, 'DAY' => 'TH' )));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.2 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T000000' );
$e->setDtend( '20060928T235959' );
$e->setDescription ( "The last Friday in November is black Friday" );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 11
                   , 'BYDAY'      => array( -1, 'DAY' => 'FR' )));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.3 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T090000' );
$e->setDescription ( "Pay bills on the 15th of the month." );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => 15 ));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.3 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T090000' );
$e->setDescription ( "Pay day is the last day of the month." );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => -1 ));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.3 Example #3                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
$e->setDtstart( '20060928T090000' );
$e->setDescription ( "Annual report due by end of February every year." );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 2
                   , 'BYMONTHDAY' => -1 ));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.4 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060927T110000' );
$e->setDtend( '20060927T150000' );
$e->setDescription ( "The dates for a lecture series: Tuesday this week, Wednesday next week, & Friday the following week." );
$e->setRdate( array( '20061004', 20061013 ));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


/*     ################################################## */
echo  "2.5 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060927T110000' );
$e->setDtend( '20060927T150000' );
$e->setDescription ( "The 2nd Sunday every 3 months for a small church that only has communion every 3 months." );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'INTERVAL'   => 3
                   , 'BYDAY'      => array( 2, 'DAY' => 'SU' )));
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.5 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060927T110000' );
$e->setDtend( '20060927T150000' );
$e->setDescription ( "The 1st day of every other month" );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => 1 )); // ?? every other month ??
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.6 Example #1                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060927T110000' );
$e->setDtend( '20060927T150000' );
$e->setDescription ( "Last Friday every month except November" );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTH'    => array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12 )
                   , 'BYDAY'      => array( -1, 'DAY' => 'FR' ))); 
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.6 Example #2                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060927T110000' );
$e->setDtend( '20060927T150000' );
$e->setDescription ( "Meeting on Mondays January through March except for Monday holidays." );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'UNTIL'      => '20060331'
                   , 'BYMONTH'    => array( 1, 2, 3 )
                   , 'BYDAY'      => array( 'DAY' => 'MO' ))); 
$e->setExdate( array( '20060109' )); // ?? holiday.. . !!
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";


/*     ################################################## */
echo  "2.6 Example #3                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060101T110000' );
$e->setDtend( '20060101T150000' );
$e->setDescription ( "Moving a meeting. We have a status meeting every Monday except next Monday is Labor Day, so we'll have to move that meeting to Tuesday." );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'BYDAY'      => array( 'DAY' => 'MO' ))); 
$e->setExdate( array( '20060401' )); // ?? Labor Day.. . !!
$c->addComponent( $e );

$e = new vevent();
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
$e->setDtstart( '20060404T110000' );
$e->setDtend( '20060404T150000' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

/*     ################################################## */
echo  "2.6 Example #4                             <br />\n";
/*     ################################################## */
$c = new vcalendar ();

$e = new vevent();
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
$e->setDtstart( '20060101T110000' );
$e->setDtend( '20060101T150000' );
$e->setDescription ( "Meeting every 5 weeks on Thursday plus next Wednesday." );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'INTERVAL'   => 5
                   , 'BYDAY'      => array( 'DAY' => 'TH' ))); 
$c->addComponent( $e );

$e = new vevent();
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
$e->setDtstart( '20060108T110000' );
$e->setDtend( '20060108T50000' );
$e->setDescription ( "Meeting every 5 weeks on Thursday plus next Wednesday." );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'INTERVAL'   => 5
                   , 'BYDAY'      => array( 'DAY' => 'WE' ))); 
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

?>