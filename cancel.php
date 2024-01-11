<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'order_id' is set in the POST data
    if (isset($_POST["order_id"])) {
        $order_id = $_POST["order_id"];

        $servername = "localhost";
        $username = "Atharav";
        $password = "Atharav@31";
        $dbname = "project";

        // Create a new database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement to retrieve the order status
        $sql = "SELECT status FROM details WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($stmt === false) {
            die("Prepare failed: " . $conns->error);
        }

        // Execute the query

        // Check if the order with the provided ID exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row["status"];

            if ($status === 'pending' || $status === 'confirmed') {
                // Prepare the SQL statement to update the order status to 'cancelled'
                $updateStatusSql = "UPDATE details SET status='cancelled' WHERE id = ?";
                $updateStmt = $conn->prepare($updateStatusSql);

                if ($updateStmt === false) {
                    die("Prepare failed: " . $conn->error);
                }

                // Bind the order ID parameter for the update statement
                $updateStmt->bind_param("i", $order_id);

                // Execute the update query
                $updateStmt->execute();

                if ($updateStmt->error) {
                    // Display an error alert
                    echo '<script>alert("Error updating status: ' . $updateStmt->error . '");</script>';
                } else {
                    // Order has been cancelled, display a success alert
                    echo '<script>alert("Order has been cancelled.");</script>';
                    echo '<script>window.location.href = "customerbookinghtml.php";</script>'; // Redirect to customerbookinghtml.php

                }

                // Close the update statement
                $updateStmt->close();
            } elseif ($status === 'cancelled') {
                // Display an alert for an already cancelled order
                echo '<script>alert("This order is already cancelled. Order ID: ' . $order_id . '");</script>';
                echo '<script>window.location.href = "customerbookinghtml.php";</script>'; // Redirect to customerbookinghtml.php


            } else {
                // Display an alert for an order that cannot be cancelled
                echo '<script>alert("This order cannot be cancelled. Status: ' . $status . '");</script>';
                echo '<script>window.location.href = "customerbookinghtml.php";</script>'; // Redirect to customerbookinghtml.php

            }
        } else {
            // Display an alert for an order not found
            echo '<script>alert("Order not found. Order ID: ' . $order_id . '");</script>';
            echo '<script>window.location.href = "customerbookinghtml.php";</script>'; // Redirect to customerbookinghtml.php

        }

        // Close the prepared statement and the database connection
        $stmt->close();
        $conn->close();
    } else {
        // Display an alert for a missing order ID
        echo '<script>alert("Order ID not provided.");</script>';
    }
}
?>
