<?php
require_once '../database/connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash and salt the password if necessary
    // Modify the SQL query to check hashed/salted password if applicable

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `to-do_list`.`users` WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful
        // Redirect the user to the desired page or perform any additional actions
        header("Location: ../view/dashboard.php");
        exit;
    } else {
        // Login failed
        // Redirect the user back to the login page with an error message
        header("Location: login.php?error=Invalid username or password");
        exit;
    }
}
