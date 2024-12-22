<?php
session_start();

// Destroy session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the homepage or login page
header("Location: index.php");
exit;
?>
