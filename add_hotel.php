<?php
// Include your database configuration here
$servername = "localhost";
$dbUsername = "Atharav";
$dbPassword = "Atharav@31";
$dbname = "project";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the hotel details
    $hotelName = $_POST["hotel_name"];
    $hotelType = $_POST["hotel_type"];
    $hotelLocation = $_POST["hotel_location"];
    $hotelUsername = $_POST["hotel_username"];
    $hotelEmail = $_POST["hotel_email"];
    $userType = "business"; // Set the user type by default
    $password = "admin"; // Set the password as "admin"

    // Function to check if a hotel already exists
    function hotelExists($name, $type, $location, $username, $email) {
        global $servername, $dbUsername, $dbPassword, $dbname;

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a hotel with the same Name, Type, Location, Username, and Email exists
        $sql = "SELECT * FROM hotel WHERE name = ? AND type = ? AND location = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $type, $location);
        $stmt->execute();
        $stmt->store_result();

        // Check if a user with the same Username and Email exists
        $userSql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("ss", $username, $email);
        $userStmt->execute();
        $userStmt->store_result();

        $conn->close();

        return ($stmt->num_rows > 0 || $userStmt->num_rows > 0);
    }

    if (hotelExists($hotelName, $hotelType, $hotelLocation, $hotelUsername, $hotelEmail)) {
        // Redirect back to the page with an error message
        header("Location: edit_rest.php?add=duplicate");
        exit();
    }

    // Function to add a new hotel and user
    function addHotelAndUser($name, $type, $location, $username, $email, $userType, $password) {
        global $servername, $dbUsername, $dbPassword, $dbname;

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the new user into the user table
        $userSql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)";
        $userStmt = $conn->prepare($userSql);
        $userStmt->bind_param("ssss", $username, $email, $password, $userType);

        if ($userStmt->execute()) {
            // Get the user's ID
            $userId = $userStmt->insert_id;

            // Insert the new hotel into the hotel table
            $hotelSql = "INSERT INTO hotel (name, type, location, status, user_id) VALUES (?, ?, ?, 'EMPTY', ?)";
            $hotelStmt = $conn->prepare($hotelSql);
            $hotelStmt->bind_param("sssi", $name, $type, $location, $userId);

            if ($hotelStmt->execute()) {
                // Redirect back to the page with a success message
                header("Location: edit_rest.php?add=success");
                exit();
            } else {
                echo "Error adding hotel: " . $hotelStmt->error;
            }
        } else {
            echo "Error adding user: " . $userStmt->error;
        }

        $conn->close();
    }

    // Call the function to add the new hotel and user
    addHotelAndUser($hotelName, $hotelType, $hotelLocation, $hotelUsername, $hotelEmail, $userType, $password);
}

// Function to fetch available hotel types
function getHotelTypes() {
    global $servername, $dbUsername, $dbPassword, $dbname;

    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT DISTINCT type FROM hotel";
    $result = $conn->query($sql);
    $types = [];

    while ($row = $result->fetch_assoc()) {
        $types[] = $row["type"];
    }

    $conn->close();
    return $types;
}

$hotelTypes = getHotelTypes();
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
    <h1>Admin Panel - Add Hotel</h1>
</header>
<main id="main">
    <section id="add-user-form">
        <h2>Add Hotel</h2>
        <form method="post" action="add_hotel.php">
            <label for="hotel_name">Hotel Name:</label>
            <input type="text" id="hotel_name" name="hotel_name" required style="width: 300px; font-size: 16px; margin-bottom: 10px;">
            <br>
            <label for="hotel_type">Hotel Type:</label>
            <select id="hotel_type" name="hotel_type" required style="width: 300px; font-size: 16px; margin-bottom: 10px;">
                <?php
                foreach ($hotelTypes as $type) {
                    echo "<option value='$type'>$type</option>";
                }
                ?>
            </select>
            <br>
            <label for="hotel_location">Hotel Location:</label>
            <input type="text" id="hotel_location" name="hotel_location" required style="width: 300px; font-size: 16px; margin-bottom: 10px;">
            <br>
            <label for="hotel_username">Hotel Username:</label>
            <input type="text" id="hotel_username" name="hotel_username" required style="width: 300px; font-size: 16px; margin-bottom: 10px;">
            <br>
            <label for="hotel_email">Hotel Email:</label>
            <input type="email" id="hotel_email" name="hotel_email" required style="width: 300px; font-size: 16px; margin-bottom: 10px;">
            <br>
            <input type="submit" value="Add Hotel" class="action-button">
        </form>
    </section>
</main>
</body>
</html>
