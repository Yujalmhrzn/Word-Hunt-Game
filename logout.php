<?php
session_start();
session_unset();
session_destroy();

// Redirect to login page with a message
header("Location: login.php?msg=You have been logged out.");
exit;
?>
