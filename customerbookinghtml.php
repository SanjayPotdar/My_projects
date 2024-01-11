<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Booking</title>
    <link rel="stylesheet" href="customerbooking.css">
</head>

<body>
    <nav>
        <div>
            <a href="addmenu.html">Add Menu</a>
            <a href="viewmenu.php">View Menu</a>
        </div>
        <div class="profile-option">
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <span class="menu">
        <h1>Customer Booking</h1>
    </span>
    <div class="menu-container">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="status">Status: </label>
            <input type="radio" id="EMPTY" name="status" value="EMPTY" checked>
            <label for="EMPTY">EMPTY</label>
            <input type="radio" id="FULL" name="status" value="FULL">
            <label for="FULL">FULL</label>
            <input type="submit" value="Update Status">
            <script>
  function showStatusUpdateAlert(message) {
    alert(message);
  }
</script>

        </form>
        <table border="1">

            <tbody id="booking-data">
                <?php include("customerbooking.php"); ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; 2023 BookMyPlate. All rights reserved.</p>
    </footer>
</body>

</html>