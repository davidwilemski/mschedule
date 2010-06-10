<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/db.php";

//interface to "IAA Uniqname Information" @ http://www.ifs.umich.edu/~fceiaa/admininfo.html
//tries to retrieve data from local cache unless told not to
class MSUniqnameInfo
{
	function get_fullName($uniqname)
	{
		$array = $this->get_all($uniqname);
		return $array['fullname'];
	}
	
	function get_umid($uniqname)
	{
		$array = $this->get_all($uniqname);
		return $arary['umid'];
	}
	
	function get_all($uniqname)
	{
		global $MSDB, $cfg;
		
		//check to see if already cached and return if so
		$result = $MSDB->sql("select * from `{$cfg['db']['tables']['uniqname_info']}` where `uniqname` = '$uniqname'");
		if($row = mysql_fetch_assoc($result)){
			return $row;
		}
		
		$this->refreshInfo($uniqname);

		$result = $MSDB->sql("select * from `{$cfg['db']['tables']['uniqname_info']}` where `uniqname` = '$uniqname'");
		if(is_array($array = mysql_fetch_assoc($result))){
			return $array;
		}else{
			return array();
		}
	}
	
	function refreshInfo($uniqname)
	{
		global $MSDB, $cfg, $MSERROR;
		
		//open connection to web page
		$host = 'www.ifs.umich.edu';
		$data = "type=Individual&uniqname=$uniqname";
		
		$fp = fsockopen($host, 80, $errno, $errstr);
		if(!$fp){
			$MSERROR->err("MSUniqnameInfo::refreshInfo()", _ERR_UNIQNAME_INFO_CN." : ".$errno." : ".$errstr);
			return;
		}
		
		fputs($fp, "POST /cgi-bin/uns_cgi HTTP/1.1\r\n");
		fputs($fp, 'Host: '.$host."\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, 'Content-length: '.strlen($data)."\r\n");
		fputs($fp, "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data);
		
		//parse data from web page
		while (!feof($fp)) {
			$line = fgets($fp, 4096);
			$array = explode(':', $line);
			if(stristr($line, 'uniqname:')){
				$uniqname = trim(strip_tags($array[1]));
			}else if(stristr($line, 'Full Name:')){
				$fullname = trim(strip_tags($array[1]));
			}else if(stristr($line, 'Univ ID Card Entity ID:')){
				$umid = str_replace("-", "", trim(strip_tags($array[1])));
			}
		}
		fclose($fp);
		
		//save to cache
		$MSDB->sql("INSERT INTO `{$cfg['db']['tables']['uniqname_info']}` (uniqname,fullname,umid) VALUES ('$uniqname','$fullname','$umid')");
	}
}
