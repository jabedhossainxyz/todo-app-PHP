<?php
session_start();

// Set a test session variable
$_SESSION['test'] = 'Hello, this is a test!';

// Output a message to indicate successful session creation
echo 'Session created successfully.';
?>
