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
        </div>
        <div class="profile-option">
          <a href="logout.php">Logout</a>
        </div>
      </nav>
      <span class="menu">
      <h1>Customer Booking</h1>
      </span>
<div class="menu-container">
    <table border="1">
        <tbody id="booking-data">
           <?php include("menuhotel.php") ?>
        </tbody>
    </table>
    
    
</div>
<footer>
    <p>&copy; 2023 Customer Booking</p>
</footer> 
</body>
</html>