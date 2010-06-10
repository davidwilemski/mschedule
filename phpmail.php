<?php 
error_reporting(E_STRICT);
ini_set('display_startup_errors', true);
//$mail_to="mschedul@mschedule.com, repalviglator@yahoo.com"; 
//$mail_to="repalviglator@yahoo.com"; 
$mail_to="kyle.mulka@gmail.com"; 
$mail_subject="Mschedule Login"; 
//$mail_from="mschedul@mschedule.com"; 
$mail_from="mschedule@umich.edu"; 
$mail_body_client="testing http://www.mschedule.com/view.php?uniqname=mulka";
mail($mail_to,$mail_subject,$mail_body_client,"FROM:". $mail_from); 

exit();

$mail_body_client="To get started with Mschedule.com, click the link below to set your initial password for the site: http://www.google.com/firefox?client=fir
efox-a&rls=org.mozilla:en-US:official";
mail($mail_to,$mail_subject,$mail_body_client,"FROM:". $mail_from); 

$mail_body_client="To get started with Mschedule.com, click the link below to set your initial password for the site: http://www.mschedule.com/confirm.php?uniqname=mattkram&code=165c0e";
mail($mail_to,$mail_subject,$mail_body_client,"FROM:". $mail_from); 
?>
