<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="review.css">
  <title>Review Page</title>
  <style>
    /* Add your CSS styles here */
  </style>
</head>

<body>
  <form action="insert.php" method="post">
    <nav>
      <div>
        <a href="mainhtml.php">Home</a>
        <a href="cancel.pdf">Cancellation Policy</a>
        <a href="contact.html">Contact</a>
      </div>
      <div>
        <a href="logout.php">Logout</a>
      </div>
    </nav>

    <div class="menu-container">
      <!-- <div class="current-time" align-left>
  <?php
  date_default_timezone_set("Asia/Kolkata");
  $current_time = date("H:i");
  $current_date = date(" d F Y");
  echo "Current Time: " . $current_time;
  echo "" . $current_date . "";
  ?>
</div> -->
      <div>
        <h2>Review Your Order</h2>
        <!-- <p id="order-id">Order Id:<?php echo isset($_SESSION["id"]) ? $_SESSION["id"] : "Not available"; ?></p> -->
        <p id="customer-name">Customer Name:
          <?php echo isset($_SESSION["name"]) ? $_SESSION["name"] : "Not available"; ?>
        </p>
        <p id="customer-name">Number of persons:
          <?php echo isset($_SESSION["persons"]) ? $_SESSION["persons"] : "Not available"; ?>
        </p>
        <p id="hotel-name">Hotel Name:
          <?php echo isset($_SESSION["selected_hotel_name"]) ? $_SESSION["selected_hotel_name"] : "Not available"; ?>
        </p>
        <p id="hotel-location">Location:
          <?php echo isset($_SESSION["location"]) ? $_SESSION["location"] : "Not available"; ?>
        </p>

        <p id="menu-selected">Menu Selected:
        <ul>
          <?php
          $totalPrice = 0; // Initialize total price
          $totalQuantity = 0; // Initialize total quantity
          if (isset($_SESSION["selected_item_data"])) {
            $selectedItemData = $_SESSION["selected_item_data"];
            foreach ($selectedItemData as $item) {
              $itemId = $item['item_id'];
              $itemName = $item['item_name'];
              $itemPrice = $item['item_price'];
              $quantity = $_SESSION["quantity"][$itemId]; // Get quantity from session
              $itemType = $item['item_type']; // Get the item type
          
              // Update total quantity
              $totalQuantity += $quantity;
              echo "<input type='hidden' name='item_type[$itemId]' value='$itemType'>";

              // Display quantity and dish name
              echo "<li>$itemName X $quantity - Price: " . ($itemPrice * $quantity) . " Rs</li>";

              // Update total price
              $totalPrice += ($itemPrice * $quantity);
            }
          } else {
            echo "<li>Not available</li>";
          }
          ?>
        </ul>
        </p>
        <p id="total-quantity">Total Quantity:
          <?php echo $totalQuantity; ?>
        </p>
        <p id="order-date">Date:
          <?php echo isset($_SESSION["date"]) ? $_SESSION["date"] : "Not available"; ?>
        </p>
        <p id="order-time">Time:
          <?php echo isset($_SESSION["time"]) ? $_SESSION["time"] : "Not available"; ?>
        </p>
        <p id="order-price">Total Price:
          <?php echo $totalPrice; ?> Rs
        </p> <!-- Display the total price -->
        <?php $_SESSION["price"] = $totalPrice ?>
        <p id="future-time">
          The Order can be Cancelled Till:
          <?php
          date_default_timezone_set("Asia/Kolkata");
          // Calculate the current time
          $current_time = time();

          // Calculate the future time by adding 30 minutes (1800 seconds) to the current time
          $future_time = strtotime('+30 minutes', $current_time);

          // Format the current and future times as human-readable dates
          $current_time_formatted = date("H:i", $current_time);
          $future_time_formatted = date("H:i", $future_time);
          echo $future_time_formatted;
          $_SESSION["confirm_time"] = $future_time_formatted ?>
        </p>

        <label>
          <input type="checkbox" name="agree" required>
          I agree to the<a href="cancel.pdf" target="_blank">TERMS AND CONDITIONS</a>
        </label><br>
        <!-- </div> -->
        <button type="submit" onclick="showAlert()">Check Out</button><br>
        <a href="mainhtml.php"><button type="button">Cancel</button></a>
      </div>
  </form>
  <script>
    // Define a JavaScript function to handle button click
    function showAlert() {
      // Redirect to main.html first
      //event.preventDefault();
      alert("Place Order");

    // Redirect to main.html first
   

      // Display an alert after the redirection
      setTimeout(function() {
        window.location.href = "mainhtml.php";
      }, 1000); // Adjust the delay (in milliseconds) as needed
      
    }
  </script>
</body>

</html>