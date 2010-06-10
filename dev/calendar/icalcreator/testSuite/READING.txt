You can run and edit (simple) test files for calendar
properties.

Open 'index.php' in your (local) webserver. Make sure
iCalcreator.class.php points to the right folder.

The testSuite folder must be writable for webserver user.

There are three run options: run-ical, run-xcal, edit.
In edit mode it is an option to save.

All tests can be run with iCal/xCal formatted output
but avoid to run xprop_iCal_test.php, index.php,
phpinfo.php, TZ_iCal_test.php and validDate_iCal_test.php
in xcal mode.

If You select run-ical/run-xcal, You can also select to
display or redirect output in browser.

Run files xprop_iCal_test.php, TZ_iCal_test.php and
validDate_iCal_test.php ONLY in display mode, due to
variable displaying (echo.. .).

Do NOT remove the following lines in the scripts:
echo $str."<br />\n";
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.ics' );
// $c->returnCalendar( FALSE, 'test.ics' );
(auto edited).

Only for testing, evaluating and showing iCal/xCal
property format and output.

Feedback is welcome; ical@kigkonsult.se!!!
