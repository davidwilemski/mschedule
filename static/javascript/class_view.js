$('document').ready(function() {
	var end = $("#submit");
	var num = $("#class_boxes");
	
	$("#add").click(function() {
		var i = num.val();
		i++;
		end.before("<p id=\"class" + i + "\"><label for=\"class" + i + "\">Class ID: </label><input type=\"text\" name=\"class" + i + "\" value=\"\" id=\"class" + i + "\"  /></p>" + "\n");
		num.val(i);
	});
	
	$("#remove").click(function(){
		var i = num.val();
		if(i > 0) {
			$("#class" + i).remove();
			num.val(--i);
		}
	});
});