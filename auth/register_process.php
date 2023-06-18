<?php
require_once '../database/connect.php';

if (isset($_POST['register'])) {
    // Retrieve the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform any necessary input validation and error handling

    // Insert the user information into the database
    $sql = "INSERT INTO `to-do_list`.`users` (username, password) VALUES ('$username', '$password')";

    // Execute the SQL query
    if ($conn->query($sql) === true) {
        // Registration successful
        // Handle success messages or additional actions here

        // Redirect the user to the login page after successful registration
        header("Location: login.php");
        exit;
    } else {
        // Registration failed
        // Handle error messages or additional actions here

        // Redirect the user back to the registration page with an error message
        header("Location: register.php?error=Registration failed");
        exit;
    }
}
?>
