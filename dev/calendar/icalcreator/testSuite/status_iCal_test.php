<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';
/*
     statvalue  = "TENTATIVE"           ;Indicates event is tentative.
                / "CONFIRMED"           ;Indicates event is definite.
                / "CANCELLED"           ;Indicates event was cancelled.
        ;Status values for a "VEVENT"

     statvalue  =/ "NEEDS-ACTION"       ;Indicates to-do needs action.
                / "COMPLETED"           ;Indicates to-do completed.
                / "IN-PROCESS"          ;Indicates to-do in process of
                / "CANCELLED"           ;Indicates to-do was cancelled.
        ;Status values for "VTODO".

     statvalue  =/ "DRAFT"              ;Indicates journal is draft.
                / "FINAL"               ;Indicates journal is final.
                / "CANCELLED"           ;Indicates journal is removed.
        ;Status values for "VJOURNAL".
*/
$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setStatus( "CONFIRMED" );
$c->addComponent( $e );

$e = new vevent();
$e->setStatus( "FINAL", array ('final' => 'countdown' ));
$c->addComponent( $e );

$e = new vevent();
$e->setStatus( "DRAFT", array ('RFC' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>