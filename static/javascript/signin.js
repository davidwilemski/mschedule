$j(document).ready(function() {

    $j("#signin_button").click(function(e) {          
		e.preventDefault();
        $j("#signin_menu").toggle();
    });
	
	$j("#signin_menu").mouseup(function() {
		return false;
	});
	
	$j(document).mouseup(function(e) {
		if($j(e.target).parent("#signin_button").length == 0) {
			$j("#signin_menu").hide();
		}
	});
	
	$j('#forgot_username_link').tipsy({gravity: 'w'});
});
