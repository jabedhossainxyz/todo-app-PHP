<?php

if (isset($_POST['title'])) {
    require '../database/connect.php';

    $title = $_POST['title'];

    if (empty($title)) {
        header("Location: ../index.php?mess=error");
    } else {
        $stmt = $conn->prepare("INSERT INTO todos(title) VALUE(?)");
        $res = $stmt->execute([$title]);

        if ($res) {
            header("Location: ../index.php?mess=success");
        } else {
            header("Location: ../index.php");
        }
        $conn = null;
        exit();
    }
} else {
    header("Location: ../index.php?mess=error");
}
$title = $_POST['title'];
$date_time = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO added_tasks (title, date_time) VALUES (?, ?)");
$stmt->bindParam(1, $title);
$stmt->bindParam(2, $date_time);
$stmt->execute();

?>