<?php
// Include your database configuration here
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

// Check if the 'username' parameter is set in the URL
if (isset($_GET['username'])) {
    $user = $_GET['username'];

    // Function to fetch business orders
    function getBusinessOrders($user) {
        global $servername, $username, $password, $dbname;
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT details.custname, details.person, details.date, details.time, details.status, 
        GROUP_CONCAT(orders.dish_name SEPARATOR ', ') AS dish_names, orders.quantity, details.confirm_time
        FROM details
        JOIN orders ON details.id = orders.d_id
        WHERE details.hotelname = '$user'
        GROUP BY details.custname, details.person, details.date, details.time, details.status, details.confirm_time";

        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    $orders = getBusinessOrders($user);
} else {
    // Handle the case where 'username' parameter is not set
    echo "Username not provided.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="action.css">
</head>
<body>
    <header id="header">
        <h1>Business Orders for <?php echo $user; ?></h1>
        <!-- Add a navigation menu or user info here -->
    </header>
    <nav id="nav">
            <ul>
                <li><a href="admin1.php?page=home">Home</a></li>
                <li><a href="admin1.php?page=view">View Resturants</a></li>
                <li><a href="admin1.php?page=add">Add Users</a></li>
                <li><a href="admin1.php?page=addr">Add Resturants</a></li>
                <li><a href="admin1.php?page=index">Logout</a></li>
            </ul>
        </nav>
    <main id="main">
    <section id="user-list">
            <h2>Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Capacity</th>
                        <th>Dish Name</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Confirm Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($orders->num_rows > 0) {
                        while ($row = $orders->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["custname"] . "</td>";
                            echo "<td>" . $row["person"] . "</td>";
                            echo "<td>";
                            $dishNames = explode(', ', $row["dish_names"]);
                            echo implode(', ', $dishNames);
                            echo "</td>";                            
                            echo "</td>";
                            echo "<td>" . $row["quantity"] . "</td>";
                            echo "<td>" . $row["date"] . "</td>";
                            echo "<td>" . $row["time"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td>" . $row["confirm_time"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No orders found for this user.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
