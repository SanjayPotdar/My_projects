<?php
// Start the session at the very beginning of your script
session_start();

// Define your database connection parameters
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$database = "project";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input data
    $location = $_POST["location"];
    $Type = $_POST["Type"];
    $date = $_POST["date"];
    $time = $_POST["time"];
    $persons = $_POST["persons"];

    // Store form input data in session variables
    $_SESSION["location"] = $location;
    $_SESSION["Type"] = $Type; 
    $_SESSION["date"] = $date; 
    $_SESSION["time"] = $time; 
    $_SESSION["persons"] = $persons; 
}

// Fetch locations from the "hotel" table
$sql = "SELECT DISTINCT location FROM hotel";
$result = $conn->query($sql);
$locationOptions = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locationName = $row["location"];
        $locationOptions .= "<option value='$locationName'>$locationName</option>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css">
  <title>My Profile Page</title>
</head>
<body>
  <form action="" method="post">
    <nav>
      <div>
       <a href="historyhtml.php">History</a>&nbsp;&nbsp;
        <a href="cancel.pdf">Cancellation Policy</a>&nbsp;&nbsp;
        <a href='checkstatus.html'>Check Status</a>
      </div>
      <div class="profile-option">
        <a href="logout.php">Logout</a>
      </div>
    </nav>
    <div class="flex-container">
      <div class="page-content">
        <h1>Select Your Desirable Hotel</h1>
        <div>
          <label for="location">Location:</label>
          <select id="location" name="location">
            <option value="Select">Select</option>
            <?php echo $locationOptions; ?>
          </select>
          <label>Type:</label>
          <div class="flex">
            <input type="radio" id="Veg" name="Type" value="Veg" required>
            <label for="Veg">Veg</label>
            
            <input type="radio" id="Non-Veg" name="Type" value="Non-Veg" required>
            <label for="Non-Veg">Non-Veg</label>
            
            <input type="radio" id="Both" name="Type" value="Both" required>
            <label for="Both">Both</label>
          </div>
        </div>
        <div>
          <label for="date">Date:</label>
          <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div>
          <label for="time">Time:</label>
          <input type="time" id="time" name="time" required>
          <!-- <input type="time" id="time" name="time" min="10:00" max="22:00" required> -->

        </div>
        <div>
          <label for "persons">Number of Persons:</label>
          <input type="number" id="persons" name="persons" min="1" max="50" required>
        </div>
        <button type="submit">Fetch Hotels</button>
      </div>
      <div class="hotel-list">
        <h2>Hotels</h2>
        <table id="hotel-table">
          <thead>
            <tr>
              <th>Hotel Name</th>
              <th>Type</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php include("main.php"); ?>
            <?php
    // Check if the order placed flag is set in the session
    if (isset($_SESSION["order_placed"]) && $_SESSION["order_placed"] === true) {
        // Display an alert
        echo '<script>alert("Your order has been placed!");</script>';

        // Reset the order placed flag
        $_SESSION["order_placed"] = false;
    }
    ?>
          </tbody>
        </table>
      </div>
    </div>
  </form>
  <footer>
    <p>&copy; 2023 BookMyPlate. All rights reserved.</p>
  </footer>
</body>
</html>
