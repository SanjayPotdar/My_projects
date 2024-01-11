<?php
// Start the session
session_start();

// Destroy all session variables
session_destroy();

// Redirect to the index.html page
header("Location: index.html");
exit;
?>
