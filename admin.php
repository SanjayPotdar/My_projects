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
    $usernameOrEmail = $_POST["username"];
    $password = $_POST["password"];

    // Hash the password (assuming your passwords are stored as hashes)
    $hashedPassword = hash("sha256", $password);

    // Prepare and execute the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $usernameOrEmail, $hashedPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Authentication successful
        // Check if the user has admin privileges (adjust the column name as per your database)
        $userRow = $result->fetch_assoc();
        if ($userRow["role"] === "admin") {
            // Redirect to the admin panel
            header("Location: admin.html"); 
            exit();
        } else {
            // User does not have admin privileges
            echo "You do not have admin privileges.";
        }
    } else {
        // Authentication failed
        echo "Login failed. Please check your credentials.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}else {
    echo "Invalid request.";
}
?>
