<?

class A
{
	var $output;
	
	function printOut()
	{
		print $this->output;
	}
	
	function set($output)
	{
		print $output;
		$this->output = $output;
	}
}

class B
{
	function yarr()
	{
		A::set("hey");
	}
}

B::yarr();
?>