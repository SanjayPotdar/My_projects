<?php
// Include your database configuration here
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

// Check if the 'delete' parameter is set in the URL
if (isset($_GET['delete']) && $_GET['delete'] == "success") {
    // Display the "Hotel deleted successfully" message
    echo "Hotel deleted successfully.";
}

if (isset($_GET['add']) && $_GET['add'] == "success") {
    // Display the "Hotel added successfully" message
    echo "Hotel added successfully.";
}
if (isset($_GET["add"]) && $_GET["add"] == "duplicate") {
    echo "A hotel already exists.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="action.css">
    <style>
    #user-list th:nth-child(5),
        #user-list td:nth-child(5) {
            width: 50px; /* Adjust the width as needed */
        }
</style>
</head>
<body>
    <header id="header">
        <h1>Admin Panel - Edit Hotels</h1>
        <!-- Add a navigation menu or user info here -->
    </header>
    <nav id="nav">
        <ul>
            <li><a href="admin1.php?page=home">Home</a></li>
            <li><a href="admin1.php?page=edit">View Users</a></li>
            <li><a href="admin1.php?page=add">Add Users</a></li>
            <li><a href="admin1.php?page=addr">Add Resturants</a></li>
            <li><a href="admin1.php?page=index">Logout</a></li>
        </ul>
    </nav>
    <main id="main">
        <section id="user-list">
            <h2>Edit Hotels</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Function to fetch all hotels
                    function getAllHotels() {
                        global $servername, $username, $password, $dbname;
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM hotel";
                        $result = $conn->query($sql);
                        $conn->close();
                        return $result;
                    }
                    
                    // Get all hotels and display them
                    $hotels = getAllHotels();
                    if ($hotels->num_rows > 0) {
                        while ($row = $hotels->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["type"] . "</td>";
                            echo "<td>" . $row["location"] . "</td>";
                            echo "<td>
                                <a href='delete_hotel.php?id=" . $row["id"] . "'><button class='action-button'>Delete</button></a>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hotels found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
