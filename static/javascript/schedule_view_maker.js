function createWeekSchedule(schedule, myIndex, location) {
	var time_denom = 30;
	var box_per_hour = 60 / time_denom;
	
	var weekdays = Array(0,1,2,3,4,5,6);
	
	var master = Array();
	
	for(var i = 0; i < 24; i++) {
	
		master[i] = Array();
		
		for(var j = 0; j < box_per_hour; j++) {
		
			master[i][j] = Array();
			
			for(var day in weekdays) {
			
				master[i][j][day] = '';
			
			}
		
		}
	
	}
	
	for(var c in schedule) {
		
		var days = schedule[c].days[0].split(',');
		
		for(var i = 0; i < days.length; i++) {
		
			var start_key = -1;
			var start_key_minor = -1;
			var end_key = -1;
			var end_key_minor = -1;
			
			if(typeof days[i] != 'undefined')
				var day = days[i];
			else
				var day = days[0];
			
			if(typeof schedule[c].time[i] != 'undefined')
				var time = schedule[c].time[i];
			else
				var time = schedule[c].time[0];
			time = time.split('-');
			
			// Work on the start time
			if(time[0] % 100 == 0) {
				// Then the start time is on the hour
				start_key = time[0] / 100;
				start_key_minor = 0;					
			} else {
				start_key_minor = 0;
				while(time[0] % 100 != 0) {
					time[0] -= time_denom;
					start_key_minor++;
				}
				start_key = time[0] / 100;
			}
			
			// Work on the end time
			if(time[1] % 100 == 0) {
				// Then the end time is on the hour
				end_key = time[1] / 100;
				end_key_minor = 0;					
			} else {
				end_key_minor = 0;
				while(time[1] % 100 != 0) {
					time[1] -= time_denom;
					end_key_minor++;
				}
				end_key = time[1] / 100;
			}
			
			var day_of_week = -1;
			if(day == 'SU')
				day_of_week = 0;
			if(day == 'M')
				day_of_week = 1;
			if(day == 'TU')
				day_of_week = 2;
			if(day == 'W')
				day_of_week = 3;
			if(day == 'TH')
				day_of_week = 4;
			if(day == 'F')
				day_of_week = 5;
			if(day == 'SA')
				day_of_week = 6;
				
			var begin = false;
			var CELL_CONTENTS = schedule[c].dept + " " + schedule[c].number + "." + schedule[c].section;
			var BUBBLE_CONTENTS = "<strong>" + CELL_CONTENTS + "</strong><br />"
				+ "Class ID: " + schedule[c].classid;
			var CELL_DATA = new Array();
			CELL_DATA.push(CELL_CONTENTS);
			CELL_DATA.push(BUBBLE_CONTENTS);
			CELL_DATA.push(schedule[c].classid);
			//console.log(CELL_DATA);
			for(start_key; start_key <= end_key; start_key++) {
				if(!begin) {
					for(start_key_minor; start_key_minor < box_per_hour; start_key_minor++) {
						master[start_key][start_key_minor][day_of_week] = CELL_DATA;
					}
					begin = true;
				} else {
					// If we are not quite to the end yet
					if(start_key != end_key) {
						for(var j = 0; j < box_per_hour; j++) {
							master[start_key][j][day_of_week] = CELL_DATA;
						}
					}
					// If we are at the end
					if(start_key == end_key) {
						for(var j = 0; j < end_key_minor; j++) {
							master[start_key][j][day_of_week] = CELL_DATA;
						}
					}
				}
			}
		
		}
	
	}
	
	var day = new Date(2010, 1, 1, 0, 0, 0, 0);
	var TD_IDs = new Array();
	var TD_CONTENT = new Array();
	
	var td_id = 0;
	// Number of columns: 7 (days of a week)
	// Number of rows:    48 (24 hours, * 2)
	var HTML_STRING = '';
	HTML_STRING += '<table border="1">';
	HTML_STRING += '<tbody>';
	HTML_STRING += '<tr><td>Times</td><td>Sunday</td><td>Monday</td><td>Tuesday</td><td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td></tr>';
	for(var hour in master) {
		for(var hour_part in master[hour]) {
			var ROW_STRING = '';
			ROW_STRING += '<td>';
			var mins = day.getMinutes();
			if(mins == "0")
				mins = "00";
			ROW_STRING += day.getHours() + ":" + mins;
			//console.log(day);
			ROW_STRING += '</td>';
			var hide = true;
			for(var weekday in master[hour][hour_part]) {
				ROW_STRING += '<td id="' + td_id++ + '" ';
				if(master[hour][hour_part][weekday].length > 0) { // We have a class here
				// This if is repeated below...
					hide = false;
					ROW_STRING += 'class="visit" classid="' + master[hour][hour_part][weekday][2] + '"';
					TD_IDs.push(td_id);
					TD_CONTENT.push(master[hour][hour_part][weekday][1]);
				}
				ROW_STRING += '>';
				if(master[hour][hour_part][weekday].length > 0) // This if is right above...
					ROW_STRING += master[hour][hour_part][weekday][0];
				ROW_STRING += '</td>';
			}
			if(hide && (day.getHours() < 8 || day.getHours() > 18)) // If we can hide this, and it's before 8 am or after 6 pm
				HTML_STRING += '<tr class="hide">' + ROW_STRING + '</tr>';
			else
				HTML_STRING += '<tr>' + ROW_STRING + '</tr>';
			
			// Increment the day.
			day.setMinutes(day.getMinutes() + time_denom);
		}
	}
	HTML_STRING += '</tbody>';
	HTML_STRING += '</table>';
	
	$(location).html(HTML_STRING);
	
	// And hide the rows to be hidden
	$('.hide').each(function() {
		$(this).hide();
	});
	
	// combine the boxes that are the same classes
	combBoxes();
	
	// Make the popup bubbles!
	for(var z in TD_IDs) {
		var id_go = TD_IDs[z] * 1 - 1;
		console.log(id_go);
		$("#" + id_go).CreateBubblePopup({ 
			innerHtml: TD_CONTENT[z], 
			themePath: 'static/css/bubble-themes', 
			themeName: 'blue',
			openingSpeed: 100,
			closingSpeed: 100
		});
	}
	
}

function combBoxes() {
	while($('.visit').length > 0) {
		var this_box = $('.visit').first();
		var currID = this_box.attr('id') * 1;
		var currCLASSID = this_box.attr('classid');
		
		// We are looking at a new class
		$(this_box).attr("rowspan", 1);
		$(this_box).removeClass('visit');
		
		// We are still on the same class
		// Add 7 to the id for the next row.
		var done = false;
		var nextNUM = -1;
		while(!done) {
			done = true;
			nextNUM = currID + 7;
			var nextBX = $('#' + nextNUM);
			//console.log(nextNUM);
			if($(nextBX).attr('classid') == currCLASSID) {
				$(this_box).attr('rowspan', $(this_box).attr('rowspan') + 1);
				$(nextBX).removeClass('visit');
				$(nextBX).remove();
				done = false;
				currID = nextNUM;
			}			
		}
	}
}