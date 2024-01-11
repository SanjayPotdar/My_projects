<?php
// Start the session at the beginning of your script
session_start();

// Define Database Connection Variables
$servername = "localhost";
$username = "Atharav"; // Replace with your database username
$password = "Atharav@31"; // Replace with your database password
$dbname = "project"; // Replace with your database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check if the hotel ID is provided in the URL
if (isset($_GET["id"])) {
    $hotelId = $_GET["id"];
    $sql = "SELECT name FROM hotel WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hotelId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hotelName = $row["name"];

            // Store the hotel name in a session variable
            $_SESSION["selected_hotel_name"] = $hotelName;
        }
    }
    //var_dump($hotelName);
    $sql = "SELECT menu_id, dish_name, dish_type, price FROM menus WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hotelId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<form method='post'>"; // Start a form
            echo "<table>"; // Start the table
            echo "<thead><tr><th>Dish Name</th><th>Dish Type</th><th>Quantity</th><th>Price</th><th>Select</th></tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                $itemId = $row["menu_id"];
                $itemtype = $row["dish_type"];
                $itemName = $row["dish_name"];
                $itemPrice = $row["price"];

                // Display a table row for each menu item with checkboxes, quantity input, and price
                echo "<tr>";
                echo "<td>$itemName</td>";
                echo "<td>$itemtype</td>";
              
                echo "<td><input type='number' name='quantity[$itemId]' value='1' min='1' style='width: 30px;'></td>"; // Quantity input field with reduced width
                echo "<td>$itemPrice RS</td>";
                echo "<td><input type='checkbox' name='selected_items[]' value='$itemId'></td>";
                echo "</tr>";
                
                // Store selected item data in an array if it's checked
                if (isset($_POST["selected_items"]) && in_array($itemId, $_POST["selected_items"])) {
                    $quantity = isset($_POST["quantity"][$itemId]) ? intval($_POST["quantity"][$itemId]) : 1;
                    $selectedItemData[] = array(
                        "item_id" => $itemId,
                        "item_name" => $itemName,
                        "item_type" => $itemtype,
                        "item_price" => $itemPrice,
                        "quantity" => $quantity
                    );
                    $_SESSION["dish_type"] = $itemtype;
                }
            }

            echo "</tbody>";
            echo "</table>"; // End the table
            echo "<button type='submit' onclick='show()'>ADD</button>"; // Add a submit button
            echo "</form>"; // End the form
        } else {
            echo "No menu items found for this hotel.";
        }
    } else {
        // Handle the database query error more gracefully (e.g., display a user-friendly message)
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selected_items"])) {
        // Capture the selected items from the POST data
        $selectedItems = $_POST["selected_items"];

        // Store the selected items in a session variable
        $_SESSION["selected_items"] = $selectedItems;
        
        // Store additional item data in a session variable
        $_SESSION["selected_item_data"] = $selectedItemData;

        // Store quantity data in a session variable
        $_SESSION["quantity"] = $_POST["quantity"];
    }
}
?>
<script>
    function show(){
        alert("Items added !!");
    }
</script>