$j(document).ready(function() {
	$j('#signin_menu input[type=text]').keypress(function (e) {
		if(e.keyCode == 13) {
			$j(this).closest('form').submit();
		}
	}).placeholder();
	
	$j('#signin_menu input[type=password]').keypress(function (e) {
		if(e.keyCode == 13) {
			$j(this).closest('form').submit();
		}
	}).placeholder();
	
	$j('#forgot_username_link').tipsy({gravity: 'w'});
	
	$j('#signin_submit').click(function (e) {
		e.preventDefault();
		$j(this).closest('form').submit();
	});
	
});
