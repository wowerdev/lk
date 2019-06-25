<?php
@session_start();
session_unset();
session_destroy();
setcookie("login", '', time() - 999999, "/", "", 0);
setcookie("hash_pass",  '', time() - 999999, "/", "", 0);
echo true; 
?>
