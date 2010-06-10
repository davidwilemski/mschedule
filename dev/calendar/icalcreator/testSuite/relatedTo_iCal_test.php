<?php // relatedTo_iCal_test.php

require_once '../iCalcreator.class.php';


$c = new vcalendar ();
$c->setFormat( "xcal" );
/* setRelatedTo( string relid [, string reltype ] )
   "PARENT" ( Default") / "CHILD" / "SIBLING / iana-token 
   ; (Some other IANA registered ; iCalendar relationship type) / x-name)
   ; A non-standard, experimental
*/
$e = new vevent();
$e->setRelatedTo( '19960401-080045-4000F192713@host.com' );
$e->setRelatedTo( '19960401-080045-4000F192713@host.com', array( 'reltype' => 'CHILD' ));
$e->setRelatedTo( '19960401-080045-4000F192713@host.com', array( 'yParam' ));
$e->setRelatedTo( '19960401-080045-4000F192713@host.com', array( 'reltype' => 'SIBLING', 'yParam', 'xparamKey' => 'xparamValue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>