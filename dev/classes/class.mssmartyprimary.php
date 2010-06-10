<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.mssmarty.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mshtmlform.php";

/**
* Class for the PRIMARY display object
*/

class MSSmartyPrimary extends MSSmarty
{
	function MSSmartyPrimary()
	{
		$this->MSSmarty();
	}
	
	//sets the neccessary variables to display the login form
	function set_loginBar()
	{		
		global $cfg, $currentUser;
		
		//let configuration override the showing of the login bar
		if ($cfg['override']['noLoginBar'])
			return false;	
			
		//declare a smarty outputobject
		$smarty = new MSSmarty();
				
		if ($currentUser->is_guest()) {
			
			//give guest users the option to log in
			$loginbar = new MSHTMLForm("loginForm",$cfg['loginRedirect'],_BUTTON_LOGIN);
			
			if ($cfg['auth']['type'] == "msauth") {
				
				//if MSauth is the method of authentication, then we need these fields.  Otherwise, the login action should redirect to the CoSign login anyway
				$loginbar->add_textField("msUsername",_FIELD_USERNAME,$cfg['usernameLength']);
				$loginbar->add_passwordField("msPassword",_FIELD_PASSWORD,$cfg['passwordLength']);
				
				//also offer a "register" option
				$smarty->assign("registerOption",true);
			}
			
			$loginbar->add_hiddenField("msAction","login");
			
		} else {
			
			//give other users the option to log out
			$loginbar = new MSHTMLForm("logoutForm",$cfg['logoutRedirect'],_BUTTON_LOGOUT);
			$loginbar->add_hiddenField("msAction","logout");
		}
		
		//assign the form to the output object
		$smarty->assign("loginbarForm",$loginbar);
		
		//get the output of this menu bar into the smartyprimary
		$output = $smarty->fetch	("elements/loginbar.shtml");
		$this->assign("loginbar",$output);
	}
	
	//sets the neccessary variables to display the left menu column
	function set_menuColumn()
	{
		global $currentUser, $MSDEBUG, $cfg;
		
		//assign the links (debugmjp: activate stuff here when we have it done)
		$links['search'][0] = new MSVOLink(_MENULINKS_COURSE_CATALOG,			"coursecatalog.php",			"system::coursecatalog");
		$links['search'][1] = new MSVOLink(_MENULINKS_COURSE_MAPS,				"coursemaps.php",			"system::coursemaps");
		//$links['search'][2] = new MSVOLink(_MENULINKS_COURSE_RATINGS,			"ratings.php",				"system::ratings");
		//$links['search'][3] = new MSVOLink(_MENULINKS_PEOPLE_IN_MY_COURSES,		"peopleinmycourses.php",		"system::inmyclasses");
		
		$links['buildsched'][0] = new MSVOLink(_MENULINKS_AUTOBUILDER,			"autobuilder.php",			"system::autobuilder");
		//$links['buildsched'][1] = new MSVOLink(_MENULINKS_VISUALBUILDER,			"visualbuilder.php",			"system::visualbuilder");
		$links['buildsched'][2] = new MSVOLink(_MENULINKS_VIEWSAVEDSCHEDS,		"viewsavedscheds.php",		"system::viewsavedscheds");
		
		$links['actions'][0] = new MSVOLink(_MENULINKS_VIEW_SOMEONES_SCHED,		"viewschedule.php",			"system::viewschedule");
		//$links['actions'][1] = new MSVOLink(_MENULINKS_SETUPMEETING,				"setupmeeting.php",			"system::setupmeeting");
		//$links['actions'][2] = new MSVOLink(_MENULINKS_ADD_CALENDAR_DATE,		"addcalendar.php",			"system::addcalendar");
		
		//only create this menu for registered users
		if ($currentUser->is_registered()) {

			$links['mystuff'][0] = new MSVOLink(_MENULINKS_MY_PREFS,				"userprefs.php",				"system::userprefs");
			
			if ($cfg['auth']['type'] == "msauth") {
				$links['mystuff'][1] = new MSVOLink(_MENULINKS_CHANGE_PASSWORD,		"changepass.php",			"system::msauth::changepass");
			}
		}

		//hide certain links from guest users
		foreach ($links as $key1=>$linksubset)
			foreach ($linksubset as $key2=>$link)
				if ($link->readable() == false) {
					$links[$key1][$key2]->class = "membersonly";
					$links[$key1][$key2]->target = $cfg['needloginRedirect'];
				}
		
		
		//assign the links arrays to the menu objects
		$menus['search'] = new MSVOMenu(_MENUTITLE_SEARCH,			$links['search'],		"system::menus::search");
		//$menus['buildsched'] = new MSVOMenu(_MENUTITLE_BUILDSCHED,	$links['buildsched'],	"system::menus::buildsched");
		//$menus['actions'] = new MSVOMenu(_MENUTITLE_ACTIONS,			$links['actions'],		"system::menus::actions");
		//if ($currentUser->is_registered())
		//	$menus['mystuff'] = new MSVOMenu(_MENUTITLE_MYSTUFF,			$links['mystuff'],		"system::menus::mystuff");
		
		//assign the menu array to the output object
		$this->assign("menuboxes",$menus);
	}
	
	//displays the main template
	function render()
	{
		//set up and render the entire thing
		$this->set_menuColumn();			
		$this->set_loginBar();
		
		$this->display("mainlayout.shtml");
	}
}



/*########################################################################################################*/


?>