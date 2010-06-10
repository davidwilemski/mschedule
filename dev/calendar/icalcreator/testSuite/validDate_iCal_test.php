<?php 
// validDate_iCal_test.php 
// Do NOT run in xcal mode, only for testing function validDate
 /* 
function error_handler($errno, $errstr, $errfile, $errline, $errctx) {
   if ($errno == E_NOTICE && substr($errstr, 0, 17) == "Undefined index: ") return;
   echo "\n error_handler: \terrno=$errno \terrstr=$errstr ";
   echo "\terrfile=$errfile \terrline=$errline <br />\n";
   if ($errno & (E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)) die();
}
error_reporting ( E_ALL ); // test ##############
set_error_handler("error_handler");
 */

require_once '../iCalcreator.class.php';

function disp ( $input, $output=FALSE) {
  echo "input  : ( ";
  if( is_array( $input )) {
    echo "array[".(count($input) - 1)."]";
    foreach( $input as $k => $v )
      echo " $k => $v";
  }
  else
    echo $input;
  if( FALSE === $output )
    echo " ) is <big><b>NOT valid</b></big><br />\n";
  else {
    $str = "output : ( "; 
    foreach( $output as $k => $v )
      $str .= "$k => $v ";
    echo " ) is <big<b>valid</b></big><br />$str )<br />\n";
  }
}
function executeDtstart( $date ) {
  $e=new vevent(); 
  $e->format = 'iCal'; 
  $e->_createFormat(); 
  $e->setDtstart( $date );
  echo $e->createDtstart();
  
}
echo "12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";

echo "<b>_1_</b><br />\n"; 
$vcalendar = new vcalendar();
$vtodo = new vtodo();
$date1 = array( 'year' => 2006, 'month' => 10, 'day' => 10, 'tz' => '+0200' );
$date2 = $vcalendar->validDate( $date1, TRUE );
if( FALSE !== $date2 ) { 
  disp( $date1, $date2 );
  $vtodo->setCompleted( $date2 ); 
  $vtodo->format = 'iCal';
  $vtodo->_createFormat(); 
  echo $vtodo->createCompleted();
}
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################


echo "<b>_2_</b><br />\n"; 
$c = new vcalendar ();
$today = date("M d Y H:i:s"); 
$offset = date( 'Z'); // offset in seconds
if( '-' == substr( $offset, 0, 1 )) {
  $offset = substr( $offset, 1 );
  $sign = '-';
}
else
  $sign = '+';
$offsetHour = $offset / 3600;
$offsetMod = $offset % 3600;
$offsetMin = $offsetMod / 60;
$offsetSec = $offsetMod % 60;
$offset = sprintf( $sign."%02d%02d%02d", $offsetHour, $offsetMin, $offsetSec );
$today .= ' '.$offset; // offset in accepted format, (+/-)HHMMSS (or (+/-)HHMM)
$date = $c->validDate( $today );
disp( $today, $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_3_</b><br />\n"; 
$c = new vcalendar ();
$date = $c->validDate( 1, 2, 3 );
disp( '1, 2, 3', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_4_</b><br />\n"; 
$c = new vcalendar ();
$date = $c->validDate( 1, 22, 3 );
disp( '1, 22, 3', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_5_</b><br />\n"; 
$date = $c->validDate( 1, 2, 3, 4, 5, 6 );
disp( '1, 2, 3, 4, 5, 6', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_6_</b><br />\n"; 
$date = $c->validDate( 1, 2, 3, 8, 0, 0, '-0800' );
disp( "1, 2, 3, 8, 0, 0, '-0800'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_7_</b><br />\n"; 
$date = $c->validDate( 1, 2, 3, 8, 0, 0, '-0800', TRUE );
disp( "1, 2, 3, 8, 0, 0, '-0800', TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_8_</b><br />\n"; 
$date = $c->validDate( 1, 2, 3, 8, 0, 0, '-080000', TRUE );
disp( "1, 2, 3, 8, 0, 0, '-080000', TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_9_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 ) );
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 )", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_10_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-0405') );
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-0405' )", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_11_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-0405'), TRUE );
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-0405' ), TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_12_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-040506'), TRUE );
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 0, 'tz' => '-040506' ), TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_13_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3 ));
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3 )", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_14_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-1100' ));
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-1100' )", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_15_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-1100' ), TRUE);
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-1100' ), TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_16_</b><br />\n"; 
$date = $c->validDate( array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-110011' ), TRUE);
disp( "array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => '-110011' ), TRUE", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_17_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 04:05:06' );
disp( '2001-02-03 04:05:06', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_18_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 04:00:00 +0800' );
disp( '2001-02-03 04:00:00 +0800', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_19_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 04:00:00 +0800', TRUE );
disp( '2001-02-03 04:00:00 +0800, TRUE', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_20_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 04:00:00 +000800', TRUE );
disp( '2001-02-03 04:00:00 +000800, TRUE', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_21_</b><br />\n"; 
$date = $c->validDate( '2001-02-03' );
disp( '2001-02-03', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_21_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 GMT' );
disp( '2001-02-03 GMT', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_22_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 -0200', TRUE );
disp( "'2001-02-03 -0200'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_23_</b><br />\n"; 
$date = $c->validDate( '2001-02-03 -222222', TRUE );
disp( "'2001-02-03 -222222'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_24_</b><br />\n"; 
$date = $c->validDate( '20010203' );
disp( '20010203', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_25_</b><br />\n"; 
$date = $c->validDate( '20010203 0200' );
disp( '20010203 0200', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_27_</b><br />\n"; 
$date = $c->validDate( '20010203 -1000', TRUE );
disp( '20010203 -1000', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_28_</b><br />\n"; 
$date = $c->validDate( '20010203 -100000', TRUE );
disp( '20010203 -100000', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_29_</b><br />\n"; 
$date = $c->validDate( '20010203040506' );
disp( '20010203040506', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_30_</b><br />\n"; 
$date = $c->validDate( '20010203040506', 'US-Eastern' );
disp( "'20010203040506', 'US-Eastern'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_31_</b><br />\n"; 
$date = $c->validDate( '20010203040506 +0100', TRUE );
disp( '20010203040506 +0100', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_32_</b><br />\n"; 
$date = $c->validDate( '20010203040506 +000100', TRUE );
disp( '20010203040506 +000100', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }

echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################
echo "<b>_33_</b><br />\n"; 
$date = $c->validDate( '20010203T040506Z' );
disp( '20010203T040506Z', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_34_</b><br />\n"; 
$date = $c->validDate( '20010203T040506' );
disp( '20010203T040506', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_35_</b><br />\n"; 
$date = $c->validDate( '2001:02:03 04:05:06' );
disp( '2001:02:03 04:05:06', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_36<br />\n"; 
$date = $c->validDate( '20010203 040506' );
disp( '20010203 040506', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "_</b><br />\n"; // ######################################
echo "<b>_37<br />\n"; 
$date = $c->validDate( '3 Feb 2001' );
disp( '3 Feb 2001', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_38_</b><br />\n"; 
$date = $c->validDate( '3 Feb 2001 CEST' );
disp( '3 Feb 2001 CEST', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_39_</b><br />\n"; 
$date = $c->validDate( '3 Feb 2001 CEST', TRUE );
disp( "'3 Feb 2001', 'CEST'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_40_</b><br />\n"; 
$date = $c->validDate( '02/03/2001' );
disp( '02/03/2001', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_41_</b><br />\n"; 
$date = $c->validDate( '02/03/2001 -0300' );
disp( '02/03/2001 -0300', $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_42_</b><br />\n"; 
$date = $c->validDate( '02/03/2001 -0300', TRUE );
disp( "'02/03/2001 -0300'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_43_</b><br />\n"; 
$date = $c->validDate( '02/03/2001 -030030', TRUE );
disp( "'02/03/2001 -030030'", $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo str_pad("<br />\n", 75, "-="); echo "<br />\n"; // ######################################

echo "<b>_44_</b><br />\n"; 
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$date = $c->validDate( array( 'timestamp' => $timestamp ));
disp( $timestamp, $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_45_</b><br />\n"; 
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$date = $c->validDate( array( 'timestamp' => $timestamp, 'tz' => '+010000' ));
disp( $timestamp, $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_46_</b><br />\n"; 
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$date = $c->validDate( array( 'timestamp' => $timestamp, 'tz' => '+010000' ), TRUE );
disp( $timestamp, $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";
echo "<b>_46_</b><br />\n"; 
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$date = $c->validDate( array( 'timestamp' => $timestamp, 'tz' => 'CEST' ) );
disp( $timestamp, $date );
if( FALSE !== $date ) { executeDtstart( $date ); }
echo "<br />\n================================================================<br />\n";

/* */
$d = strtotime ("now"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("now") : '.$d." ($s)<br />\n";

$d = strtotime ("10 September 2000"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("10 September 2000") : '.$d." ($s)<br />\n";

$d = strtotime ("+1 day"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("+1 day") : '.$d." ($s)<br />\n";

$d = strtotime ("+1 week"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("+1 week") : '.$d." ($s)<br />\n";

$d = strtotime ("+1 week 2 days 4 hours 2 seconds"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("+1 week 2 days 4 hours 2 seconds") : '.$d." ($s)<br />\n";

$d = strtotime ("next Thursday"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("next Thursday") : '.$d." ($s)<br />\n";

$d = strtotime ("last Monday"); $s = date( 'Y-m-d H:i:s', $d );
echo 'strtotime ("last Monday") : '.$d." ($s)<br />\n";


echo "<br />\n"; 
echo "LC_ALL=C TZ=UTC0 date<br />\n";
echo "_A_: Y-m-d H:i:s Z  <= Fri Dec 15 19:48:05 UTC 2000 <br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('Fri Dec 15 19:48:05 UTC 2000'));   echo "<br />\n";

echo "<br />\n"; 
echo "_B_: Y-m-d H:i:s <= Fri Dec 15 19:48:05<br />\n";
echo date( 'Y-m-d H:i:s',   strtotime('Fri Dec 15 19:48:05'));            echo "<br />\n";

echo "<br />\n"; 
echo "TZ=UTC0 date +'%Y-%m-%d %H:%M:%SZ'<br />\n";
echo "_C_: Y-m-d H:i:s Z <= 2000-12-15 19:48:05Z<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 19:48:05Z'));           echo "<br />\n";

echo "<br />\n"; 
echo "date --iso-8601=seconds  # a GNU extension<br />\n"; 
echo "_D_: Y-m-d H:i:s Z <= 2000-12-15T11:48:05-0800<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15T11:48:05-0800'));       echo "<br />\n";

echo "<br />\n"; 
echo "date --rfc-822  # a GNU extension<br />\n";
echo ">_E_: Y-m-d H:i:s Z <= Fri, 15 Dec 2000 11:48:05 -0800<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('Fri, 15 Dec 2000 11:48:05 -0800'));echo "<br />\n";

echo "<br />\n"; 
echo "date +'%Y-%m-%d %H:%M:%S %z'  # %z is a GNU extension.<br />\n";
echo "_F_: Y-m-d H:i:s Z <= 2000-12-15 11:48:05 -0800<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 11:48:05 -0800'));      echo "<br />\n";

// string datestring // date in a string, acceptable by strtotime-command, only local time
/**/
?>