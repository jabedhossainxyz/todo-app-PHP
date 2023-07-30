<?php
if (isset($_POST['title'])) {
    require '../database/connect.php';

    $title = $_POST['title'];

    if (empty($title)) {
        header("Location: ../index.php?mess=error");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO `to-do_list`.`todos`(title, date_time) VALUES(?, NOW())");
        $res = $stmt->execute([$title]);

        if ($res) {
            header("Location: ../index.php?mess=success");
            exit();
        } else {
            header("Location: ../index.php");
            exit();
        }
    }
} else {
    header("Location: ../index.php?mess=error");
    exit();
}