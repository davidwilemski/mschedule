<?php // TZ_iCal_test.php

require_once '../iCalcreator.class.php';

echo "12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";

$tpl = "
     BEGIN:VTIMEZONE
     TZID:US-Eastern
     LAST-MODIFIED:19870101T000000Z
     BEGIN:STANDARD
     DTSTART:19971026T020000
     RDATE:19971026T020000
     TZOFFSETFROM:-0400
     TZOFFSETTO:-0500
     TZNAME:EST
     END:STANDARD
     BEGIN:DAYLIGHT
     DTSTART:19971026T020000
     RDATE:19970406T020000
     TZOFFSETFROM:-0500
     TZOFFSETTO:-0400
     TZNAME:EDT
     END:DAYLIGHT
     END:VTIMEZONE
     <br />\n\n";
while( 0 < substr_count( $tpl, '  '))
  $tpl = str_replace('  ', ' ', $tpl );
$tpl = str_replace(',', ",\n", $tpl );
echo $tpl;

$c = new vcalendar ();

$e = new vtimezone();
$e->setTzid( 'US-Eastern' );
$e->setLastModified( '19870101' );

$s = new vtimezone( 'standard' );
$s->setDtstart( '19971026020000' );
$s->setRdate( array( '19971026020000' ));
$s->setTzoffsetfrom( '-0400' );
$s->setTzoffsetto( '-0500' );
$s->setTzname( 'EST' );
$e->addSubComponent( $s );

$d = new vtimezone( 'daylight' );
$d->setDtstart( '19971026020000' );
$d->setRdate( array( '19970406020000' ));
$d->setTzoffsetfrom( '-0500' );
$d->setTzoffsetto( '-0400' );
$d->setTzname( 'EDT' );
$e->addSubComponent( $d );

$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

echo "################################################<br />\n";
$tpl = "
     BEGIN:VTIMEZONE
     TZID:US-Eastern
     LAST-MODIFIED:19870101T000000Z
     TZURL:http://zones.stds_r_us.net/tz/US-Eastern
     BEGIN:STANDARD
     DTSTART:19671029T020000
     RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10
     TZOFFSETFROM:-0400
     TZOFFSETTO:-0500
     TZNAME:EST
     END:STANDARD
     BEGIN:DAYLIGHT
     DTSTART:19870405T020000
     RRULE:FREQ=YEARLY;BYDAY=1SU;BYMONTH=4
     TZOFFSETFROM:-0500
     TZOFFSETTO:-0400
     TZNAME:EDT
     END:DAYLIGHT
     END:VTIMEZONE
     <br />\n\n";
while( 0 < substr_count( $tpl, '  '))
  $tpl = str_replace('  ', ' ', $tpl );
$tpl = str_replace(',', ",\n", $tpl );
echo $tpl;

$c = new vcalendar ();

$e = new vtimezone();
$e->setTzid( 'US-Eastern' );
$e->setLastModified( '19870101T000000' );
$e->setTzurl( 'http://zones.stds_r_us.net/tz/US-Eastern' );

$s = new vtimezone( 'standard' );
$s->setDtstart( '19671029T020000' );
$s->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 10
                   , 'BYday'      => array( -1, 'DAY' => 'SU' )));
$s->setTzoffsetfrom( '-0400' );
$s->setTzoffsetto( '-0500' );
$s->setTzname( 'EST' );
$e->addSubComponent( $s );

$d = new vtimezone( 'daylight' );
$d->setDtstart( '19870405T020000' );
$d->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 4
                   , 'BYday'      => array( 1, 'DAY' => 'SU' )));
$d->setTzoffsetfrom( '-0500' );
$d->setTzoffsetto( '-0400' );
$d->setTzname( 'EDT' );
$e->addSubComponent( $d );

$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";
?>