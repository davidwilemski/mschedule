<?php /* rdate_iCal_test.php */

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );


/* $rdate1 = array ( 2001, 1, 1, 1, 1, 1 ); */
/* alt. */
$rdate1 = array ( 'year' => 2001, 'month' => 1, 'day' => 1, 'hour' => 1, 'min' => 1, 'sec' => 1, 'tz' => '-0200' );
$rdate2 = array ( 2002, 2, 2, 2, 2, 2, '-0200' );
$rdate3 = '3 March 2003 03.03.03';
$rdate4 = array ( 2004, 4, 4, 4, 4, 4, 'GMT' );
$rdate5 = array ( 2005, 10, 5, 5, 5, 5 );
$rdur6 = array ( 'week' => 0, 'day' => 0, 'hour' => 5, 'min' => 5, 'sec' => 5 );
$rdur7 = array ( 0, 0, 6 );
 /* duration for 6 hours */
$rdate8 = array ( 'year' => 2007, 'month' => 7, 'day' => 7 );
$timestamp = mktime( 9, 9, 9, 9, date('d'), date('Y'));
$rdate9    = array( 'timestamp' => $timestamp );
$timestamp = mktime( 10, 10, 10, 10, date('d'), date('Y') + 1 );
$rdat10    = array( 'timestamp' => $timestamp, 'tz' => '+0100' );

$vevent = new vevent();
$vevent->setComment( implode( '-',$rdate1 ));
   /* one recurrence date, date in 3-params format */
$vevent->setComment( implode( '  ', array( implode('-',$rdate1), implode('-',$rdate2 ))));
   /* two dates, date 7-params format */
$vevent->setComment( implode('  ', array(
  implode('/',array( implode( '-',$rdate1), implode('-',$rdate5 )))
 /* Both fromdate and tomdate must have 7 params !!! */
, implode('/',array( implode( '-',$rdate2), implode( '-',$rdur6 )))
   /* duration */
, implode('/',array( $rdate3, implode( '-',$rdur7 )))
   /* period, pairs of fromdate <-> tom -date/-duration */
, implode('/',array( implode( '-',$rdate4), implode( '-',$rdate5 )))
)));
$vevent->setRdate ( array( $rdate1 ));
   /* one recurrence date, date in 3-params format
$vevent->setRdate ( array( $rdate1, $rdate2 ));
   /* two dates, date 7-params format */
$vevent->setRdate ( array(
  array( $rdate1, $rdate5 )
   /* Both fromdate and tomdate must have 7 params !!! */
, array( $rdate2, $rdur6 )
   /* duration */
, array( $rdate3, $rdur7 )
   /* period, pairs of fromdate <-> tom -date/-duration */
, array( $rdate4, $rdate5 )
));
$vevent->setComment( implode( '-',$rdate9 ));
$vevent->setRdate ( array( $rdate9 ));
$vevent->setComment( implode( '-',$rdat10 ));
$vevent->setRdate ( array( $rdat10 ));
$vevent->setComment( implode( '  ', array( implode('-',$rdat10), implode('-',$rdur6 ))));
$vevent->setRdate ( array(
  array( $rdat10, $rdur6 )
));
$c->addComponent( $vevent );


$d1 = array ( 2001, 1, 1, 1, 1, 1 );   
$d2 = array ( 'year' => 2002, 'month' => 2, 'day' => 2, 'hour' => 2, 'min' => 2, 'sec' => 2 ); 
$d3 = array ( 2003, 3, 3, 0, 0, 0, '+0300' ); 
$d4 = array ( 2004, 4, 4 ); 
$d5 = array ( 2005, 5, 5, 5, 5, 5, '-050505' ); 
$da = '5 May 2005 5:5:5'; 
$db =  '5/1/2005 5.2'; 

$d6 = array ( 0, 5, 5, 5, 5 ); 
$d7 = array ( 'week' => 0, 'day' => 0, 'hour' => 5, 'min' => 5, 'sec' => 5 ); 
$d8 = array ( 0, 0, 6, 0 );             /* duration for 6 hours  */
$d9 = array ( 'year' => 2007, 'month' => 7, 'day' => 7, 'tz'=> '+0900' );  
$d0 = array ( 0,1 );             /* duration for 1 day/week */

$e = new vtodo();
$e->setComment( implode('-',$d1 ) );
$e->setRdate( array( $d1 ));
$e->setComment( implode('  ', array( implode('-',$d3), implode('-',$d4))));
$e->setRdate( array( $d3, $d4 ));
$e->setComment( implode('  ', array( implode('-',$d4), implode('-',$d3), implode('-',$d2), implode('-',$d1))));
$e->setRdate( array( $d4, $d3, $d2, $d1 ));
$c->addComponent( $e );

$e = new vtodo();
$e->setComment( implode('  ', array( implode('-',$d3), implode('-',$d4))));
$e->setRdate( array( $d3, $d4 ));
$e->setComment( implode('  ', array( implode('/', array( implode('-',$d9), implode('-',$d8))))));
$e->setRdate( array( array( $d9, $d8 )));
$c->addComponent( $e );


$e = new vtodo();
//$e->setRdate( array( $d2 ));
$e->setComment(implode('  ', array( implode('/', array( $db, implode('-',$d8))))));
$e->setRdate( array( array( $db, $d8 )));
$e->setComment(implode('  ', array( 
  implode('/', array( implode('-',$d1), implode('-',$d0)))
, $db
, $da
, implode('/', array( implode('-',$d2), implode('-',$d7)))
)));
$e->setRdate( array( array( $d1, $d0 )
                   , $db, $da
                   , array( $d2, $d7 )
                    )
             , array( 'xKey' => 'xValue' )
             );
$c->addComponent( $e );
$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>