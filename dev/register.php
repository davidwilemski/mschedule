<?php
require_once "inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msemail.php";

//create output and input object
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$input = new MSInputGrabber();


//debugmjp:testing MSemail
/*
$email = new MSEmail("this is an email","this is a subject",false);
$email->add_recipient("matteo7@comcast.net","MattP");
if (!$email->send())
	var_dump($email);

if(0){
ini_set('sendmail_from', $cfg['email']['sendmailFrom']);
$to .= "matteo7@comcast.net";

$subject = "test email";

$message = "stupid shit";

$headers .= "To: MattP <matteo7@comcast.net>\n";
$headers .= "From: MSchedule <schedulesharing@umich.edu>\n";

mail($to, $subject, $message, $headers);
}
*/

//#################################################################################################
//if we got good input, do the registration
if ($input->postVar("msAction")=="msCreateUser") {
	
	//create a random password
	$pass = mkPasswd();
	$username = $input->postVar("uniqname");
	$domain = $cfg['defaultDomain'];

	$GLOBALS['MSDEBUG']->add("password",$pass);
	$GLOBALS['MSDEBUG']->add("username",$input->postVar("uniqname"));

	//create user
	$success = $MSAUTH->create($username,$domain,$pass);
	
	if ($success) {
		
		//email notification
		//debugmjp
		/*
		$emailSmarty = new MSSmarty();
		$message = "blah lbah you registered with this";
		$emailSmarty->assign("message",$message);
		$emailContents = $emailSmarty->fetch("email.shtml");
		$email = new MSEmail($username."@".$domain,$emailContents);
		$email->send();
		*/
		
		//assign the smarty vars
		$smarty->assign("messageTitle",_REGISTRATION_WAS_SUCCESSFUL);
		$smarty->assign("messageContent",_BLOCKTEXT_REGISTER_SUCCESS);
	
	} else {
		
		//email notification
		//debugmjp
		
		//assign the smarty vars
		$smarty->assign("messageTitle",_REGISTRATION_WAS_FAILURE	);
		$smarty->assign("messageContent",_BLOCKTEXT_REGISTER_FAILURE);
	}
			
	//generate the contents of the page
	$content = $smarty->fetch("register_action.shtml");

//#################################################################################################
//otherwise, give registration form
} else {

	//generate the formvars
	$regForm = new MSHTMLForm("regNewUser", "register.php",_BUTTON_REGISTER);

	$regForm->add_textField("uniqname",_FIELD_UNIQNAME,8);
	$regForm->add_hiddenField("msAction","msCreateUser");

	//assign the form to the smarty
	$smarty->assign("regForm",$regForm);
	
	//generate the contents of the page
	$content = $smarty->fetch("register_start.shtml");
}




//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle",_TITLE_REGISTER);
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();


//#######################
//FUNCTIONS
function mkPasswd() //copied from http://www.blueroo.net/max/pwdgen.php
{
	$consts='bcdgjlmnprst';
	$vowels='aeiou';
	
	for ($x=0; $x < 6; $x++) {
		mt_srand ((double) microtime() * 1000000);
		$const[$x] = substr($consts,mt_rand(0,strlen($consts)-1),1);
		$vow[$x] = substr($vowels,mt_rand(0,strlen($vowels)-1),1);
	}
	
	return $const[0] . $vow[0] .$const[2] . $const[1] . $vow[1] . $const[3] . $vow[3] . $const[4];
}

?>
