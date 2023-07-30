<?php

if (isset($_POST['id'])) {
    require '../database/connect.php';

    $id = $_POST['id'];

    if (empty($id)) {
        echo 'error';
        exit();
    }

    $stmt = $conn->prepare("UPDATE `to-do_list`.`todos` SET checked = NOT checked WHERE id = ?");
    $res = $stmt->execute([$id]);

    if ($res) {
        echo 'success';
    } else {
        echo 'error';
    }
    $conn = null;
    exit();
} else {
    header("Location: ../index.php?mess=error");
    exit();
}