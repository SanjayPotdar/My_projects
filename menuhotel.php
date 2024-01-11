<?php
session_start();

if (isset($_SESSION["name"])) {
    $businessName = $_SESSION["name"];
} else {
    $businessName = "Business Name Not Set";
}

if (isset($_GET['custname'])) {
    $customerName = $_GET['custname'];
} else {
    echo "Customer name not provided.";
    exit;
}

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
} else {
    echo "Customer id not provided.";
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

$sql = "SELECT o.`dish_name`, o.`quantity`
        FROM orders o
        WHERE o.d_id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $customerId);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Dish Details for Customer: $customerName<br>";
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>Dish Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["dish_name"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";

    echo '<form action="confirm.php" method="post">';
    echo '<input type="hidden" name="order_id" value="' . $customerId . '">';
    echo '<button type="submit" name="confirm_order" value="confirm">Confirm Order</button>';
    echo '</form>';

    echo '<form action="cancel.php" method="post">';
    echo '<input type="hidden" name="order_id" value="' . $customerId . '">';
    echo '<button type="submit" name="cancel_order" value="cancel">Cancel Order</button>';
    echo '</form>';
} else {
    echo "No dish details found for Customer: $customerName. ID: $customerId";
}

$stmt->close();
$conn->close();
?>
