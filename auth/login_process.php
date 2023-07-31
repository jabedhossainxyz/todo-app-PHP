<?php
session_start();

// Include the database connection file
require_once '../database/connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password using the defined constants
    $hashedPassword = password_hash($password, PASSWORD_HASH_ALGO, PASSWORD_HASH_OPTIONS);

    $stmt = $conn->prepare("SELECT * FROM `to-do_list`.`users` WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result['password'])) {
        // Password is correct, set session and redirect to dashboard
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit;
    } else {
        // Redirect to login page with an error message
        header("Location: login.php?error=Invalid username or password");
        exit;
    }
}
