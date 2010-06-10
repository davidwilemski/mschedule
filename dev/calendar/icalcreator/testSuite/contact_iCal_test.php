<?php // created_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );

$e = new vevent();
$e->setComment( "'Jim Dolittle, ABC Industries, +1-919-555-1234'" );
$e->setContact( 'Jim Dolittle, ABC Industries, +1-919-555-1234' );
$c->addComponent( $e );

$e = new vevent();
$e->setComment( "'Jim Dolittle, ABC Industries, +1-919-555-1234'".
                ", array( 'altrep' => ".
                "'ldap://host.com:6666/o=3DABC%20Industries, c=3DUS??(cn=3DBJim%20Dolittle)' )");
$e->setContact( 'Jim Dolittle, ABC Industries, +1-919-555-1234'
               , array( 'altrep' => 'ldap://host.com:6666/o=3DABC%20Industries, c=3DUS??(cn=3DBJim%20Dolittle)' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "'Jim Dolittle, ABC Industries, +1-919-555-1234'".
                ", array( 'altrep' => ".
                  "'ldap://host.com:6666/o=3DABC%20Industries, c=3DUS??(cn=3DBJim%20Dolittle)' )");
$e->setContact( 'Jim Dolittle, ABC Industries, +1-919-555-1234'
               , array( 'altrep' => 'ldap://host.com:6666/o=3DABC%20Industries, c=3DUS??(cn=3DBJim%20Dolittle)' ));
$c->addComponent( $e );

$e = new vevent();
$e->setLanguage( 'no' );
$e->setComment( "setLanguage( 'no' )".
                "'Jim Dolittle, ABC Industries, +1-919-555-1234'".
                ", array( 'altrep'    => 'CID=<part3.msg970930T083000SILVER@host.com>'".
                        ",".            "'oddXparam'".
                        ", 'language'  => 'da'".
                        ", 'xparamKey' => 'xparamvalue' )");
$e->setContact( 'Jim Dolittle, ABC Industries, +1-919-555-1234'
               , array( 'altrep'    => 'CID=<part3.msg970930T083000SILVER@host.com>'
                      ,                'oddXparam'
                      , 'language'  => 'da'
                      , 'xparamKey' => 'xparamvalue' ));
$c->addComponent( $e );

$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

?>