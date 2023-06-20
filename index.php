<!DOCTYPE html>
<html lang="en">
<?php include './database/connect.php' ?>

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>To-Do App</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <style>
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
                        padding: 8px;
                        line-height: 1.5;
                        vertical-align: middle;
                        border-top: 1px solid #dee2e6;
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
                                        <input type="text" name="title" style="border-color: #ff6666"
                                                placeholder="This field is required" />
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
                                                                <td style="text-align: center;">
                                                                        <?php echo $todo['title'] ?>
                                                                </td>
                                                                <td>
                                                                        <?php echo $todo['date_time'] ?>
                                                                </td>
                                                                <td>
                                                                        <span id="<?php echo $todo['id']; ?>"
                                                                                class="remove-to-do">x</span>
                                                                        <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>"
                                                                                class="check-box" />
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
                $(document).ready(function () {
                        $('#todo-table').DataTable();

                        $('.remove-to-do').click(function () {
                                const id = $(this).attr('id');

                                $.post("app/remove.php", {
                                        id: id
                                }, function (data) {
                                        if (data) {
                                                $(this).parent().parent().hide(600);
                                        }
                                });
                        });

                        $(".check-box").click(function (e) {
                                const id = $(this).attr('data-todo-id');

                                $.post('app/check.php', {
                                        id: id
                                }, function (data) {
                                        if (data !== 'error') {
                                                const row = $(this).closest("tr");
                                                const title = row.find("td:first").text();

                                                if (data === '1') {
                                                        row.addClass('done');
                                                        row.find("td:first").html('<del>' + title + '</del>');
                                                } else {
                                                        row.removeClass('done');
                                                        row.find("td:first").text(title);
                                                }
                                        }
                                });
                        });
                });
        </script>
</body>

</html>