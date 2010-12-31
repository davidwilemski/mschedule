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
			
			var maxSchedules = json.length;
			
			//console.log(schedule);
			
			$("#schedule_div").html('<span id="put_table_here"></span>');
			
			// Function passing in a schedule to make the table
			var schedule_id = createWeekSchedule(schedule, 0, $("#put_table_here"));
			
	
			// Add in the 'schedule id' that we worked with.
			// This is for when we change what schedule we are looking at
			// and which one we save.
			$("#schedule_div").append('<span id="scheduleID" value="' + 0 + '" idstring="' + schedule_id + '" />');
			
			// For now, let's also add a simple span to be able to go forward
			// and backwards in the schedules
			$("#schedule_div").append('<span id="next_schedule"><p>Next Schedule</p></span>');
			$("#schedule_div").append('<span id="prev_schedule"><p>Prev Schedule</p></span>');
			$("#schedule_div").append('<span id="save_schedule"><p>Save Schedule</p></span>');
			
			// Hide the right buttons
			hideNextPrev(0, maxSchedules);
			
			// Function for moving forward a schedule 
			function nextButtonFunction() {
				var next_index = 0;
				next_index = $('#scheduleID').val() * 1 + 1;
				$('#scheduleID').val(next_index);
				$("#put_table_here").html("<p>Loading</p>");
				var new_schedule_id = createWeekSchedule(json[next_index], next_index, $('#put_table_here'));
				//console.log(new_schedule_id);
				$('#scheduleID').attr('idstring', new_schedule_id);
				hideNextPrev(next_index, maxSchedules);
			}
			$('#next_schedule').click(nextButtonFunction);
			
			// Function for moving back a schedule
			function prevButtonFunction() {
				var next_index = 0;
				next_index = $('#scheduleID').val() * 1 - 1;
				$('#scheduleID').val(next_index);
				$("#put_table_here").html("<p>Loading</p>");
				var new_schedule_id = createWeekSchedule(json[next_index], next_index, $('#put_table_here'));
				$('#scheduleID').attr('idstring', new_schedule_id);
				hideNextPrev(next_index, maxSchedules);
			} 
			$('#prev_schedule').click(prevButtonFunction);
			
			// Function for saving a schedule
			$('#save_schedule').click(function() {
				$.post("api/json/class_model/saveSchedule", {'data': $("#scheduleID").attr('idstring')}, function(data){
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

function hideNextPrev(myIndex, myMax) {
	if(myIndex > 0 && myIndex < myMax) {
		if($('#next_schedule').is(':hidden'))
			$('#next_schedule').show();
		if($('#prev_schedule').is(':hidden'))
			$('#prev_schedule').show();
	} 
	if (myIndex <= 0)
		$('#prev_schedule').hide();
	if (myIndex >= myMax-1)
		$('#next_schedule').hide();
}

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
