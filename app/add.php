<?php

if (isset($_POST['title'])) {
    require '../database/connect.php';

    $title = $_POST['title'];

    if (empty($title)) {
        header("Location: ../index.php?mess=error");
    } else {
        $stmt = $conn->prepare("INSERT INTO `to-do_list`.`todos`(title) VALUES(?)");
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
?>
