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
	
	$('#sections').click( function () {
		c.hide();
		t.hide();
		sec.show();
		sch.hide();
	});
	
	$('#schedules').click( function () {
		c.hide();
		t.hide();
		sec.hide();
		sch.show();
	});
	
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
	
	$(".class_tr").each( function(item) {
		if(color) 
			$(this).addClass('highlight_row');
		color = !color;
	});
	// Done coloring our tables!
	
	var dept = $("#department_list");
	
	// Show class list when we click on a department
	$(".dept_tr").each( function(item) {
		$(this).click( function () {
			$("#" + dept.attr('class') + "_classes").hide();
			dept.removeClass().addClass(this.id);
			$("#" + this.id + "_classes").show();
		});
	});
	
	// Put the department and class number into the selected paragraph
	$(".class_tr").each( function (item) {
		$(this).click( function () {
			var r = $("#selected_row")
			var row = r.val();
			var input = $("#" + row).children();
			$(input[0]).val( dept.attr('class') );
			$(input[1]).val( $(this).children().html() );
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

	var classes = new Array();
	$("#load_times").click( function () {
		$(".sel_p").each( function(item) {
			var input = $(this).children();
			if($(input[0]).val() != "")
				classes.push([$(input[0]).val(), $(input[1]).val()]);
		});
		console.log(classes);
	});
});