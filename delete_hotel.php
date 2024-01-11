<?php
// Include your database configuration here
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Get the hotel ID from the URL
    $hotelId = $_GET["id"];
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Delete records from the 'menus' table that reference the hotel
    $sqlDeleteMenus = "DELETE FROM menus WHERE id=?";
    $stmtDeleteMenus = $conn->prepare($sqlDeleteMenus);
    $stmtDeleteMenus->bind_param("i", $hotelId);
    
    // Use prepared statement to delete the hotel record
    $sqlDeleteHotel = "DELETE FROM hotel WHERE id=?";
    $stmtDeleteHotel = $conn->prepare($sqlDeleteHotel);
    $stmtDeleteHotel->bind_param("i", $hotelId);
    
    // Delete menus first
    if ($stmtDeleteMenus->execute()) {
        // Then delete the hotel
        if ($stmtDeleteHotel->execute()) {
            // Redirect back to edit_rest.php with a success message
            header("Location: edit_rest.php?delete=success");
            exit();
        } else {
            echo "Error deleting hotel: " . $stmtDeleteHotel->error;
        }
    } else {
        echo "Error deleting menus: " . $stmtDeleteMenus->error;
    }
    
    $conn->close();
} else {
    echo "Invalid request.";
}
?>