<?php
session_start();
// Check if the business name is set in the session
if (isset($_SESSION["name"])) {
    $businessName = $_SESSION["name"];
} else {
    $businessName = "Business Name Not Set";
}

// Define your database connection variables
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
if (isset($_POST["status"])) {
    // Get the selected status value
    $selectedStatus = $_POST["status"];
    
    // Update the status in the "hotel" table for the specific business name
    $updateSql = "UPDATE hotel SET status = ? WHERE name = ?";
    
    // Prepare the statement
    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    // Bind parameters and execute the update statement
    $updateStmt->bind_param("ss", $selectedStatus, $businessName);
    $updateStmt->execute();

    // Close the update statement
    $updateStmt->close();
    $message = 'Status has been updated to ' . $selectedStatus;

    // Call the JavaScript function to show the alert
    echo '<script>showStatusUpdateAlert("' . $message . '");</script>';
}

// SQL query to fetch data from the "orders" and "details" tables for a specific hotel with GROUP BY
$sql = "SELECT d.custname, o.`dish_name` AS menu_item, d.`price` AS total_price, d.person, d.date, d.time,d.status,d.id
        FROM orders o
        JOIN details d ON o.d_id = d.id
        JOIN menus m ON o.`dish_name` = m.`dish_name`
        WHERE d.hotelname = '$businessName'
        GROUP BY o.d_id, d.custname";

// Prepare the statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Execute the query
$stmt->execute();

$result = $stmt->get_result();

// Check if there are rows returned
if ($result->num_rows > 0) {
    echo "Business Name: " . $businessName; // Display the business name

    // Output data of each row
    echo "<table border='1'>
    <thead>
        <tr>
        
            <th>id</th>
            <th>Customer Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Number of Persons</th>
            <th>Total Price</th>
            <th>status</th>
        </tr>
    </thead>
    <tbody>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        
        echo "<td><a href='menuhotelhtml.php?custname=" . $row["custname"]  . "&id=" . $row["id"] . "'>" . $row["custname"] . "</a></td>";
        echo "<td>" . $row["date"] . "</td>"; // Display date
        echo "<td>" . $row["time"] . "</td>"; // Display time
        echo "<td>" . $row["person"] . "</td>";
        echo "<td>" . $row["total_price"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No data found for Hotel $businessName.";
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>