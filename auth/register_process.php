<?php
require_once '../database/connect.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM `to-do_list`.`users` WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        header("Location: register.php?error=Username already exists");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO `to-do_list`.`users` (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    if ($stmt->rowCount() > 0) {
        header("Location: login.php");
        exit;
    } else {
        header("Location: register.php?error=Registration failed");
        exit;
    }
}
?>
