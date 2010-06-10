<?php
//try to put all color functions here, unless they are huge

//converts rgb array to hex array
function rgb2hex($rgb){
   if(!is_array($rgb) || count($rgb) != 3){
       echo "Argument must be an array with 3 integer elements";
       return false;
   }
   for($i=0;$i<count($rgb);$i++){
       if(strlen($hex[$i] = dechex($rgb[$i])) == 1){
           $hex[$i] = "0".$hex[$i];
       }
   }
   return $hex;
}
?>