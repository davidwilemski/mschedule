$(document).ready(function() {

    $("#signin_button").click(function(e) {          
		e.preventDefault();
        $("#signin_menu").toggle();
    });
	
	$("#signin_menu").mouseup(function() {
		return false;
	});
	
	$(document).mouseup(function(e) {
		if($(e.target).parent("#signin_button").length == 0) {
			$("#signin_menu").hide();
		}
	});
	
	$('#forgot_username_link').tipsy({gravity: 'w'});
});
