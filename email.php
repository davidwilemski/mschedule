<?
include_once 'inc/common.php';

getdata(array('name', 'email', 'message'));

if($message != ''){

	if($name == ''){
		$name = "Mschedule User";
	}
	if($email == ''){
		$email = $myaddress;
	}
	
	$from = $name." <".$email.">";

	//send email
	//mail('', $subject, $message, $header);
	//sendemail($message, $from);

	
	$body = "Email has been sent. Thank you.";
	showHTMLPage("Email Sent", $body);
	
}else{
	error("Please type a message. Thanks.");
}

?>