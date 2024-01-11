<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the order ID from the form if available
    $order_id = isset($_POST["order_id"]) ? $_POST["order_id"] : null;

    // Define your database connection variables
    $servername = "localhost";
    $username = "Atharav";
    $password = "Atharav@31";
    $dbname = "project";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the "order_id" is set and not null
    if ($order_id !== null) {
        // Check the order status
        $sql = "SELECT status FROM details WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row["status"];

            if ($status === 'pending') {
                // Offer the option to confirm or cancel the order
                if (isset($_POST["confirm_order"])) {
                    // Handle the confirmation of the order
                    $updateStatusSql = "UPDATE details SET status='confirmed' WHERE id = ?";
                    $stmt = $conn->prepare($updateStatusSql);
                    $stmt->bind_param("i", $order_id);
                    $stmt->execute();

                    if ($stmt->error) {
                        echo "Error: " . $stmt->error;
                    } else {
                        echo "Order has been confirmed.";
                    }
                } elseif (isset($_POST["cancel_order"])) {
                    // Code to delete the order from the 'orders' table
                    $deleteOrdersSql = "DELETE FROM orders WHERE d_id = ?";
                    $stmt = $conn->prepare($deleteOrdersSql);
                    $stmt->bind_param("i", $order_id);
                    $stmt->execute();

                    if ($stmt->error) {
                        echo "Error: " . $stmt->error;
                    } else {
                        // Now delete the order from the 'details' table
                        $deleteDetailsSql = "DELETE FROM details WHERE id = ?";
                        $stmt = $conn->prepare($deleteDetailsSql);
                        $stmt->bind_param("i", $order_id);
                        $stmt->execute();

                        if ($stmt->error) {
                            echo "Error: " . $stmt->error;
                        } else {
                            echo "Order has been cancelled.";
                        }
                    }
                }
            } else {
                echo "This order cannot be confirmed. Status: $status";
            }
        } else {
            echo "Order not found. Order ID: $order_id";
        }
    } else {
        echo "Order ID not provided.";
    }

    // Close the database connection
    $conn->close();
}
?>
