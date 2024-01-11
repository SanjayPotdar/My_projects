<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection settings (modify with your credentials)
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

    // Retrieve data from the form
    $restaurantName = $_POST["restaurant-name"];
    $usernames = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];
    $location = $_POST["location"];
    $status="EMPTY";
   

    // Check if the username already exists in the "users" table
    $checkUserSql = "SELECT * FROM users WHERE name = ?";
    $checkUserStmt = $conn->prepare($checkUserSql);
    $checkUserStmt->bind_param("s", $usernames);
    $checkUserStmt->execute();
    $result = $checkUserStmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists. Please try again with a different username.";
    } else {
        // Insert data into the user table
        $userInsertSql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
        $userInsertStmt = $conn->prepare($userInsertSql);

        if ($userInsertStmt === false) {
            die("User Prepare failed: " . $conn->error);
        }

        $userType = 'business'; // Assuming all signups are for business

        $userInsertStmt->bind_param("ssss", $usernames, $email, $password, $userType);

        if ($userInsertStmt->execute()) {
            // User data inserted successfully

            // Now, insert data into the hotel table
            $hotelInsertSql = "INSERT INTO hotel (id, username, name,location, type, status) VALUES (?, ?,?, ?, ?, ?)";
            $hotelInsertStmt = $conn->prepare($hotelInsertSql);

            if ($hotelInsertStmt === false) {
                die("Hotel Prepare failed: " . $conn->error);
            }

            $userId = $conn->insert_id;

            $hotelInsertStmt->bind_param("isssss", $userId,$usernames,$restaurantName, $location, $type, $status);

            if ($hotelInsertStmt->execute()) {
                echo "Business signup successful.";
                header("Location: LoginBC.html");
            } else {
                echo "Error inserting data into the hotel table: " . $hotelInsertStmt->error;
            }
        } else {
            echo "Error inserting data into the user table: " . $userInsertStmt->error;
        }

        $userInsertStmt->close();
        $hotelInsertStmt->close();
    }

    // Close the database connection
    $checkUserStmt->close();
    $conn->close();
}
?>
