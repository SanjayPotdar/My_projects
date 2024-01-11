<?php
session_start();
echo "<link rel='stylesheet' href='history.css'>";

if (isset($_SESSION["name"])) {
    $name = $_SESSION["name"];
} else {
    echo "customer name not found in the session.";
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

// Assuming you have a user ID stored in the session, retrieve the user's orders
if (isset($_SESSION["name"])) {
    $userId = $_SESSION["name"];

    $sql = "SELECT hotelname, person, location, date, time, price, status FROM details WHERE custname = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $userId); // Assuming name is a string

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Previous Orders for User: $name</h2>";
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Hotel Name</th>
                        <th>Person</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

        $srno = 1; // Initialize the serial number

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $srno . "</td>"; // Add serial number
            echo "<td>" . $row["hotelname"] . "</td>";
            echo "<td>" . $row["person"] . "</td>";
            echo "<td>" . $row["location"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["time"] . "</td>";
            echo "<td>" . $row["price"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "</tr>";
            $srno++; // Increment the serial number
        }

        echo "</tbody></table>";
        echo  "<a href=mainhtml.php><button type=button class=back-button>BACK</button></a>";
    } else {
        echo "No previous orders found for User: $hotelName";
        echo "<a href='mainhtml.php'>Back to Main Page</a>";
    }

    $stmt->close();
}

$conn->close();
?>
