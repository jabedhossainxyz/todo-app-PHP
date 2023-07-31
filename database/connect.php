<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "to-do_list";

// Using PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "PDO Connection failed: " . $e->getMessage();
    exit; // Stop execution if the connection fails
}

// Define password hashing algorithm and options
define('PASSWORD_HASH_ALGO', PASSWORD_DEFAULT);
define('PASSWORD_HASH_OPTIONS', []);
?>