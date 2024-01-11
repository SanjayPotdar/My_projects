<?php
session_start();
echo "<link rel='stylesheet' href='hotelsidemain.css'>";

if (isset($_SESSION["name"])) {
    $hotelName = $_SESSION["name"];
} else {
    echo "Hotel name not found in the session.";
    exit;
}

$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Retrieve the hotel ID based on the hotel name
$sql = "SELECT id FROM hotel WHERE name = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $hotelName); // Assuming `name` is a string

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $hotelId = $row["id"];
    }
} else {
    echo "Hotel not found for name: $hotelName";
    exit; // Exit if the hotel is not found
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form has been submitted
    if (isset($_POST["menu-item"]) && isset($_POST["menu-type"]) && isset($_POST["menu-price"])) {
        $menuItem = $_POST["menu-item"];
        $menuType = $_POST["menu-type"];
        $menuPrice = $_POST["menu-price"];

        // Prepare and execute the SQL statement to insert a new menu item
        $sql = "INSERT INTO menus (dish_name, dish_type, price, id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssdi", $menuItem, $menuType, $menuPrice, $hotelId);

        if ($stmt->execute()) {
           
            echo "<script>alert('Menu item added successfully.'); window.location.href = 'addmenu.html';</script>";

        } else {
            echo "<script>alert('Error adding menu item: " . $stmt->error . "'); window.location.href = 'addmenu.html';</script>";

        }
    } else {
        echo "<script>alert('Invalid form data. Please provide all required information.'); window.location.href = 'addmenu.html';</script>";
    }
}

$conn->close();
?>
