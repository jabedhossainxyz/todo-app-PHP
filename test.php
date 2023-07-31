<!DOCTYPE html>
<html lang="en">

<!-- Rest of the head and CSS styles -->

<body>
        <!-- Navbar and other sections -->

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
                <!-- Rest of the code to display tasks -->
        </div>

        <!-- Rest of the scripts -->
</body>

</html>
