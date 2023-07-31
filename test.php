
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head content -->
</head>

<body>
    <ul class="navbar">
        <!-- Navbar links -->
    </ul>
    <div class="main-section">
        <!-- Your existing HTML content here -->

        <?php if ($todos->rowCount() <= 0) { ?>
            <!-- Empty todos message -->
            <div class="todo-item">
                <div class="empty">
                    <img src="img/f.png" width="100%" />
                    <img src="img/Ellipsis.gif" width="80px">
                </div>
            </div>
        <?php } else { ?>
            <!-- Todo table -->
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
                            <td><?php echo date('Y-m-d h:i A', strtotime($todo['date_time'])); ?></td>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Your JavaScript code here
    </script>
</body>

</html>
