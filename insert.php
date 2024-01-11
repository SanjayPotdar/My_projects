<?php
session_start();

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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the session for the order details
    $customerName = isset($_SESSION["name"]) ? $_SESSION["name"] : "Not available";
    $hotelName = isset($_SESSION["selected_hotel_name"]) ? $_SESSION["selected_hotel_name"] : "Not available";
    $location = isset($_SESSION["location"]) ? $_SESSION["location"] : "Not available";
    $numPersons = isset($_SESSION["persons"]) ? $_SESSION["persons"] : "Not available";
    $orderDate = isset($_SESSION["date"]) ? $_SESSION["date"] : "Not available";
    $orderTime = isset($_SESSION["time"]) ? $_SESSION["time"] : "Not available";
    $totalPrice = isset($_SESSION["price"]) ? $_SESSION["price"] : 0; // Set a default value if not available
    $status = 'pending';
    $confirmationTime = isset($_SESSION["confirm_time"]) ? $_SESSION["confirm_time"] : "Not available";
    // Retrieve menu item data and item type from the session
    $selectedItemData = isset($_SESSION["selected_item_data"]) ? $_SESSION["selected_item_data"] : array();
    $itemTypes = isset($_SESSION["itemType"]) ? $_SESSION["itemType"] : array();
    $sql = "INSERT INTO details (`custname`, `hotelname`, `location`, `person`, `date`, `time`, `price`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssds", $customerName, $hotelName, $location, $numPersons, $orderDate, $orderTime, $totalPrice, $status);

    if ($stmt->execute() === TRUE) {
        // Retrieve the auto-generated ID of the last inserted record
        $lastInsertId = $conn->insert_id;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Include the PDF generation script
include('generate_pdf.php');

// Check if the PDF file was generated successfully
if (file_exists($pdfFileName)) {
    // Store the order confirmation time in the database
   // $confirmationTime = date("H:i"); // Get the current time in 24-hour format
    $sql = "UPDATE details SET confirm_time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $confirmationTime, $lastInsertId);
    $stmt->execute();

    // Close the database connection
    $conn->close();

    // Use PHP header to trigger the download and redirect
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . $pdfFileName);
    header('Content-Length: ' . filesize($pdfFileName));
    readfile($pdfFileName);
    ob_end_flush();
    $_SESSION["order_placed"] = true;

    // Redirect to 'mainhtml.php'
    header('Location: mainhtml.php');
} else {
    // PDF generation failed
    echo "PDF generation failed. No redirection.";
}
?>
