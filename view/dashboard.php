<?php
require '../database/connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}


// Assuming you have stored the username of the logged-in user in $_SESSION['username']
$username = $_SESSION['username'];
$todos = $conn->prepare("SELECT * FROM todos WHERE checked = 0 AND created_by = ? ORDER BY id DESC");
$todos->execute([$username]);

if (isset($_POST['complete'])) {
    $taskId = $_POST['complete'];
    $stmt = $conn->prepare("UPDATE `to-do_list`.`todos` SET checked = 1 WHERE id = ?");
    $stmt->execute([$taskId]);
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['delete'])) {
    $taskId = $_POST['delete'];
    $stmt = $conn->prepare("DELETE FROM `to-do_list`.`todos` WHERE id = ?");
    $stmt->execute([$taskId]);
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['title'])) {
    $title = $_POST['title'];

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO `to-do_list`.`todos` (title, date_time) VALUES (?, NOW())");
        $stmt->execute([$title]);
        header("Location: dashboard.php");
        exit;
    }
}

$todos = $conn->query("SELECT * FROM todos WHERE checked = 0 ORDER BY id DESC");

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
        .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                background-color: #f2f2f2;
        }

        .navbar-menu li a {
                font-size: 20px;
        }

        .navbar-menu {
                list-style: none;
                margin: 0;
                padding: 0;
        }

        .navbar-menu li {
                display: inline-block;
                margin-left: 10px;
        }

        .navbar-menu li:first-child {
                margin-left: auto;
        }

        .show-todo-section table {
                width: 100%;
        }

        table.dataTable {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #dee2e6;
        }

        table.dataTable thead th,
        table.dataTable tfoot th {
                font-weight: bold;
                border: 1px solid #dee2e6;
        }

        table.dataTable td,
        table.dataTable th {
                padding: 12px;
                line-height: 2;
                vertical-align: middle;
                border-top: 1px solid #dee2e6;
                text-align: center;
        }

        table.dataTable tbody tr:nth-child(even) {
                background-color: #f9f9f9;
        }
        </style>
</head>

<body>
        <div class="navbar">
                <div class="welcome-section">
                        <h2>Welcome
                                <?php echo $_SESSION['username']; ?>
                        </h2>
                </div>
                <ul class="navbar-menu">
                        <li><a href="../auth/logout.php" class="logout-button">Logout</a></li>
                </ul>
        </div>
        <div class="main-section">
                <div class="add-section">
                        <form action="" method="POST" autocomplete="off">
                                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') {?>
                                <input type="text" name="title" style="border-color: #ff6666"
                                        placeholder="This field is required" />
                                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                                <?php } else {?>
                                <input type="text" name="title" placeholder="What do you need to do?" />
                                <button type="submit">Add &nbsp; <span>&#43;</span></button>
                                <?php }?>
                        </form>
                </div>

                <div class="show-todo-section">
                        <?php if ($todos->rowCount() <= 0) {?>
                        <div class="todo-item">
                                <div class="empty">
                                        <h1>empty</h1>
                                </div>
                        </div>
                        <?php }?>

                        <table id="todo-table" class="table table-striped">
                                <thead>
                                        <tr>
                                                <th>Title</th>
                                                <th>Date Created</th>
                                                <th>Action</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) {?>
                                        <tr>
                                                <td><?php echo $todo['title']; ?></td>
                                                <td><?php echo $todo['date_time']; ?></td>
                                                <td>
                                                        <span id="<?php echo $todo['id']; ?>"
                                                                class="remove-to-do">x</span>
                                                        <?php if ($todo['checked']) {?>
                                                        <input type="checkbox" class="check-box"
                                                                data-todo-id="<?php echo $todo['id']; ?>" checked />
                                                        <?php } else {?>
                                                        <input type="checkbox" class="check-box"
                                                                data-todo-id="<?php echo $todo['id']; ?>" />
                                                        <?php }?>
                                                </td>
                                        </tr>
                                        <?php }?>
                                </tbody>
                        </table>
                </div>

        </div>
        </div>

        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>


        <script>
        $(document).ready(function() {
                $('#todo-table').DataTable({
                        "paging": false,
                        "language": {
                                "paginate": {
                                        "previous": "Previous",
                                        "next": "Next"
                                }
                        }
                });

                $('.remove-to-do').click(function() {
                        const id = $(this).attr('id');
                        const row = $(this).closest('tr');

                        $.post("../app/remove.php", {
                                        id: id
                                },
                                function(data) {
                                        if (data === '1') {
                                                row.hide(600, function() {
                                                        row
                                                                .remove();
                                                });
                                        }
                                }
                        );
                });

                $(".check-box").click(function(e) {
                        const id = $(this).attr('data-todo-id');

                        $.post("../app/check.php", {
                                        id: id
                                },
                                (data) => {
                                        if (data != 'error') {
                                                const h2 = $(this).prev('h2');
                                                if (data === '1') {
                                                        h2.removeClass(
                                                                'checked'
                                                                );
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