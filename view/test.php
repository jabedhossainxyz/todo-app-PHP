<?php
session_start();

include '../database/connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
        header("Location: ../auth/login.php");
        exit;
}

// Function to move a task to the completed_tasks table
function completeTask($conn, $taskId)
{
        $stmt = $conn->prepare("INSERT INTO completed_tasks (id, title, checked, date_time)
                            SELECT id, title, checked, date_time FROM todos WHERE id = ?");
        $stmt->execute([$taskId]);
}

// Function to move a task to the deleted_tasks table
function deleteTask($conn, $taskId)
{
        $stmt = $conn->prepare("INSERT INTO deleted_tasks (id, title, checked, date_time)
                            SELECT id, title, checked, date_time FROM todos WHERE id = ?");
        $stmt->execute([$taskId]);
}

// Check if a task has been completed
if (isset($_POST['complete'])) {
        $taskId = $_POST['complete'];

        if (!empty($taskId)) {
                completeTask($conn, $taskId);
                $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
                $stmt->execute([$taskId]);
        }
}

// Check if a task has been deleted

?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Dashboard</title>
        <link rel="stylesheet" href="../css/style.css">

        <style>
                /* ... your CSS styles ... */
        </style>
</head>

<body>
        <!-- ... your HTML code ... -->

        <div class="show-todo-section">
                <?php if ($todos->rowCount() <= 0) { ?>
                        <div class="todo-item">
                                <div class="empty">
                                        <img src="../img/f.png" width="100%" />
                                        <img src="../img/Ellipsis.gif" width="80px">
                                </div>
                        </div>
                <?php } ?>

                <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="todo-item">
                                <form class="task-form" action="" method="POST">
                                        <span>
                                                <button type="submit" name="complete"
                                                        value="<?php echo $todo['id']; ?>">Complete</button>
                                                <button type="submit" name="delete"
                                                        value="<?php echo $todo['id']; ?>">Delete</button>
                                        </span>
                                </form>
                                <?php if ($todo['checked']) { ?>
                                        <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
                                        <h2 class="checked">
                                                <?php echo $todo['title'] ?>
                                        </h2>
                                <?php } else { ?>
                                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                                        <h2>
                                                <?php echo $todo['title'] ?>
                                        </h2>
                                <?php } ?>
                                <br>
                                <small>created:
                                        <?php echo $todo['date_time'] ?>
                                </small>
                        </div>
                <?php } ?>
        </div>
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>

        <script>
                $(document).ready(function () {
                        $('.remove-to-do').click(function () {
                                const id = $(this).attr('id');

                                $.post("../app/remove.php", {
                                        id: id
                                },
                                        (data) => {
                                                if (data) {
                                                        $(this).parent().hide(600);
                                                }
                                        }
                                );
                        });

                        $(".check-box").click(function (e) {
                                const id = $(this).attr('data-todo-id');

                                $.post('../app/check.php', {
                                        id: id
                                },
                                        (data) => {
                                                if (data != 'error') {
                                                        const h2 = $(this).next();
                                                        if (data === '1') {
                                                                h2.removeClass('checked');
                                                        } else {
                                                                h2.addClass('checked');
                                                        }
                                                }
                                        }
                                );
                        });
                });
        </script>
</body>

</html>