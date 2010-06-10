#!/bin/sh
PERL5LIB="$PERL5LIB:/var/www/mschedule/update/myperl/lib"
/var/www/mschedule/update/importmschedule.pl &> /tmp/importmschedule.log && 
/var/www/mschedule/update/importclass.pl &> /tmp/importclass.log && 
/var/www/mschedule/update/updatesections.pl &> /tmp/updatesections.log
