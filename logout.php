<?php
session_start();

session_unset(); 

session_destroy();
echo "Logout berhasil!"; 

setcookie("username", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>