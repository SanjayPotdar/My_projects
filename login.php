<?php
session_start();

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
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["login-as"];

    // Check if it's an admin login
    if ($name === "admin" && $password === "123" && $type === "Admin") {
        // Admin login successful
        $_SESSION["name"] = "admin";
        header("Location: admin1.php"); // Redirect to admin dashboard
        exit;
    }

    // Basic authentication logic with user type check
    $sql = "SELECT * FROM users WHERE name = ? AND email = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User exists, retrieve user data
        $user = $result->fetch_assoc();

        if ($type === $user["user_type"]) {
            $_SESSION["id"] = $user["id"];
            $_SESSION["name"] = $name;
            
            if ($type === "customer") {
                header("Location: mainhtml.php");
                exit;
            } elseif ($type === "business") {
                // Retrieve the business name from the user data
                $sql = "SELECT name FROM hotel WHERE username = ?";
                $stmt = $conn->prepare($sql);
            
                if ($stmt === false) {
                    die("Prepare failed: " . $conn->error);
                }
            
                $stmt->bind_param("s", $name);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();
                    $businessName = $row["name"];
                    $_SESSION["name"] = $businessName; // Set the business name in the session
                    header("Location: customerbookinghtml.php");
                    exit;
                } else {
                    echo "<script>alert('Business not found in the database.'); window.location.href = 'loginBC.html';</script>";
                }

                $stmt->close();
            } elseif ($type === "admin") {
                header("Location: admin1.php");
                exit;
            } else {
                echo "<script>alert('Invalid user type.'); window.location.href = 'loginBC.html';</script>";
            }
        } else {
            echo "<script>alert('User type selected does not match the user's type in the database.'); window.location.href = 'loginBC.html';</script>";
        }
    } else {
        // Authentication failed
        echo "<script>alert('Login failed. Please check your credentials.'); window.location.href = 'loginBC.html';</script>";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid Request.'); window.location.href = 'loginBC.html';</script>";
}
?>
