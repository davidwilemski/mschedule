<?php
$_GET['getdate'] = "20070113";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Mschedule</title>
  	<link rel="stylesheet" type="text/css" href="templates/mschedule/default.css" />
			
	<script language="JavaScript" type="text/javascript">
<!--
function openEventWindow(num) {
	// populate the hidden form
	var data = document.popup_data[num];
	var form = document.forms.eventPopupForm;
	form.elements.date.value = data.date;
	form.elements.time.value = data.time;
	form.elements.uid.value = data.uid;
	form.elements.cpath.value = data.cpath;
	form.elements.event_data.value = data.event_data;
	
	// open a new window
	var w = window.open('', 'Popup', 'scrollbars=yes,width=460,height=275');
	form.target = 'Popup';
	form.submit();
}

function EventData(date, time, uid, cpath, event_data) {
	this.date = date;
	this.time = time;
	this.uid = uid;
	this.cpath = cpath;
	this.event_data = event_data;
}

function openTodoInfo(vtodo_array) {	
	var windowW = 460;
	var windowH = 275;
	var url = "includes/todo.php?vtodo_array="+vtodo_array;
	options = "scrollbars=yes,width="+windowW+",height="+windowH;
	info = window.open(url, "Popup", options);
	info.focus();
}

document.popup_data = new Array();
//-->
</script>

</head>
<body>
<form name="eventPopupForm" id="eventPopupForm" method="post" action="includes/event.php" style="display: none;">

  <input type="hidden" name="date" id="date" value="" />
  <input type="hidden" name="time" id="time" value="" />
  <input type="hidden" name="uid" id="uid" value="" />
  <input type="hidden" name="cpath" id="cpath" value="" />
  <input type="hidden" name="event_data" id="event_data" value="" />
</form>

<div>
<?php include "week.php"; ?>
</div>
</body>
</html>
