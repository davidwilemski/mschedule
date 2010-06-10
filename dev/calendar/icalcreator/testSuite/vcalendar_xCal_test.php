<?php // completed_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$c->unique_id = 'kigkonsult.se';
$c->setMethod ( 'PUBLISH' );
$c->setCalscale ( 'gregorian' );

$e = new vevent();
$e->setAttach( 'http://doclib.domain.net/lib1234567890/docfile.txt' );
$e->setAttach( 'MIICajCCAdOgAwIBAgICBEUwDQYJKoZIhvcNAQEEBQAwdzELMAkGA1UEBhMCVVMxLDAqBgNVBAoTI05ldHNjYXBlIENvbW11bmljYXRpb25zIE.. .. .', array('FMTTYPE' => 'image/basic', 'ENCODING' => 'BASE64', 'VALUE' => 'BINARY', 'hejsan' ));
$e->setComment( 'kommentar' );
$e->setCategories( "category1, category2", array('hejsan2' => 'tjoflöjt2', 'hoppsan', 'language' => 'se' ));
$e->setCategories( "category3", array('xKey' => 'xValue'));
$e->setDtstart( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 ), array( 'xparamKey' => 'xparamValue' ));
$e->setgeo ( 11.2345, -32.5678, array( 'xparamValue', 'yparamKey' => 'yparamValue' ));
$e->setResources( array( 'Oljekanna, smörjfett', 'trassel' ), array( 'language' => 'se', 'yParam', 'altrep' => 'rundsmörjningsgrejjor' ));
$e->setUrl( 'http://www.icaldomain.net/document.txt', array( 'specification' => 'explanation' ));

$a = new valarm();
$a->setSummary( 'EMAIL alarm subject' );
$a->setDescription( 'EMAIL alarm body' );
$a->setAction( 'EMAIL' );
$a->setTrigger( array( 'year' => 2007, 'month' => 6, 'day' => 5, 'hour' => 2, 'min' => 2, 'sec' => 3, 'tz' => '-0200' ), array( 'xparamKey' => 'xparamValue' ) );
$a->setDuration( array( 'day' => 2, 'hour' => 3, 'sec' => 5 ), array( 'xparamkey' => 'xparamvalue' ));
$a->setRepeat( 2, array( 'xparamKey' => 'xparamValue' ));
$a->setAttach( 'http://alarm.alarmdomain.net/alarmlib/alarm.exe' );
$a->setAttendee( 'someone@internet.com'
                , array( 'cutype'         => 'New York'
                       , 'member'         => array( 'kigsf1@sf.net', 'kigsf2@sf.net', 'kigsf3@sf.net' )
                       , 'role'           => 'CHAIR'
                       , 'PARTSTAT'       => 'ACCEPTED' 
                       , 'RSVP'           => 'TRUE'
                       , 'DELEgated-to'   => array( 'kigsf1@sf.net', 'kigsf2@sf.net', 'kigsf3@sf.net' )
                       , 'delegateD-FROM' => array( 'kigsf1@sf.net', 'kigsf2@sf.net', 'kigsf3@sf.net' ) 
                       , 'SENT-BY'        => 'info@kigkonsult.se'
                       , 'CN'             => 'John Doe'
                       , 'DIR'            => 'http://www.domain.net/doc/info.doc'
                       , 'LANGUAGE'       => 'us-EN'
                       , 'hejsan'         => 'hoppsan'   // xparam
                       ,                     'tjosan'    // also xparam
                  ));
$e->addSubComponent( $a );

$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );

