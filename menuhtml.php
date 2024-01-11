<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="menu.css">
  <title>Hotel Menu</title>
</head>
<body>
  <nav>
    <div>
      <a href="mainhtml.php">Home</a>
      
      <a href="#">Contact</a>
    </div>
    <div class="profile-option">
      <a href="logout.php">Logout</a>
    </div>
  </nav>
  <div class="menu-container">
    <h1>Hotel Menu</h1>
    <table id="menu-table">
      <tbody>
        <?php include("menu.php"); ?>
      </tbody>
    </table>
  </div>
  <form action="review.php" method="post"> <!-- Use a form element with the action attribute set to "review.php" -->
    <button type="submit">Confirm Order</button> <!-- Use a submit button to trigger the form submission -->
  </form>
  <div id="total-price"></div>
</body>
</html>
