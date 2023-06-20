<?php

if (isset($_POST['id'])) {
    require '../database/connect.php';

    $id = $_POST['id'];

    if (empty($id)) {
        echo 'error';
    } else {
        $stmt = $conn->prepare("SELECT id, checked FROM `to-do_list`.`todos` WHERE id=?");
        $stmt->execute([$id]);

        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        $uId = $todo['id'];
        $checked = $todo['checked'];

        $uChecked = $checked ? 0 : 1;

        $stmt = $conn->prepare("UPDATE `to-do_list`.`todos` SET checked=? WHERE id=?");
        $res = $stmt->execute([$uChecked, $uId]);

        if ($res) {
            echo $checked;
        } else {
            echo "error";
        }
        $conn = null;
        exit();
    }
} else {
    header("Location: ../index.php?mess=error");
}
?>