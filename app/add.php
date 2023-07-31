<?php
session_start();
require '../database/connect.php';

if (isset($_POST['title'])) {
    $title = $_POST['title'];

    if (!empty($title)) {
        // Get the username of the current user (you may retrieve it from the session)
        $username = $_SESSION['username'];

        // Insert the new task with the username into the database
        $stmt = $conn->prepare("INSERT INTO todos (title, created_by, date_time) VALUES (?, ?, NOW())");
        $stmt->execute([$title, $username]);

        header("Location: ../dashboard.php");
        exit;
    }
}

header("Location: ../dashboard.php?mess=error");
?>
