<?php
$action  = isset($_REQUEST['action'])  ? $_REQUEST['action']  : 'run-ical';
$file    = isset($_REQUEST['file'])    ? $_REQUEST['file']    : null;
$output  = isset($_REQUEST['output'])  ? $_REQUEST['output']  : 'display';
$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : null;
$message = isset($_REQUEST['message']) ? $_REQUEST['message'] : null;
//if( '0' != ini_get ( 'magic_quotes_gpc' ))
//  $content = stripcslashes ( $content );

$files = array( 0 => '   ' );
if ($dir = @opendir( '.' )) {
  while( FALSE !== ( $theFile = readdir( $dir ))) {
    if(( '.' == $theFile ) || ( '..' == $theFile ))
      continue;
    if( 'index.php' == $theFile )
      continue;
    if(( 'ics' != substr( $theFile, -3 )) &&
       ( 'php' != substr( $theFile, -3 )))
      continue;
    $files[] = $theFile;
  }
  closedir($dir);
}
asort( $files );
$files[0] = 'SELECT FILE';

if ( 'save' == $action ) {
  $msg = null;
  if ( !$file )
    $msg = 'No file is selected!';
  if ( 0 < strlen( $content ))
    $msg = 'The file '.$files[$file].' has no content!';
  if ( is_writable( $files[$file] )) {
    if ( !$fp = fopen( $files[$file], 'w+' ))
      $msg = 'Cannot open file ( '.$files[$file].' )';
    if ( !fwrite( $fp, $content ))
      $msg = 'Cannot write to file ( '.$files[$file].' )';
    $msg = 'Success, save content to file ( '.$files[$file].' )';
    fclose($fp);
 }
 else
    $msg = 'The file '.$files[$file].' is not writable';
  $url  = 'index.php?file='.$file.'&action=edit&message='.$msg;
  if (headers_sent()) {
    echo "<script>document.location.href='$url';</script>\n";
  }
  else {
    @ob_end_clean(); // clear output buffer
    header( "Location: $url" );
  }
}
elseif( 'run-' == substr( $action, 0, 4 )) {
  $lines  = file ( $files[$file] );
  $lines2 = array();
  foreach( $lines as $row ) {
    if( "\n" == trim( $row ))
      continue;
    $pos1 = strpos( $row, '->setFormat');        // remove format setting
    $pos2 = strpos( $row, '$str = str_replace'); // remove display adjustment
    if(( $pos1 === FALSE ) && ( $pos2 === FALSE ))
      $lines2[] = $row;
  }
  $content = null;
  foreach( $lines2 as $row ) {
    $pos = strpos( $row, ' = new vcalendar');
    if(( $pos !== false ) && ( 'run-xcal' == $action )) {// if run-xcal, set format
      $content .= $row;
      $content .= substr( $row, 0, $pos ).'->setFormat( "xcal" );'."\n";
      continue;
    }
    if( 'display' != $output ) { // redirect output to browser
      $pos  = strpos( $row, 'echo $str' );
      if(( $pos !== false ) && ( '// ' != substr( $row, 0, 3 )))
        $row = '// '.$row; // add comment-mark if not exist
      $pos  = strpos( $row, '->returnCalendar(' );
      if(( $pos !== false ) && ( '// $' == substr( $row, 0, 4 )))
        $row = substr( $row, 3); // remove comment-mark if exist
    }
    else { // display output
      $pos = strpos( $row, 'echo $str' );
      if( $pos !== false ) {
        if( '// ' == substr( $row, 0, 3 ))
          $row = substr( $row, 3); // remove comment-mark if exist
        if( 'run-xcal' == $action ) { // if run-xcal.. .
          $content .= '$str = str_replace( "<", "&lt;", $str );'."\n";
          $content .= '$str = str_replace( ">", "&gt;", $str );'."\n";
        }
      }
      $pos = strpos( $row, '->returnCalendar(' );
      if(( $pos !== false ) && ( '// $' != substr( $row, 0, 4 )))
        $row = '// '.$row; // add comment-mark if not exist
    }
    $content .= $row;
  }
  while( 0 < ( substr_count( $content, "\n\n" )))
    $content = str_replace( "\n\n", "\n", $content );
  if( 'run-xcal' == $action ) // change output filename
    $content = str_replace( 'test.ics', 'test.xml', $content );
  else
    $content = str_replace( 'test.xml', 'test.ics', $content );
  if( is_writable( $files[$file] ) && ( $fp = fopen( $files[$file], 'w+' ))) {
    fwrite( $fp, $content );
    fclose($fp);
  }
  if( 'display' != $output ) { // redirect output to browser
    header( "Location: ".$files[$file] );
    exit();
  }
}
// echo "action=$action file=$file output=$output message=$message <br />\n"; // test ###
if( $file ) {
  $pos = @strpos( $files[$file], '_');
  if ($pos !== false) {
    $property = substr( $files[$file], 0, $pos );
  }
}
else
  $property = null;
?>
<HTML>
<HEAD>
<TITLE>iCalcreator testsuite - <?echo $property; ?></TITLE>
</HEAD>
<BODY>
<FORM ID="form" NAME="form" ACTION="index.php" METHOD="post">
<TABLE border=0><TR><TD>
<?php
echo "<SELECT NAME='file'>\n";
foreach( $files as $ix => $theFile ) {
  echo '<OPTION VALUE="'.$ix.'"';
  if( $file == $ix )
    echo ' SELECTED="selected"';
  echo '>'.$theFile.'</option>'."\n";
}
echo '</SELECT>';
$actions = array( 'run-ical', 'run-xcal', 'edit' );
if( in_array( $action, array( 'edit', 'save' )))
  $actions[] = 'save';
foreach( $actions as $choice ) {
  echo '<INPUT NAME="action" VALUE="'.$choice.'"';
  if( $choice == $action )
    echo' CHECKED="checked"';
  echo ' TYPE="radio" />'.$choice."&nbsp;\n";
}
?>
<INPUT TYPE="submit" VALUE="submit" />
</TD>
<TD ALIGN="center">
<A TITLE="iCalDictionary, rfc2445 in HTML format" HREF="http://www.kigkonsult.se/iCalcreator/iCalDictionary/index.html" target="_blank">iCalDictionary</a>
</TD>
<TD ALIGN="right">
<A TITLE="using iCalcreator" HREF="http://www.kigkonsult.se/iCalcreator/using.html" TARGET="_blank">using iCalreator</a>
</TD></TR>
<?php
echo '<TR><TD align="right">run&nbsp;and&nbsp;';
echo '<INPUT NAME="output" VALUE="display"';
if( 'display' == $output )
  echo' CHECKED="checked"';
echo ' TYPE="radio" />output=display&nbsp;'."\n";
echo '<INPUT NAME="output" VALUE="redirect"';
if( 'display' != $output )
  echo' CHECKED="checked"';
echo ' TYPE="radio" />output=redirect to browser'."\n";
echo "</TD>\n<TD colspan='2'>\n</TR>";
if( $message ) {
  $text  = $message;
  $color = 'red';
}
elseif( $file ) {
  $text  = @$files[$file];
  $color = 'silver';
}
else
  exit;
echo "<TR><TD WIDTH=\"800px\" COLSPAN=\"3\">\n";
$style = ' font-size: 18px; padding: 5px;';
echo "<H1 STYLE=\"background-color: $color; $style\">".$text."</H1>\n";
switch( $action ) {
  case 'run-ical':
  case 'run-xcal':
    echo '<PRE STYLE="background-color:#ccccff; padding: 5px;">'."\n";
    include_once( @$files[$file] );
    echo "</PRE>\n";
    break;
  default:
  case 'edit':
    $lines = file ( $files[$file] );
    $str   = null;
    $rows  = ( 50 < count( $lines )) ? count( $lines ) : 50;
    $cols  = 0;
    foreach( $lines as $line ) {
      if( $cols < strlen( $line ))
          $cols = strlen( $line );
      $str .= $line;
    }
    $cols = (  80 > $cols ) ?  80 : $cols;
    $cols = ( 100 < $cols ) ? 100 : $cols;
    echo '<TEXTAREA NAME="content" ';
    echo 'STYLE="border: black solid; font: 14px courier; padding: 5px; background-color: #ccccff"';
    echo 'ROWS="'.$rows.'" COLS="'.$cols.'">'."\n";
    echo $str;
    echo "</TEXTAREA>\n";
    break;
}
echo "</TD></TR>\n";
echo "<TR><TD COLSPAN=\"3\">\n";
echo "<H1 STYLE=\"background-color: $color; $style\">".$text."</H1>\n";
echo "</TD></TR>\n";
echo "<TR><TD>";
echo '<A HREF="http://sourceforge.net/tracker/?group_id=174828&atid=870787" target="_blank">Tracker at Sourceforge.net</A> <- Feature Requests - Bugs';
echo "</TD>\n<TD COLSPAN=\"2\" ALIGN=\"right\">";
echo 'Forum -> <A HREF="https://sourceforge.net/forum/?group_id=174828" target="_blank">Discussion at Sourceforge.net</A>';
echo "</TD></TR>\n";
?>
</TABLE>
</FORM>
</BODY>
</HTML>
