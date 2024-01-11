<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <div id="sidebar">
        <h2 id="a1">Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="?page=edit"><button>View Users</button></a></li>
                <li><a href="?page=view"><button>View Restaurants</button></a></li>
                <li><a href="?page=add"><button>Add Users</button></a></li>
                <li><a href="?page=addr"><button>Add Restaurants</button></a></li>
                <li><a href="?page=index"><button>Logout</button></a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        switch ($page)
        {
            case 'edit':
                include('action.php');
                break;
            case 'view':
                include('edit_rest.php');
                break;
            case 'add':
                include('add_user.php');
                break;
            case 'addr':
                include('add_hotel.php');
                break;
            default:
            $servername = "localhost";
            $username = "Atharav";
            $password = "Atharav@31";
            $dbname = "project";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch user count
                $userCount = 0; // Initialize to 0
                $userQuery = "SELECT COUNT(*) AS count FROM users";
                $userResult = $conn->query($userQuery);
                if ($userResult->num_rows > 0) {
                    $userData = $userResult->fetch_assoc();
                    $userCount = $userData['count'];
                }

                // Fetch hotel count
                $hotelCount = 0; // Initialize to 0
                $hotelQuery = "SELECT COUNT(*) AS count FROM hotel";
                $hotelResult = $conn->query($hotelQuery);
                if ($hotelResult->num_rows > 0) {
                    $hotelData = $hotelResult->fetch_assoc();
                    $hotelCount = $hotelData['count'];
                }

                // Close the database connection
                $conn->close();
                
                // Display the counts and welcome message
                echo "<div class='welcome-message'>Welcome Admin</div>";
                echo "<p class='count'>Number of Users Registed with us: $userCount</p>";
                echo "<p class='count'>Number of Hotels Registed with us: $hotelCount</p>";
                break;
        }
        ?>
    </div>
</body>
</html>
