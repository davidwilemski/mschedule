<?
//has the effect of checking all files in the classes directory for syntaxs errors

$array = array();
if ($handle = opendir('../classes')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != "..") {
	       	if(!is_dir($file)){
           		array_push($array, $file);
       		}
       }
   }
   closedir($handle);
}

print "<pre>";
foreach($array as $file){
	print "$file:\n";
	require_once "../classes/$file";
}

?>