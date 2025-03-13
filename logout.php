<?php
// File: logout.php
// Purpose: Ends the user's session and redirects them to the homepage.

session_start(); // Start the session to access and manage session variables
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session completely

header("Location: index.php"); // Redirect the user to the homepage
exit(); // Ensure no further code is executed after the redirect
?>
