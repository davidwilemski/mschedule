<?php

class blah
{
	var $memberVar1 = "I'm original member var 1";
	var $memberVar2 = "I'm original member var 2";
	

	function changeMembers1($a, $b)
	{
		$memberVar1 = $a;
		$memberVar2 = $b;
	}

	function changeMembers2($a, $b)
	{
		$this->memberVar1 = $a;
		$this->memberVar2 = $b;
	}
	
	function printMe()
	{
		var_dump($this);
	}
	
	function from1()
	{
		function toAnother($msg)
		{
			print "Inside function:".$msg;
		}
		toAnother("first way worked");
		$this->toAnother("second way worked");
	}
	
	function toAnother($msg)
	{
		print $msg;
	}
}

$foo = new blah;
$foo->printMe();
$foo->changeMembers1("changeMembers1 worked", "yup, it did");
$foo->printMe();
$foo->changeMembers2("changeMembers2 worked", "yarr maty");
$foo->printMe();
$foo->from1();

?>