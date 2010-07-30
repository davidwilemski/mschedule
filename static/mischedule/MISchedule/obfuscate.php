<?

function obfuscate($string)
{
	$len = strlen($string);
	for($i = 0; $i < $len; $i++)
	{
		$char = substr($string, $i, 1);
		$rv .= "&#".ord($char);
	}
	return $rv;
}