<?php
// Include your database configuration here
$servername = "localhost";
$dbUsername = "Atharav"; // Use a different variable name here
$dbPassword = "Atharav@31";
$dbname = "project";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];

    // Set the default password
    $defaultUserPassword = "admin";

    // Set the default user type to "customer"
    $userType = "customer";

    // Function to check if a user already exists
    function isUserExists($username, $email) {
        global $servername, $dbUsername, $dbPassword, $dbname;

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        $numRows = $stmt->num_rows;
        $stmt->close();
        $conn->close();

        return $numRows > 0;
    }

    // Check if the user already exists
    if (isUserExists($newUsername, $newEmail)) {
        // Redirect back to action.php with an error message
        header("Location: action.php?add=error&message=Username+or+email+address+already+taken.+Please+choose+different+credentials.");
        exit();
    }

    // Function to add a new user
    function addUser($username, $email, $password, $userType) {
        global $servername, $dbUsername, $dbPassword, $dbname;

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the new user into the database with the default password and user_type
        $sql = "INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $userType);

        if ($stmt->execute()) {
            // Redirect back to action.php with a success message
            header("Location: action.php?add=success");
            exit();
        } else {
            echo "Error adding user: " . $stmt->error;
        }

        $conn->close();
    }

    // Call the function to add the new user with the default password and user_type
    addUser($newUsername, $newEmail, $defaultUserPassword, $userType);
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
        <h1>Admin Panel - Add Users</h1>
    </header>
    <main id="main">
        <section id="add-user-form">
            <h2>Add User</h2>
            <form method="post" action="add_user.php">
                <label for="new_username">Username:</label>
                <input type="text" id="new_username" name="new_username" required style="width: 300px; font-size: 16px;">
                <br>
                <label for="new_email">Email:</label>
                <input type="email" id="new_email" name="new_email" required style="width: 300px; font-size: 16px;">
                <br>
                <input type="submit" value="Add User" class="action-button">
            </form>
        </section>
    </main>
</body>
</html>
