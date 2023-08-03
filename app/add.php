<?php
session_start();

require_once '../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title']) && !empty($_POST['title'])) {
        $title = $_POST['title'];

        try {
            $stmt = $conn->prepare("INSERT INTO todos (title, date_time) VALUES (?, NOW())");
            $stmt->execute([$title]);
            header("Location: ../view/dashboard.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../view/dashboard.php?mess=error");
            exit;
        }
    } else {
        header("Location: ../view/dashboard.php?mess=error");
        exit;
    }
} else {
    header("Location: ../view/dashboard.php");
    exit;
}
?>
