<?php // exrule_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vevent();
  /* freq = "SECONDLY" / "MINUTELY" / "HOURLY" / "DAILY" / "WEEKLY" / "MONTHLY" / "YEARLY" */

$e->setExrule( array( 'FREQ'       => "MINUTELY"
                    , 'UNTIL'      => array( 1, 2, 3 )
                    , 'INTERVAL'   => 2 ));
$e->setExrule( array( 'FREQ'       => "WEEKLY"
                    , 'UNTIL'      => array( 1, 2, 3, 0, 0, 0, '+0200' )
                    , 'BYMONTHDAY' => array( 2, -4, 6 ) ));   // single value/array of values
$e->setExrule( array( 'FREQ'       => "HOURLY"
                    , 'UNTIL'      => array( 1, 2, 3, 4, 5, 6 ))
             , array( 'xparamkey'  => 'xparamValue' ));
$e->setExrule( array( 'FREQ'       => "DAILY"
                    , 'UNTIL'      => array( 'year' => 1, 'month' => 2, 'day' => 3 )
                    , 'BYday'      => array( 'DAY' => 'WE' )));
$e->setExrule( array( 'FREQ'       => "DAILY"
                    , 'UNTIL'      => array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '+0200' )
                    , 'BYday'      => array( 'DAY' => 'WE' )));
$e->setExrule( array( 'FREQ'       => "WEEKLY"
                    , 'UNTIL'      => array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 )
                    , 'BYday'      => array( 5, 'DAY' => 'WE' )            // single value/array of values
 ));
$e->setExrule( array( 'FREQ'       => "MONTHLY"
                    , 'UNTIL'      => '3 Feb 2007'
                    , 'INTERVAL'   => 2
                    , 'WKST'       => 'SU'
                    , 'BYSECOND'   => 2
                    , 'BYMINUTE'   => array( 2, -4, 6 )                    // single value/array of values
                    , 'BYHOUR'     => array( 2, 4, -6 )                    // single value/array of values
                    , 'BYMONTHDAY' => -2                                   // single value/array of values
                    , 'BYYEARDAY'  => 2                                    // single value/array of values
                    , 'BYWEEKNO'   => array( 2, -4, 6 )                    // single value/array of values
                    , 'BYMONTH'    => 2                                    // single value/array of values
                    , 'BYSETPOS'   => array( 2, -4, 6 )                    // single value/array of values
                    , 'BYday'      => array( array( -2, 'DAY' => 'WE' )    // array of values
                                           , array(  3, 'DAY' => 'TH' )
                                           , array(  5, 'DAY' => 'FR' )
                                           ,            'DAY' => 'SA'
                                           , array(     'DAY' => 'SU' ))
                    , 'X-NAME'     => 'x-value' )
             , array( 'xparamkey'  => 'xparamValue' 
                    ,                 'yParamValue' ));
$e->setExrule( array( 'FREQ'       => "YEARLY"
                    , 'COUNT'      => 2
                    , 'INTERVAL'   => 2
                    , 'WKST'       => 'SU'
                    , 'BYSECOND'   => array( -2, 4, 6 )                    // single value/array of values
                    , 'BYMINUTE'   => -2                                   // single value/array of values
                    , 'BYHOUR'     => 2                                    // single value/array of values
                    , 'BYMONTHDAY' => array( 2, -4, 6 )                    // single value/array of values
                    , 'BYYEARDAY'  => array( -2, 4, 6 )                    // single value/array of values
                    , 'BYWEEKNO'   => -2                                   // single value/array of values
                    , 'BYMONTH'    => array( 2, 4, -6 )                    // single value/array of values
                    , 'BYSETPOS'   => -2                                   // single value/array of values
                    , 'BYday'      => array( 5, 'DAY' => 'MO' )            // single value array/array of value arrays
                    , 'X-NAME'     => 'x-value'));
$c->addComponent( $e );

$e = new vevent();
$e->setComment ( 'every last Monday in month until (timestamp =) now(Ymd only) + 7 month' );
  /* freq = "SECONDLY" / "MINUTELY" / "HOURLY" / "DAILY" / "WEEKLY" / "MONTHLY" / "YEARLY" */
$timestamp = mktime ( 0, 0, 0, date('m') + 7, date('d'), date('Y'));
$e->setExrule( array( 'FREQ'       => "MONTHLY"
                    , 'UNTIL'      => array( 'timestamp' => $timestamp )
                    , 'BYday'      => array( -1, 'DAY' => 'MO' )));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
