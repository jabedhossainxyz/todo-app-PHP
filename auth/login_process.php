<?php
session_start();

require_once '../database/connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM `to-do_list`.`users` WHERE username = :username AND password = :password");
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        $_SESSION['username'] = $username; 
        header("Location: ../view/dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=Invalid username or password");
        exit;
    }
}
?>