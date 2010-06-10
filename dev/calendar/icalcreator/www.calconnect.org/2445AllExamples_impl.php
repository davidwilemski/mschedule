<?php // 2445AllExamples_impl.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setCalscale( 'GREGORIAN' );
$c->setMethod( 'PUBLISH' );

$t = new vtimezone();
$t->setTzid( 'US-Eastern' );
$t->setLastModified( '20040110T032845Z' );

$d = new vtimezone( 'daylight' );
$d->setDtstart( '19900404T010000' );
$d->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 4 
                   , 'BYday'      => array( 1, 'DAY' => 'SU' )));
$d->setTzoffsetfrom( '-0500' );
$d->setTzoffsetto( '-0400' );
$d->setTzname( 'EDT' );
$t->addSubComponent( $d );

$s = new vtimezone( 'standard' );
$s->setDtstart( '19901026T060000' );
$s->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 10 
                   , 'BYday'      => array( -1, 'DAY' => 'SU' )));
$s->setTzoffsetfrom( '-0400' );
$s->setTzoffsetto( '-0500' );
$s->setTzname( 'EST' );
$t->addSubComponent( $s );

$c->addComponent( $t );

$e = new vevent();
$e->setDescription( 'Daily for 10 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'COUNT'      => 10 ));
$e->setSummary( 'RExample01' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Daily until Dec, 24 1997' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'UNTIL'      => '19971224T000000Z' ));
$e->setSummary( 'RExample02' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other day - forever:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'INTERVAL'   => 2 ));
$e->setSummary( 'RExample03' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 10 days, 5 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'COUNT'      => 5
                   , 'INTERVAL'   => 10 ));
$e->setSummary( 'RExample04' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Everyday in January, for 3 years:' );
$e->setDtstart( '19980101T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'UNTIL'      => '20000131T090000Z'
                   , 'BYMONTH'    => 1
                   , 'BYday'      => array( array( 'DAY' => 'SU' )
                                          , array( 'DAY' => 'MO' )
                                          , array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'WE' )
                                          , array( 'DAY' => 'TH' )
                                          , array( 'DAY' => 'FR' )
                                          , array( 'DAY' => 'SA' ))));
$e->setSummary( 'RExample05a' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Everyday in January, for 3 years:' );
$e->setDtstart( '19980101T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'UNTIL'      => '20000131T090000Z'
                   , 'BYMONTH'    => 1 ));
$e->setSummary( 'RExample05b' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Weekly for 10 occurrences' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'COUNT'      => 10 ));
$e->setSummary( 'RExample06' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Weekly until December 24, 1997' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'UNTIL'      => '19971224T000000Z' ));
$e->setSummary( 'RExample07' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other week - forever:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'WKST'       => 'SU' ));
$e->setSummary( 'RExample08' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Weekly on Tuesday and Thursday for 5 weeks:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'UNTIL'      => '20000131T090000Z'
                   , 'WKST'       => 'SU'
                   , 'BYday'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'TH' ))));
$e->setSummary( 'RExample09a' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Weekly on Tuesday and Thursday for 5 weeks:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'COUNT'      => 10
                   , 'WKST'       => 'SU'
                   , 'BYday'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'TH' ))));
$e->setSummary( 'RExample09b' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other week on Monday, Wednesday and Friday until December 24,1997, but starting on Tuesday, September 2, 1997:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'UNTIL'      => '19971224T000000Z'
                   , 'INTERVAL'   => 2
                   , 'WKST'       => 'SU'
                   , 'BYday'      => array( array( 'DAY' => 'MO' )
                                          , array( 'DAY' => 'WE' )
                                          , array( 'DAY' => 'FR' ))));
$e->setSummary( 'RExample10' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other week on Tuesday and Thursday, for 8 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'COUNT'      => 8
                   , 'INTERVAL'   => 2
                   , 'WKST'       => 'SU'
                   , 'BYday'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'TH' ))));
$e->setSummary( 'RExample11' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the 1st Friday for ten occurrences:' );
$e->setDtstart( '19970905T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 10
                   , 'BYday'      => array( 1, 'DAY' => 'FR' )));
$e->setSummary( 'RExample12' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the 1st Friday until December 24, 1997:' );
$e->setDtstart( '19970905T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'MONTHLY'    => '19971224T000000Z'
                   , 'BYday'      => array( 1, 'DAY' => 'FR' )));
$e->setSummary( 'RExample13' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other month on the 1st and last Sunday of the month for 10occurrences:' );
$e->setDtstart( '19970907T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 10
                   , 'INTERVAL'   => 2
                   , 'BYday'      => array( array(  1, 'DAY' => 'SU' )
                                          , array( -1, 'DAY' => 'SU' ))));
$e->setSummary( 'RExample14' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the second to last Monday of the month for 6 months:' );
$e->setDtstart( '19970922T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 6
                   , 'BYday'      => array( -2, 'DAY' => 'MO' )));
$e->setSummary( 'RExample15' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the third to the last day of the month, forever:' );
$e->setDtstart( '19970928T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => -3 ));
$e->setSummary( 'RExample16' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the 2nd and 15th of the month for 10 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 10
                   , 'BYMONTHDAY' => array( 2, 15 )));
$e->setSummary( 'RExample17' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monthly on the first and last day of the month for 10 occurrences:' );
$e->setDtstart( '19970930T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 10
                   , 'BYMONTHDAY' => array( 1, -1 )));
$e->setSummary( 'RExample18' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 18 months on the 10th thru 15th of the month for 10 occurrences:' );
$e->setDtstart( '19970910T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 10
                   , 'INTERVAL'   => 18
                   , 'BYMONTHDAY' => array( 10, 11, 12, 13, 14, 15 )));
$e->setSummary( 'RExample19' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every Tuesday, every other month:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'INTERVAL'   => 2
                   , 'BYday'      => array( 'DAY' => 'TU' )));
$e->setSummary( 'RExample20' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Yearly in June and July for 10 occurrences:' );
$e->setDtstart( '19970610T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'COUNT'      => 10
                   , 'BYMONTH'    => array( 6, 7 )));
$e->setSummary( 'RExample21' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every other year on January, February, and March for 10 occurrences:' );
$e->setDtstart( '19970610T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'COUNT'      => 10
                   , 'INTERVAL'   => 2
                   , 'BYMONTH'    => array( 1, 2, 3 )));
$e->setSummary( 'RExample22' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 3rd year on the 1st, 100th and 200th day for 10 occurrences:' );
$e->setDtstart( '19970610T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'COUNT'      => 10
                   , 'INTERVAL'   => 3
                   , 'BYYEARDAY'  => array( 1, 100, 200 )));
$e->setSummary( 'RExample23' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 20th Monday of the year, forever:' );
$e->setDtstart( '19970519T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYDAY'      => array( 20, 'DAY' => 'MO' )));
$e->setSummary( 'RExample24' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Monday of week number 20 (where the default start of the week isMonday), forever:' );
$e->setDtstart( '19970512T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYWEEKNO'   => 20
                   , 'BYDAY'      => array( 'DAY' => 'MO' )));
$e->setSummary( 'RExample25' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every Thursday in March, forever:' );
$e->setDtstart( '19970313T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => 3
                   , 'BYDAY'      => array( 'DAY' => 'TH' )));
$e->setSummary( 'RExample26' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every Thursday, but only during June, July, and August, forever:' );
$e->setDtstart( '19970605T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'BYMONTH'    => array( 6, 7, 8 )
                   , 'BYDAY'      => array( 'DAY' => 'TH' )));
$e->setSummary( 'RExample27' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every Friday the 13th, forever:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setExdate( array( '19970902T090000 US-Eastern' ));
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => 13
                   , 'BYDAY'      => array( 'DAY' => 'FR' )));
$e->setSummary( 'RExample28' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'The first Saturday that follows the first Sunday of the month, forever:' );
$e->setDtstart( '19970913T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYMONTHDAY' => array( 7, 8, 9, 19, 11, 12, 13 )
                   , 'BYDAY'      => array( 'DAY' => 'SA' )));
$e->setSummary( 'RExample29' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every four years, the first Tuesday after a Monday in November,forever (U.S. Presidential Election day):' );
$e->setDtstart( '19961105T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "YEARLY"
                   , 'INVERVAL'   => 4
                   , 'BYMONTH'    => 11
                   , 'BYMONTHDAY' => array( 2, 3, 4, 5, 6, 7, 8 )
                   , 'BYDAY'      => array( 'DAY' => 'TU' )));
$e->setSummary( 'RExample30' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'The 3rd instance into the month of one of Tuesday, Wednesday orThursday, for the next 3 months:' );
$e->setDtstart( '19970904T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'COUNT'      => 3
                   , 'BYDAY'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'WE' )
                                          , array( 'DAY' => 'TH' ))
                   , 'BYSETPOS'   => 3 ));
$e->setSummary( 'RExample31' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'The 2nd to last weekday of the month:' );
$e->setDtstart( '19970929T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MONTHLY"
                   , 'BYDAY'      => array( array( 'DAY' => 'MO' )
                                          , array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'WE' )
                                          , array( 'DAY' => 'TH' )
                                          , array( 'DAY' => 'FR' ))
                   , 'BYSETPOS'   => -2 ));
$e->setSummary( 'RExample32' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 3 hours from 9:00 AM to 5:00 PM on a specific day:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "HOURLY"
                   , 'UNTIL'      => '19970902T170000Z'
                   , 'INTERVAL'   => 3 ));
$e->setSummary( 'RExample33' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 15 minutes for 6 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MINUTELY"
                   , 'COUNT'      => 6
                   , 'INTERVAL'   => 15 ));
$e->setSummary( 'RExample34' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every hour and a half for 4 occurrences:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MINUTELY"
                   , 'COUNT'      => 4
                   , 'INTERVAL'   => 90 ));
$e->setSummary( 'RExample35' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 20 minutes from 9:00 AM to 4:40 PM every day:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "DAILY"
                   , 'BYMINUTE'   => array( 0, 20, 40 )
                   , 'BYHOUR'     => array( 9, 10, 11, 12, 13, 14, 15, 16 )));
$e->setSummary( 'RExample36a' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'Every 20 minutes from 9:00 AM to 4:40 PM every day:' );
$e->setDtstart( '19970902T090000 US-Eastern' );
$e->setRrule( array( 'FREQ'       => "MINUTELY"
                   , 'INTERVAL'   => 20
                   , 'BYHOUR'     => array( 9, 10, 11, 12, 13, 14, 15, 16 )));
$e->setSummary( 'RExample36b' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'An example where the days generated makes a difference because of WKST:' );
$e->setDtstart( '19970805' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'COUNT'      => 4
                   , 'INTERVAL'   => 2
                   , 'BYDAY'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'SU' ))));
$e->setSummary( 'RExample37a' );
$c->addComponent( $e );

$e = new vevent();
$e->setDescription( 'changing only WKST from MO to SU, yields different results...' );
$e->setDtstart( '19970805' );
$e->setRrule( array( 'FREQ'       => "WEEKLY"
                   , 'COUNT'      => 4
                   , 'INTERVAL'   => 2
                   , 'WKST'       => 'SU'
                   , 'BYDAY'      => array( array( 'DAY' => 'TU' )
                                          , array( 'DAY' => 'SU' ))));
$e->setSummary( 'RExample37b' );
$c->addComponent( $e );

$str = $c->createCalendar();
echo $str."<br />\n";

?>