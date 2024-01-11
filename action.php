<?php
// Include your database configuration here
$servername = "localhost";
$username = "Atharav";
$password = "Atharav@31";
$dbname = "project";

// Check if the 'delete' parameter is set in the URL
if (isset($_GET['delete']) && $_GET['delete'] == "success") {
    // Display the "User deleted successfully" message
    echo "User deleted successfully.";
}


if (isset($_GET['add'])) {
    if ($_GET['add'] == "success") {
        // Display the "User added successfully" message
        echo "User added successfully.";
    } elseif ($_GET['add'] == "error") {
        // Display the error message
        $errorMessage = urldecode($_GET['message']);
        echo "Error: " . $errorMessage;
    }
}
// Add a new section to handle user type-based routing
if (isset($_GET['usertype']) && $_GET['usertype'] === "customer") {
    // Redirect to the customer order view page
    header("Location: view_customer_orders.php?username=" . $_GET['username']);
    exit;
} elseif (isset($_GET['usertype']) && $_GET['usertype'] === "business") {
    // Redirect to the business order view page
    header("Location: view_business_orders.php?username=" . $_GET['username']);
    exit;
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
            <h1>Admin Panel - View Users</h1>
        </header>
        <nav id="nav">  
            <ul>
                <li><a href="admin1.php?page=home">Home</a></li>
                <li><a href="admin1.php?page=view">View Resturants</a></li>
                <li><a href="admin1.php?page=add">Add Users</a></li>
                <li><a href="admin1.php?page=addr">Add Resturants</a></li>
                <li><a href="admin1.php?page=index">Logout</a></li>
            </ul>
        </nav>
        <main id="main">
            <!-- Add your admin features here -->
            <!-- Example: User List -->
            <section id="user-list">
                <h2>View Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th id="actions-header">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Function to fetch all users
                        function getAllUsers() {
                            global $servername, $username, $password, $dbname;
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM users";
                            $result = $conn->query($sql);
                            $conn->close();
                            return $result;
                        }
                        
                        $users = getAllUsers();
                        if ($users->num_rows > 0) {
                            while ($row = $users->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["user_type"] . "</td>";
                                echo "<td>";
                        
                                if ($row["user_type"] !== "Admin") {
                                    echo "<a href='delete_user.php?id=" . $row["id"] . "'><button class='action-button'>Delete</button></a>";
                                }
                        
                                if ($row["user_type"] === "customer") {
                                    echo "<a href='action.php?usertype=customer&username=" . $row["name"] . "'><button class='action-button' style='width: 150px;'>View Orders</button></a>";
                                } elseif ($row["user_type"] === "business") {
                                    echo "<a href='action.php?usertype=business&username=" . $row["name"] . "'><button class='action-button' style='width: 150px;'>View Orders</button></a>";
                                }
                        
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>                        
                    </tbody>
                </table>
            </section>
        </main>
    </body>
    </html>
