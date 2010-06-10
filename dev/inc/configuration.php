<?php
define("CONFIG_INCLUDED", true);

// Mschedule configuration

// all configuration variables should be put into and taken from this array

error_reporting(E_ALL);

$cfg['language'] = "english";

$cfg['domainName'] = "www.mschedule.com";

$cfg['ms_rootpath']['server'] = "/var/www/mschedule.com/dev";
$cfg['ms_rootpath']['client'] = "http://".$_SERVER["SERVER_NAME"];	

$cfg['defaultTerm'] = "winter05";

$cfg['auth']['type'] = "msauth"; // valid values: cosign, msauth
$cfg['auth']['CoSignRedirect'] = ""; //path to the CoSign section of the website (usually starts with https)

$cfg['useAdminPass'] = true; //if one password will log in any registered user
$cfg['adminPass'] = "7cMSAbBL475DKcNy"; //password which will login any user
$cfg['adminUsers'] = array('mulka','mjpizz'); //array with uniqnames of administrators

$cfg['ldap']['host'] = 'ldap.itd.umich.edu';
$cfg['ldap']['port'] = '389';
$cfg['ldap']['defaultDomain'] = 'umich.edu';
$cfg['defaultDomain'] = "umich.edu"; //the domain that mschedule is servicing


//information about mschedule's backend database
$cfg['db']['host'] = 'localhost';
$cfg['db']['dbName'] = 'mschedule_dev';
$cfg['db']['username'] = 'mschedule_dev';
$cfg['db']['port'] = '3306';

$cfg['db']['tablePrefix'] = 'mschedule_';

$cfg['db']['password'] = '';

//names of database tables
$cfg['db']['tables']['users'] = 'users';
$cfg['db']['tables']['uniqname_info'] = 'uniqname_info';
$cfg['db']['tables']['user_class'] = 'uniqname_class_'.$cfg['defaultTerm'];
$cfg['db']['tables']['classes'] = 'classes_'.$cfg['defaultTerm'];
$cfg['db']['tables']['users'] = 'users';
$cfg['db']['tables']['error_log'] = 'error_log';
$cfg['db']['tables']['php_errors'] = 'phperror_log';
$cfg['db']['tables']['invites'] = 'invites';
$cfg['db']['tables']['prefs'] = 'preferences';
$cfg['db']['tables']['vips'] = 'vips';
$cfg['db']['tables']['buildings'] = 'buildings';
$cfg['db']['tables']['mapdata'] = 'mapdata';
$cfg['db']['tables']['locations'] = 'locations';

//WA data
$cfg['db']['tables']['wa_subjects'] = 'wa_subjects';
$cfg['db']['tables']['wa_courses'] = 'wa_courses';
$cfg['db']['tables']['wa_sections'] = 'wa_sections';
$cfg['db']['tables']['wa_meetings'] = 'wa_meetings';

foreach($cfg['db']['tables'] as $key => $value){
	$cfg['db']['tables'][$key] = $cfg['db']['tablePrefix'].$value;
}

//error and debug reporting levels
$cfg['showErrors'] = true;
$cfg['showDebug'] = true;
$cfg['hideExceptions'] = false;

//smarty template settings
$cfg['smarty']['template_dir']	= $cfg['ms_rootpath']['server']."/templatedata/templates/";
$cfg['smarty']['compile_dir']	= $cfg['ms_rootpath']['server']."/templatedata/templates_compiled/";
$cfg['smarty']['cache_dir']		= $cfg['ms_rootpath']['server']."/templatedata/templates_cache/";
$cfg['smarty']['config_dir']		= $cfg['ms_rootpath']['server']."/inc/smarty_configs/";
$cfg['smarty']['force_compile']	= true;
$cfg['smarty']['overlibPath'] = $cfg['ms_rootpath']['client']."/javascripts/overlib400/overlib.js";

//miscellaneous paths
$cfg['needloginRedirect'] = $cfg['ms_rootpath']['client']."/register2.php";
$cfg['loginRedirect'] = '';
$cfg['logoutRedirect'] = 'index.php';

//mapping
$cfg['maps']['names'] = array("north", "medical", "central", "south");
$cfg['maps']['ext'] = "gif";
$cfg['maps']['path'] = $cfg['ms_rootpath']['client']."/images/maps";

//user account settings
$cfg['usernameLength'] = 8;
$cfg['passwordLength'] = 16;

//administrative overrides
$cfg['override']['noLoginBar'] = true;
$cfg['stopSearchEngineIndexing'] = true;
?>
