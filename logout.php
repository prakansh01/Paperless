<?php
session_start(); // Resume the session
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: signup.html"); // Redirects to the sugnup page
exit();
?>
