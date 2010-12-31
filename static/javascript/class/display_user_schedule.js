$('document').ready(function () {

	var schedule_id = $("#put_schedule_here").attr("value");
	
	//console.log(schedule_id);
	
	$.post("../api/json/class_model/getUserClassDetails", { 'data': schedule_id }, function(data) {
	
		var json = jQuery.parseJSON(data);
		
		console.log(json);
		
		createWeekSchedule(json, 0, $("#put_schedule_here"), "../");
	
	});

});