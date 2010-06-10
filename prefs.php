<?
include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/db.php';

//loads names of fields and valid values
$fields = sql("show fields from $prefs");
//loads displayname from users table
$displayname = mysql_result(sql("select fullname from $users where uniqname = '$auth_uniqname'"), 0, 'fullname');;


$fields_to_skip = array(
'uniqname',
'new_user_in_class_notification',
'friend_join_notification',
'friend_schedule_change_notification',
'blog_update_notification'
);

$pref_field_text = array(
	'privacy' => "Privacy (who can see your schedule)",
	'admin_emails' => "Email Announcements (what type would you like to recieve?)",
	'notification_freq' => "Notification Frequency <br>(How often would you like to be notified about events like: <br>new people in your class, friends joining, friend's schedule change, etc.)",
	'blog_update_notification' => "Blog Update (recieve an email when the blog is updated)"
);

$pref_value_text = array(
	'privacy' => array(
		'public' => "Public (anyone)",
		'protected' => "Protected (only registered users who are logged in)",
		'private' => "Private (only people on your VIP list)"
	),
	'admin_emails' => array(
		'all' => "All (Changes and New Features)",
		'changes' => "Changes",
		'features' => "New Features",
		'none' => "None"
	),
	'notification_freq' => array(
		
	)
); 


getdata(array('submit'));

if($submit == "savepreferences"){
	$set_string = '';
	$first = true;
	while($field_desc_array = mysql_fetch_assoc($fields)){
		
		$field_name = $field_desc_array['Field'];
		
		//skip certain fields
		if(in_array($field_name, $fields_to_skip)){
			continue;
		}
		
		$field_value = strip_tags($_POST[$field_name]);
		$field_value = preg_replace("/\W/", '', $field_value);
		
		if(!$first){
			$set_string	.= ', ';
		}
		$first = false;
		if($field_value == ''){
			$set_string	.= "$field_name=default";
		}else{
			$set_string	.= "$field_name='$field_value'";
		}
	}
	$result = sql("select * from $prefs where uniqname='$auth_uniqname'");
	if(mysql_num_rows($result)){
		sql("update $prefs set $set_string where uniqname='$auth_uniqname'");
	}else{
		sql("insert into $prefs set uniqname='$auth_uniqname', $set_string");
	}
	clearpostdata();
	exit;
}else if($submit == 'change'){
	getdata(array('displayname'), 'post', 'fullname');
	if($displayname != ''){
		sql("update $users set fullname = '$displayname' where uniqname = '$auth_uniqname'");
		//clearpostdata("Your display name was accepted as follows");
		clearpostdata();
	}else{
		
		clearpostdata("Your display name was NOT accepted. It cannot be blank and can only contain letters, hyphens, commas, periods, and spaces.");
	}
	
	exit;
}


//debug
//include '../viewdata.php';



showhtmlhead("Preferences");



?>
<form METHOD=POST ACTION="<?=$_SERVER['PHP_SELF']?>">
<p>Name: <input name="displayname" type="text" value="<?=$displayname?>" maxlength="100" size="25" /> <input type="submit" name="submit" value="Change" /> (accepted: letters, commas, periods, and spaces)</p>

</form>


<p><b>Note:</b> Bold indicates your current setting.</p>
<form METHOD=POST ACTION="<?=$_SERVER['PHP_SELF']?>">
<?

//loads specific user preferences if they exist
$result = sql("select * from $prefs where uniqname = '$auth_uniqname' limit 1");
$user_pref_array = mysql_fetch_assoc($result);

//go through each field
while($field_desc_array = mysql_fetch_assoc($fields)){
	
	$field_name = $field_desc_array['Field'];
	
	//skip certain fields
	if(in_array($field_name, $fields_to_skip)){
		continue;
	}
	$type = $field_desc_array['Type'];
	$type = str_replace('enum(', '', $type);
	$type = str_replace(')', '', $type);
	$type = str_replace('\'', '', $type);
	//echo $type;
	$possible_value_array = explode(',', $type);
	$user_pref = $user_pref_array[$field_name];

	if($pref_field_text[$field_name] != ''){
		$field_text = $pref_field_text[$field_name];
	}else{
		$field_text = $field_name;
	}
	
	echo "<h4>$field_text</h4>\n";
	
	foreach($possible_value_array as $value){
		
		echo "<input TYPE=\"radio\" NAME=\"$field_name\" value=\"$value\"";
		//sets field to default if none exsists for user
		if($user_pref[$field_name] == ''){
			$user_pref = $field_desc_array['Default'];
		}
		//if this value is the users preference, or default, then check it
		if($user_pref == $value){
			$checked = true;	
		}else{
			$checked = false;
		}
		if($checked){
			echo " checked";
		}
		echo ">";
		if($pref_value_text[$field_name][$value] != ''){
			$value_text = $pref_value_text[$field_name][$value];
		}else{
			$value_text = $value;
		}
		if($checked){
			echo "<b>";
		}
		echo "$value_text<br>\n";
		if($checked){
			echo "</b>";
		}
	}
}

?>

<p>
<input type="submit" name="submit" value="Save Preferences">
</p>
</form>
<?




showhtmlfoot();

?>