<?php
// Check if the form was submitted
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
    $un = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $usertype = $_POST["var"];

    // Basic validation (you can add more)
    if ($password != $confirm_password) {
        echo "Password and confirm password do not match.";
    } else {
        // Check if the username already exists
        $check_user_sql = "SELECT * FROM users WHERE name = ?";
        $check_user_stmt = $conn->prepare($check_user_sql);
        $check_user_stmt->bind_param("s", $un);
        $check_user_stmt->execute();
        $result = $check_user_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username already exists. Please try again with a different username.";
        } else {
            // Insert the data into the database
            $insert_sql = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssss", $un, $email, $password, $usertype);

            if ($insert_stmt->execute()) {
                header("Location: LoginBC.html");
                exit();
            } else {
                echo "Error: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        }
        $check_user_stmt->close();
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
