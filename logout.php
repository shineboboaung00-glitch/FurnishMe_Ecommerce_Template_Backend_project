<?php
session_start();
session_destroy();  // Destroy session
header('location: login.php');  // Redirect to login page
exit();
?>