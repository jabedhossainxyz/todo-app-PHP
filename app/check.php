<?php
require '../database/connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (empty($id)) {
        echo 'error';
    } else {
        // Retrieve the current checked status from the database
        $stmt = $conn->prepare("SELECT checked FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $checked = $result['checked'];
            // Toggle the checked status
            $newChecked = $checked ? 0 : 1;

            // Update the checked status in the database
            $stmt = $conn->prepare("UPDATE todos SET checked = ? WHERE id = ?");
            $stmt->execute([$newChecked, $id]);

            // Return the new checked status as a response
            echo $newChecked;
        } else {
            echo 'error';
        }
        $conn = null;
        exit();
    }
} else {
    header("Location: ../index.php?mess=error");
}


?>