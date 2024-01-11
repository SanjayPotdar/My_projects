<link rel="stylesheet" href="demo.css">
<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the customer's username from the session (adjust this as needed)
    $customer_username = isset($_SESSION["name"]) ? $_SESSION["name"] : "";

    if (empty($customer_username)) {
        echo "Customer not logged in.";
        exit;
    }

    // Define your database connection variables
    $servername = "localhost";
    $username = "Atharav";
    $password = "Atharav@31";
    $dbname = "project";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, custname, status, confirm_time FROM details WHERE custname = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $customer_username, $order_id);

    // Assuming you also need to get the order_id from the form
    $order_id = isset($_POST["order_id"]) ? $_POST["order_id"] : "";

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Order Details</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Order ID</th><th>Customer Name</th><th>Status</th><th>Action</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $order_id = $row["id"];
            $customer_name = $row["custname"];
            $status = $row["status"];
            $confirmation_time = strtotime($row["confirm_time"]);

            echo "<tr>";
            echo "<td>$order_id</td>";
            echo "<td>$customer_name</td>";
            echo "<td>$status</td>";
            echo "<td>";

            if ($status === "pending") {
                $current_time = time();

                if ($confirmation_time > $current_time) {
                    echo '<form action="cancelcust.php" method="post">';
                    echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
                    echo '<button type="submit" name="cancel_order" value="cancel">Cancel Order</button>';
                    echo '</form>';
                } else {
                    echo "This order cannot be canceled because the confirmation time has passed.";
                }
            } else {
                echo "This order cannot be deleted.";
            }

            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "Current Time: " . date("Y-m-d H:i:s") . "<br>";
        echo "Confirmation Time: " . date("Y-m-d H:i:s", $confirmation_time) . "<br>";
        echo  "<a href=mainhtml.php><button type=button class=back-button>BACK</button></a>";



    } else {
        echo "<script>alert('Order not found for the logged-in customer.'); window.location.href = 'checkstatus.html';</script>";
    }

    $conn->close();
}
?>
