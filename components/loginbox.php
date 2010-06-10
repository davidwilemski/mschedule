<?php
//displays the login box, which I believe is only displayed on the login.php page

include_once 'inc/common.php';

$post_action = isset($redirect) ? $redirect : $_SERVER['PHP_SELF'];

?>
<b>Warning:</b> DO NOT use your UofM password here.
<form method="post" action="<?=$post_action?>" name=form>
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
       <td align="right">
           <p>Username</p>
       </td>
       <td>
           <input name="uniqname" type="text" maxlength="8" size="10" />
       </td>
   </tr> 
   <tr> 
       <td align="right">
           <p>Password</p>
       </td>
       <td>
           <input name="password" type="password" maxlength="20" size="10" />
       </td>
   </tr>
   <tr>
      <td colspan=2 align="right">
           <a href= "passreset.php">forgot your password?</a> 
       </td>
   </tr>
   <tr>
       <td align="right" colspan="2">
            <hr />
           <input type="submit" name="submit" value="Log in" /> 
       </td> 
   </tr> 
</table>
</form>
<script>
<!--
document.form.uniqname.focus();
// -->
</script>
