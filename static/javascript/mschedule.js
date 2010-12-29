var num = 2; //id for additional selected class boxes, will be incremented

$('document').ready(function () {

	var c = $('#class_div');
	var t = $('#time_div');
	var sec = $('#section_div');
	var sch = $('#schedule_div');
	
	c.show();
	t.hide();
	sec.hide();
	sch.hide();

	$('#classes').click( function () {
		c.show();
		t.hide();
		sec.hide();
		sch.hide();
	});

    $('.rm_course').click( rm_course);
	
	// clicking on sections loads the times now!
	
	// clicking on schedules loads below
	
	/**
	*** Classes Section
	**/
	
	// Let's color our table!
	var color = false;
	$(".dept_tr").each( function(item) {
		if(color) 
			$(this).addClass('highlight_row');
		color = !color;
	});
	// Done coloring our tables!
	
	var dept = $("#department_list");
	
	
	// Show class list when we click on a department
	$(".dept_tr").each( function(item) {
		$(this).click( function () {
			// Make sure we can reference what department is selected
			dept.removeClass().addClass(this.id);
			
			// Make our JSON post call
			$.post("api/json/class_model/getDeptClassList", { 'data[]': [this.id]}, function(data) {
				//console.log(data);
				var table = jQuery.parseJSON(data);
				var cl = $("#class_table");
				cl.html("");
				for(var item in table)  {
					cl.append('<tr id="' + table[item].classid + '" class="class_tr"><td>' + table[item].number + '</td><td>' + table[item].class_name + '</td></tr>');
				}
				
				// Put the department and class number into the selected paragraph
				$(".class_tr").each( function (item) {
					$(this).click( function () {
						var r = $("#selected_row");
						var row = r.val();
						var input = $("#" + row).children();
						$(input[0]).val( dept.attr('class') );
						$(input[1]).val( $(this).children().html() );
						
						// Move the paragraph selector
						var curr = $("p[class='sel_p highlight_p']");
						
						if(num < 10){
						
						
							curr.parent().append('<p id="sel_' + num.toString() + '" class="sel_p"><input type="text" name="dept_' + num.toString() + '" value="" id="dept_' + num.toString() + '" class="dept_input" readonly="readonly"  /><input type="text" name="class_' + num.toString() + '" value="" id="class_' + num.toString() + '" class="class_input" readonly="readonly"  /><span id="c' + num.toString() +'" class="rm_course"><img src="static/images/round_delete.png" /></span></p>');
							num++;
							curr.removeClass('highlight_p');
							curr.next().addClass('highlight_p');
							
							var newRow = row.charAt(4) * 1;
							var rr = newRow + 1;
							r.val("sel_" + rr);
							
							
							
							// Highlight the selected paragraph
							$(".sel_p").each( function(item) {
								$(this).click( function () {
									$("#selected_row").val( $(this).attr('id') );
									$(".sel_p").each( function(elm) {
										$(this).removeClass('highlight_p');
									});
									$(this).addClass('highlight_p');
								});
							});
                            
                            $('.rm_course').unbind();

                            $('.rm_course').click( rm_course);

							

						}
						
					});
				});
				
				// Color our table!
				$(".class_tr").each( function(item) {
					if(color) 
						$(this).addClass('highlight_row');
					color = !color;
				});
				
			});
		});
	});
	
	// Highlight the first paragraph
	$(".sel_p").first().addClass('highlight_p');
	
	// Highlight the selected paragraph
	$(".sel_p").each( function(item) {
		$(this).click( function () {
			$("#selected_row").val( $(this).attr('id') );
			$(".sel_p").each( function(elm) {
				$(this).removeClass('highlight_p');
			});
			$(this).addClass('highlight_p');
		});
	});
	
	/**
	*** Times Section
	**/
	
	$('#times').click( function () {
		c.hide();
		t.show();
		sec.hide();
		sch.hide();
	});
	
	/**
	*** Sections Section
	**/

	var classes = Array();
	$("#sections").click( function () {
		c.hide();
		t.hide();
		sec.show();
		sch.hide();
		
		$("#section_div").html("");
		// LOAD SECTION TIMES!
		$(".sel_p").each( function(item) {
			// this is the group of text inputs, [0] and [1] are the dept and class number
			var input = $(this).children();
			if($(input[0]).val() != "") {
				var dept = $(input[0]).val();
				var num = $(input[1]).val();
				/*start.append('<div id="class_' + j + '_div></div>');
				start = $("#class_" + j + "_div");
				start.append('<p><strong>' + dept + ' ' + num + '</strong></p>');
				start.append('<table border=1 id="' + j + '" class="sections_table">');
				start.append('</table>');
				start = $("#" + j);
				//$("#class_" + j + "_div").prepend('<p><strong>' + dept + ' ' + num + '</strong></p>');*/
				
				$("#section_div").append( $('<div>', {
					id: 'class_' + dept + num + '_div'
				}));
				$('#class_' + dept + num + '_div').append( $('<p>', {
					text: dept + ' ' + num
				}));
				$('#class_' + dept + num + '_div').append( $('<table>', {
					id: 'class_' + dept + num + '_table',
					border: 1,
				}).addClass('sections_table'));
				
				$.post("api/json/class_model/getClassSections", { 'data[]': [dept, num]}, function(data) {
					var json = jQuery.parseJSON(data);
					for(var item in json) {
						var a = json[item].dept;
						var b = json[item].number;
						
						classes[json[item].classid] = Array(json[item].dept, json[item].number, json[item].classid, json[item].section, json[item].type, json[item].days, json[item].time);
						
						$('#class_' + a + b + '_table').append( $('<tr>', {
							id: 'show_' + json[item].classid
						}));
						var row = $('#show_' + json[item].classid)
						row.append( $('<td>', {
							html: '<input checked="false" type="checkbox" use="1" name="section" value="' + json[item].classid + '" />'
						}));
						row.append( $('<td>', {
							text: json[item].classid
						}));
						row.append( $('<td>', {
							text: json[item].section
						}));
						row.append( $('<td>', {
							text: json[item].type
						}));
						
						var l = json[item].location.split(';');
						var lString = json[item].location;
						if(l[1]) {
							lString = '';
							for(var z in l) {
								lString += '<p>' + l[z] + '</p>';
							}
						}
						
						row.append( $('<td>', {
							html: lString 
						}));
						
						var d = json[item].days.split(';');
						var dString = json[item].days;
						if(d[1]) {
							dString = '';
							for(var z in d) {
								dString += '<p>' + d[z] + '</p>';
							}
						}
						
						row.append( $('<td>', {
							html: dString
						}));
						
						var t = json[item].time.split(';');
						var tString = json[item].time;
						if(t[1]) {
							tString = '';
							for(var z in t) {
								tString += '<p>' + formatNiceTime(t[z]) + '</p>';
							}
						}
						
						row.append( $('<td>', {
							html: tString
						}));
					}
					
					// Add listener to checkboxes
					$("input[type='checkbox']").each( function(item) {
						$(this).unbind('click');
						$(this).click( function() {
							if($(this).attr('use') == '1')
								$(this).attr('use', '0');
							else
								$(this).attr('use', '1');
						});
					});
				});
			}
		});
		
		
	});
	
	/**
	*** Schedules Section
	**/
	
	$('#schedules').click( function () {
		c.hide();
		t.hide();
		sec.hide();
		sch.show();
		
		var use = Array();
		use.push($('select[name="times"]').val()); // This puts the time pref in the first spot of the POST array
		$('input[type="checkbox"]').each( function(item) {
			if($(this).attr('use') == '1') {
				use.push($(this).val());
			}
		});
		
		//console.log(use);
		$.post("api/json/class_model/createSchedules", { 'data[]': use }, function(data) {
			//console.log(data);
			var tableString = "";
			var json = jQuery.parseJSON(data);
			
			var schedule = json[0];
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
					for(start_key; start_key <= end_key; start_key++) {
						if(!begin) {
							begin = true;
							for(start_key_minor; start_key_minor < box_per_hour; start_key_minor++) {
								master[start_key][start_key_minor][day_of_week] = 'BUSY';
							}
						}
						// If we are not quite to the end yet
						if(start_key != end_key) {
							for(var j = 0; j < box_per_hour; j++) {
								master[start_key][j][day_of_week] = 'BUSY';
							}
						}
						// If we are at the end
						if(start_key == end_key) {
							for(var j = 0; j < end_key_minor; j++) {
								master[start_key][j][day_of_week] = 'BUSY';
							}
						}
					}
				
				}
			
			}
			
			var day = new Date(2010, 1, 1, 0, 0, 0, 0);
			
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
						ROW_STRING += '<td>';
						ROW_STRING += master[hour][hour_part][weekday];
						if(master[hour][hour_part][weekday] != '')
							hide = false;
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
			
			console.log(HTML_STRING);
			
			var tableString = HTML_STRING;
			
			// This used to dump each schedule into the page in various tables. Lets not do that.
			/*for(var j in json) {
				//console.log(json[j]);
				tableString += '<table><tbody><tr>';
				tableString += '<td>Class ID</td>';
				tableString += '<td>Department</td>';
				tableString += '<td>Class Number</td>';
				tableString += '<td>Class Section</td>';
				tableString += '<td>Class Type</td>';
				tableString += '<td>Score</td>';
				tableString += '</tr>';
				scheduleID = '';
				for(var c in json[j]) {
					//console.log(json[j][c]);
					tableString += '<tr>';
					tableString += '<td>';
					tableString += json[j][c].classid;
					scheduleID += json[j][c].classid + ';';
					tableString += '</td>';
					tableString += '<td>';
					tableString += json[j][c].dept;
					tableString += '</td>';
					tableString += '<td>';
					tableString += json[j][c].number;
					tableString += '</td>';
					tableString += '<td>';
					tableString += json[j][c].section;
					tableString += '</td>';
					tableString += '<td>';
					tableString += json[j][c].type;
					tableString += '</td>';
					tableString += '<td>'
					tableString += json[j][c].score
					tableString += '</td>';
					tableString += '</tr>';
				}
				tableString += '<tr><td class="save_schedule" value="';
				tableString += scheduleID;
				tableString += '">Save this schedule!</td></tr>';
				tableString += '</tbody></table>';
			}*/
			
			// Add the table to the view
			$("#schedule_div").html(tableString);
			
			// And hide the rows to be hidden
			$('.hide').each(function() {
				$(this).hide();
			});
			
			// Function for saving a schedule
			$('.save_schedule').click(function() {
				$.post("api/json/class_model/saveSchedule", {'data': $(this).attr('value')}, function(data){
					console.log(data);
					if(data == "true") {
						alert('Your schedule is safe');
					} else {
						alert('Something went wrong');
					}
				});
			});
		});
		
	});
	
});

function formatNiceTime(time) {
	var t = time.split('-');
	var s = '';
	
	for(var i = 0; i <= 1; i++) {
		var suf = 'AM';
		var num = t[i] * 1;
	
		if(num > 1200) {
			suf = 'PM';
		}
		
		if(num > 1259) {
			num = num - 1200;
		}
		
		num_str = num.toString();
		
		if(num_str.length == 3)
			s += num_str[0] + ':' + num_str[1] + num_str[2] + suf;
		else
			s += num_str[0] + num_str[1] + ':' + num_str[2] + num_str[3] + suf;
		
		if(i == 0)
			s += '-';
	}
	
	return s;
}

function rm_course(event){
    id = $(this).parent().attr('id').substr(4);
	//remove row
    $(this).parent().remove();
	//subtract 1 from all ids that are > this row's id
    $('.sel_p').each(function(){

        if(this.id.substr(4) > id){
            $cNum = (this.id.substr(4)).toString();
            $nNum = (this.id.substr(4)-1).toString();
            this.id = 'sel_' + (this.id.substr(4)-1).toString();
            $(this).children("#dept_" + $cNum).attr("name", "dept_" + $nNum);
            $(this).children("#class_" + $cNum).attr("name", "class_" + $nNum);
            $(this).children("#dept_" + $cNum).attr("id","dept_" + $nNum);
            $(this).children("#class_" + $cNum).attr("id", "class_" + $nNum);
            $(this).children("#c" + $cNum).attr("id", "c" + $nNum);

            //$(this+':nth-child(1)').id = 'dept_' + (this.id.substr(4)-1).toString();
            //$(this+':nth-child(2)').id = 'class_' + (this.id.substr(4)-1).toString();
            //$(this+':nth-child(3)').id = 'c' + (this.id.substr(4)-1).toString();
        }
    });
    //decrement num
    num--;

}
