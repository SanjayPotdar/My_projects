<?php
// Include your database configuration here
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Function to delete a user by ID
    function deleteUserById($id) {
        global $servername, $username, $password, $dbname;

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM users WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirect back to action.php with a success message
            header("Location: action.php?delete=success");
            exit();
        } else {
            echo "Error deleting user: " . $conn->error;
        }

        $conn->close();
    }

    // Call the function to delete the user
    deleteUserById($user_id);
} else {
    echo "User ID not provided.";
}
?>
