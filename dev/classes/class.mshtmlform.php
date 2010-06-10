<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

/**
 * A class for form fields.
 *
 */

class MSHTMLFormField
{
	var $label;
	var $name;
	var $type;
	var $value;
	var $maxlength;
	var $size;
	
	function MSHTMLFormField($name,$type,$label='',$maxlength=10,$value='')
	{
		$this->name = $name;
		$this->type = $type;
		$this->label = $label;
		$this->value = $value;
		$this->maxlength = $maxlength;
	}
	
	function set_size($length)
	{
		$this->size = $length;
	}
}

/**
 * A class for forms.
 *
 */
class MSHTMLForm
{
	var $name;
	var $action;
	var $submitString;
	var $authkey;
	//var $hiddenFields;
	//var $textFields;
	var $fields;
	
	//CONSTRUCTOR
	function MSHTMLForm($formName, $formAction, $submitString)
	{
		$this->hiddenFields = array();
		$this->fields = array();
		
		$this->set_name($formName);
		$this->set_action($formAction);
		$this->set_submitString($submitString);
		
		//debugmjp: disabled this for now
		//$this->set_authkey();
	}
	
	//sets the name of this form
	function set_name($formName)
	{
		$this->name = $formName;
	}
	
	//sets the action that this form will have
	function set_action($formAction)
	{
		$this->action = $formAction;
	}
	
	//sets the string that the 'submit' button of this form will have
	function set_submitString($submitString)
	{
		$this->submitString = $submitString;
	}
	
	/*debugmjp: got rid of this...maybe someday do it
	//sets the authorization key for this form, from a session variable
	function set_authkey()
	{
		$session = new MSSessionHandler();
				
		$seed = $session->get_randID();

		$this->authkey = md5($seed);
				
		//add this authorization key to the hidden fields
		$this->add_hiddenField("authkey",$this->authkey);
	}
	*/
	
	//adds a text field to the form
	function add_textField($name, $label, $maxlength, $size=null)
	{
		if ($size==NULL)
			$size = $maxlength;
			
		$field = new MSHTMLFormField($name,"text",$label,$maxlength);
		$field->set_size($size);
				
		array_push($this->fields, $field);
	}
	
	//adds a password field to the form
	function add_passwordField($name, $label, $maxlength, $size=null)
	{
		if ($size==NULL)
			$size = $maxlength;
			
		$field = new MSHTMLFormField($name,"password",$label,$maxlength);
		$field->set_size($size);
				
		array_push($this->fields, $field);
	}
	
	//adds a hidden field to the form
	function add_hiddenField($name, $value)
	{		
		$field = new MSHTMLFormField($name,"hidden",null,null,$value);
		
		array_push($this->hiddenFields, $field);
	}
	
}

?>

