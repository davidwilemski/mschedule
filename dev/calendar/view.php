<?
require_once 'db.php';


$uniqname = $_GET['uniqname'];

$result = $db->getAll("SELECT t2.dept, t2.number, t2.section "
                                . " FROM `uniqname_class_winter07` as t1, `classes_winter07` as t2 "
                        . " WHERE t1.uniqname = ? AND "
                        . " t1.classid = t2.classid "
                        . " ORDER BY `dept`, `number`, `section`", array($uniqname));

if(PEAR::isError($result)){
	die($result->getMessage());
}


$sections = array();
foreach($result as $row){
	array_push($sections, "{$row['dept']}_{$row['number']}_{$row['section']}");
}

$_GET['term'] = "WN2007";
$_GET['cal'] = $sections;
$_GET['getdate'] = "20070113";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>Mschedule</title>
        <link rel="stylesheet" type="text/css" href="templates/mschedule/default.css" />
</head>
<body>
<form method="get"  autocomplete="off" action="<?=$_SEVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
       <td align="right"> 
           <p>Uniqname</p>
       </td> 
       <td>
           <input name="uniqname" type="text" value="<?=$uniqname?>" maxlength="8" size="10" /> 
       </td>

   		<td colspan="2" nowrap>
           <input type="submit" value="View" /><form>
<?
	if($auth_uniqname){
			?><input type="submit" name="submit" value="Add to VIPs"><?
	}
?>
       </td> 
   </tr> 

</table>
</form>

<?php
include 'week.php';
?>
</body>
</html>
