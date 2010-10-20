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
	
	$('#times').click( function () {
		c.hide();
		t.show();
		sec.hide();
		sch.hide();
	});
	
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
						curr.removeClass('highlight_p');
						curr.next().addClass('highlight_p');
						var newRow = row.charAt(4) * 1;
						var rr = newRow + 1;
						r.val("sel_" + rr);
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
					class: 'sections_table'
				}));
				
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
							html: '<input checked="true" type="checkbox" use="1" name="section" value="' + json[item].classid + '" />'
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
		
		$('input[type="checkbox"]').each( function(item) {
			if($(this).attr('use') == '1') {
				use.push($(this).val());
			}
		});
		
		$.post("api/json/class_model/createSchedules", { 'data[]': use }, function(data) {
			console.log(data);
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