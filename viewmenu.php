<?php
session_start();
echo"<link rel= stylesheet href =demo.css>";

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

$stmt->close();

// Step 2: Get Menu Items with Price (Make sure the column names match your database)
$sql = "SELECT dish_name,dish_type, price FROM menus WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $hotelId); // Assuming `hotel_id` is an integer

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Menu for Hotel: $hotelName</h2>";
    $srno=1;
    echo "<table border='1'>
            <thead>
                <tr>
                    <th> Srno</th>
                    <th>Dish Name</th>
                    <th>Type</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo"<td>".$srno."</td>";
        echo "<td>" . $row["dish_name"] . "</td>";
        echo "<td>". $row["dish_type"] ."</td>";
        echo "<td>Rs. " . number_format($row["price"], 2) . "</td>";
        echo "</tr>";
        $srno++;
    }

    echo "</tbody></table>";
    echo  "<a href=customerbookinghtml.php><button type=button class=back-button>BACK</button></a>";
} else {
    echo "No menu items found for Hotel: $hotelName";
}

$stmt->close();
$conn->close();
?>
