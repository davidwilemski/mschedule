<?

/*
converts keys in array

goes through convertionArray converting every original key to each final key in the returned array
if discard, throw out elements not addressed by convertionArray
example:
$convertionArray = array(
'original' => 'final',
'original2' => 'final'
);
*/
function convertArray($array, $convertionArray, $discard = true)
{
	if($discard){
		foreach($convertionArray as $key => $value){
			$rv[$value] = $array[$key];
		}
	}else{
		$rv = $array;
		foreach($convertionArray as $key => $value){
			$rv[$value] = $array[$key];
			unset($rv[$key]);
		}
	}
	return $rv;
}