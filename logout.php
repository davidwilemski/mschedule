<?

include_once 'inc/common.php';

session_unset();
session_destroy();

showHTMLHead("Logged Out");
echo "<p>You have been successfully logged out.</p>";
echo "<a href=\"$login_page\">Login again</a>";
//$redirect = $start_page;
//include 'components/loginbox.php';

showHTMLFoot();
exit;

?>