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

	$("#sections").click( function () {
		c.hide();
		t.hide();
		sec.show();
		sch.hide();
		
		$("#section_div").html("");
		// LOAD SECTION TIMES!
		var j = 1;
		$(".sel_p").each( function(item) {
			// this is the group of text inputs, [0] and [1] are the dept and class number
			var input = $(this).children();
			if($(input[0]).val() != "") {
				var dept = $(input[0]).val();
				var num = $(input[1]).val();
				$.post("api/json/class_model/getClassSections", { 'data[]': [dept, num]}, function(data) {
					var json = jQuery.parseJSON(data);
					var start = $("#section_div");
					start.append('<div id="class_' + j + '_div>');
					start.append('<table border=1 id="' + j + '" class="sections_table">');
					start.append('</table>');
					start.append('</div>');
					var title = true;
					for(var item in json) {
						start = $("#" + j);
						if(title) {
							$("#class_" + j + "_div").prepend('<p><strong>' + json[item].dept + ' ' + json[item].number + '</strong></p>');
							title = false;
						}
						start.append('<tr id="' + json[item].classid + '"><td>' + '<input use="1" checked="true" type="checkbox" name="section" class_number="' + json[item].number + '" value="' + json[item].classid + '" />' + '</td><td>' + json[item].classid + '</td><td>' + json[item].section + '</td><td>' + json[item].type + '</td><td>' + json[item].days + '</td><td>' + formatNiceTime(json[item].time) + '</td></tr>');
					}
					j = j + 1;
				});
			}
		});
		
		// Add listener to checkboxes
		$("input[type='checkbox']").each( function(item) {
			console.log(item);
			$(this).click( function() {
				$(this).hide();
			});
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
		
		// TODO: Get information from previous step with checked sections, and start making schedules.
	});
	
});

function splitTime(time) {
	var t = time.split('-');
	console.log(t);
	return time;
}

function formatNiceTime(time) {
	var t = time.split('-');
	var s = Array();
	for(var a in t) {
		var suffix = "am";
		var l = t[a].length;
		t[a] = t[a] * 1;
		if(l == 1) {
			if(t[a] < 1 && t[a] >= 0) {
				t[a] = t[a] + 12;
			}
			s[a] = t[a] + ":00 " + suffix;
		} else if(l == 2) {
			if(t[a] > 12) {
				t[a] = t[a] - 12;
				suffix = "pm";
			}
			s[a] = t[a] + ":00 " + suffix;
		} else if(l == 3) {
			if(t[a] < 100 && t[a] >= 0) {
				t[a] = t[a] + 1200;
				t[a] = t[a] + "z"
				s[a] = t[a].charAt(0) + t[a].charAt(1) + ":" + t[a].charAt(2) + t[a].charAt(3) + " " + suffix;
			} else
				t[a] = t[a] + "z"
				s[a] = t[a].charAt(0) + ":" + t[a].charAt(1) + t[a].charAt(2) + " " + suffix;
		} else if(l == 4) {
			if(t[a] > 1259) {
				t[a] = t[a] - 1200;
				suffix = "pm";
			} 
			if(t[a] < 1000) {
				t[a] = t[a] + "z"
				s[a] = t[a].charAt(0) + ":" + t[a].charAt(1) + t[a].charAt(2) + " " + suffix;
			} else {
				t[a] = t[a] + "z"
				s[a] = t[a].charAt(0) + t[a].charAt(1) + ":" + t[a].charAt(2) + t[a].charAt(3) + " " + suffix;
			}
		}
	}
	return s[0] + " - " + s[1];
}