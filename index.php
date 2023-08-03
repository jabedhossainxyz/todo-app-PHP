<?php
require './database/connect.php';

$todos = $conn->query("SELECT * FROM todos WHERE checked = 0 ORDER BY id DESC");

if (isset($_POST['title'])) {
    $title = $_POST['title'];

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO todos (title, date_time) VALUES (?, NOW())");
        $stmt->execute([$title]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>To-Do App</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <style>
                body {
                        background: #68a7ac9e;
                        padding: 2px;
                        margin: 10px;
                }

                .main-section {
                        width: 100%;
                }

                ul.navbar {
                        list-style-type: none;
                        margin: 0;
                        padding: 0;
                        overflow: hidden;
                        background-color: #05697cdb;
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
        <ul class="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="auth\login.php">Login</a></li>
                <li><a href="auth\register.php">Registration</a></li>
        </ul>
        <div class="main-section">
                <div class="add-section">
                        <form action="app/add.php" method="POST" autocomplete="off">
                                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                                        <input type="text" name="title" style="border-color: #ff6666" placeholder="This field is required" />
                                        <button type="submit">Add &nbsp; <span>&#43;</span></button>
                                <?php } else { ?>
                                        <input type="text" name="title" placeholder="What do you need to do?" />
                                        <button type="submit">Add &nbsp; <span>&#43;</span></button>
                                <?php } ?>
                        </form>
                </div>
                <?php
                $todos = $conn->query("SELECT * FROM todos WHERE checked = 0 ORDER BY id DESC");
                ?>
                <div class="show-todo-section">
                        <?php if ($todos->rowCount() <= 0) { ?>

                                <div class="todo-item">
                                        <div class="empty">
                                                <img src="img/f.png" width="100%" />
                                                <img src="img/Ellipsis.gif" width="80px">
                                        </div>
                                </div>
                        <?php } else { ?>
                                <table id="todo-table" class="table table-striped">
                                        <thead>
                                                <tr>
                                                        <th>Title</th>
                                                        <th>Date Created</th>
                                                        <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <tr>
                                                                <td><?php echo $todo['title'] ?></td>
                                                                <td><?php echo date('Y-m-d h:i A', strtotime($todo['date_time'])); ?>
                                                                </td>
                                                                <td>
                                                                        <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                                                                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                                                                </td>
                                                        </tr>
                                                <?php } ?>
                                        </tbody>
                                </table>
                        <?php } ?>
                </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>


        <script>
                $(document).ready(function() {
                        $('#todo-table').DataTable({
                                "paging": false, // Disable pagination
                                "language": {
                                        "paginate": {
                                                "previous": "Previous",
                                                "next": "Next"
                                        }
                                }
                        });

                        $('.remove-to-do').click(function() {
                                const id = $(this).attr('id');
                                const row = $(this).closest(
                                        'tr'); // Find the nearest tr element

                                $.post("app/remove.php", {
                                                id: id
                                        },
                                        function(data) {
                                                if (data === '1') {
                                                        row.hide(600, function() {
                                                                // After hiding the row, remove it from the DOM completely
                                                                row
                                                                        .remove();
                                                        });
                                                }
                                        }
                                );
                        });

                        $(".check-box").click(function(e) {
                                const id = $(this).attr('data-todo-id');
                                const isChecked = $(this).prop(
                                        'checked'); // Get the checked status of the checkbox

                                $.post('app/check.php', {
                                                id: id,
                                                checked: isChecked // Send the checked status to the server
                                        },
                                        (data) => {
                                                if (data != 'error') {
                                                        const h2 = $(this).next();
                                                        if (isChecked) {
                                                                h2.addClass('checked');
                                                        } else {
                                                                h2.removeClass(
                                                                        'checked'
                                                                );
                                                        }
                                                }
                                        }
                                );
                        });
                });
        </script>

</body>

</html>