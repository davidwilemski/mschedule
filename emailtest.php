<?
include_once 'inc/common.php';

$name = 'Kyle';
$email = 'repalviglator@yahoo.com';
$message = 'yarr';

	if($name == ''){
		$name = "Mschedule User";
	}
	if($email == ''){
		$email = $myaddress;
	}
	
	$from = $name." <".$email.">";

	//mail('', $subject, $message, $header);
print	sendemail($message, $from);

	
