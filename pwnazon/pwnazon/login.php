<?php
include "utils.php";

make_header("Login");

?>
<h3>Note: User accounts are currently in beta, and restricted to administrator
access</h3>

<form action='#' onsubmit='login()';>
 <p>User: <input type="text" name="user"></p>
 <p>Password: <input type="password" name="password"></p>
 <button type="button" onclick='login()'>Login</button>
</form>
