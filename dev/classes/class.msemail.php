<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}
	
/**
 * A utility class for sending email from MSchedule
 *
 */

class MSEmailAddress
{
	var $email;
	var $name;
	function MSEmailAddress($email,$name='')
	{
		$this->email = $email;
		$this->name = $name;
	}
}

class MSEmail
{
	var $message;
	var $subject;
	var $from;
	var $recipients;
	var $bccRecipients;
	var $useHTML;
	
	function MSEmail($content, $subject, $useHTML = true)
	{
		global $cfg;
		
		//check that content was specified
		if (!$content)
			return false;
		else
			$this->message = $content;
		
		//set the subject
		if ($subject)
			$this->subject = $subject;
		else
			$this->subject = $cfg['email']['defaultSubject'];
		
		//init the recipients array
		$this->recipients = array();
		
		//init the $from var
		$this->from = new MSEmailAddress($cfg['email']['sendmailFrom'], $cfg['email']['sendmailFromName']);
		
		//init the useHTML var
		$this->useHTML = $useHTML;
	}
	
	//adds a recipient to the list
	function add_recipient($email,$name)
	{
		$newRecipient = new MSEmailAddress($email,$name);
		array_push($this->recipients,$newRecipient);
	}
	
	//adds a Bcc recipient to the list
	function add_bcc($email,$name)
	{
		$newRecipient = new MSEmailAddress($email,$name);
		array_push($this->bccRecipients,$newRecipient);
	}
	
	//sends the message
	function send()
	{
		global $cfg, $MSERROR;
		
		//check for recipients and $from source
		if (!$this->recipients || !$this->from)
			return false;
		
		//set sendmail settings
		ini_set('sendmail_from', $cfg['email']['sendmailFrom']);
		//ini_set('SMTP', $cfg['email']['SMTP']);
		
		//set the $to field and To: headers
		foreach ($this->recipients as $key=>$recipient)
			$to .= $recipient->email . ", ";
		
				
		//make HTML headers if we need them
		if ($this->useHTML) {
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		}
		
		//assign the rest of the headers
		$headers .= "To: ".$this->make_header($this->recipients);
		$headers .= "From: ".$this->make_header($this->from);
		$headers .= "Bcc: ".$this->make_header($this->bccRecipients);
		
		//send the mail
		$GLOBALS['MSDEBUG']->add("to:",$to);
		$GLOBALS['MSDEBUG']->add("subject:",$this->subject);
		$GLOBALS['MSDEBUG']->add("message:",$this->message);
		$GLOBALS['MSDEBUG']->add("headers:",$headers);
		$result = mail($to, $this->subject, $this->message, $headers);
		
		//check that email was sent properly
		if(!$result)
			$MSERROR->err("MSEmail::send",_EMAIL_DELIVERYNOTACCEPTED);
		
		return $result;
	}
	
	//make email lists for headers
	function make_header($emailAddresses)
	{
		//check for null input
		if ($emailAddresses == NULL)
			return false;
		
		//check if this is an array or just 1 email
		if (!is_array($emailAddresses))
			$header .= $emailAddresses->name . " <" . $emailAddresses->email . ">";
		else
			foreach ($emailAddresses as $emailAddy)
				$header .= $emailAddy->name . " <" . $emailAddy->email . ">, ";
		
		$header .= "/n";
		
		return $header;
	}
}
?>
