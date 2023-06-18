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
}

// Using mysqli
$conn_mysqli = new mysqli($host, $username, $password, $dbname);
if ($conn_mysqli->connect_error) {
    die("mysqli Connection failed: " . $conn_mysqli->connect_error);
}

?>
