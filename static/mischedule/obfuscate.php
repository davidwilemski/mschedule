<?

function obfuscate($string)
{
	$len = strlen($string);
	$rv = '';
	for($i = 0; $i < $len; $i++)
	{
		$char = substr($string, $i, 1);
		$rv .= "&#".ord($char);
	}
	return $rv;
}
