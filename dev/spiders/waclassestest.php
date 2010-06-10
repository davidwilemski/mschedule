<head>
<title>WA scrape test</title>
</head>
<?php

class MSWAScraper
{
	var $ch;	//curl handle
	
	function MSWAScraper()
	{
		$this->ch = curl_init();
		$this->setOptions();
	}
	
	function setOptions()
	{
		$options = array(
			CURLOPT_FAILONERROR => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_FRESH_CONNECT => true,
			CURLOPT_HEADER => true,
			CURLOPT_STDERR => "./err.txt",
			CURLOPT_COOKIEFILE => "C:\cookie.txt",
			CURLOPT_COOKIEJAR => "C:\cookie.txt",
			
			CURLOPT_VERBOSE => true
		);
		print "**".$this->ch."**";
		foreach($options as $option => $value){
			curl_setopt($this->ch, $option, $value);
		}
	}
	
	function get($url)
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);
		//var_dump( curl_getinfo($ch));
		curl_exec($this->ch);
	}
	
	function close()
	{
		curl_close($this->ch);
	}
}
$s = new MSWAScraper();

//$s->get("http://wolverineaccess.umich.edu/index.jsp");
$s->get("http://localhost/mschedule/spiders/class-search.txt");
//$s->get("https://wolverineaccess.umich.edu/heprodnonop/start.jsp");



$s->close();
?>