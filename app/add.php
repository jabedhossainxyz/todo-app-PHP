<?php
session_start();

require_once '../database/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title']) && !empty($_POST['title'])) {
        $title = $_POST['title'];
        $created_by = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';

        try {
            $stmt = $conn->prepare("INSERT INTO todos (title, created_by) VALUES (?, ?)");
            $stmt->execute([$title, $created_by]);
            header("Location: ../index.php");
            exit;
        } catch (PDOException $e) {
            header("Location: ../index.php?mess=error");
            exit;
        }
    } else {
        header("Location: ../index.php?mess=error");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}
