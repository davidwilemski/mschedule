<?php // organizer_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vevent();
$e->setComment( "'jsmith@host1.com'" );
$e->setOrganizer( 'jsmith@host1.com' );
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'jsmith@host1.com', array( 'xparamKey' => 'xparamValue', 'yParam' )" );
$e->setOrganizer( 'jsmith@host1.com', array( 'xparamKey' => 'xparamValue', 'yParam' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'jsmith@host1.com', array( 'CN' => 'John Smith', 'xparamKey' => 'xparamValue', 'yParam' )" );
$e->setOrganizer( 'jsmith@host1.com', array( 'CN' => 'John Smith', 'xparamKey' => 'xparamValue', 'yParam' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'jsmith@host1.com', array( 'language' => 'se', 'CN' => 'John Smith' )" );
$e->setOrganizer( 'jsmith@host1.com', array( 'language' => 'se', 'CN' => 'John Smith' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'jsmith@host1.com', array( 'language' => 'se', 'CN' => 'John Smith', 'DIR' => 'ldap://host.com:6666/o=3DDC%20Associates,c=3DUS??(cn=3DJohn%20Smith)', 'SENT-BY' => 'info@host1.com', 'xparamKey' => 'xparamValue', 'yParam' )" );
$e->setOrganizer( 'jsmith@host1.com', array( 'language' => 'se', 'CN' => 'John Smith', 'DIR' => 'ldap://host.com:6666/o=3DDC%20Associates,c=3DUS??(cn=3DJohn%20Smith)', 'SENT-BY' => 'info@host1.com', 'xparamKey' => 'xparamValue', 'yParam' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );
