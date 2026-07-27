<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session data
$_SESSION = [];
session_unset();
session_destroy();

// Send the user back to the login page
header("Location: login.php");
exit;
?>
