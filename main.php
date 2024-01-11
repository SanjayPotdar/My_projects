<?php
// Check if a session is already active
// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nop = $_POST["persons"];
    $bookingDate = $_POST["date"];
    $bookingTime = $_POST["time"];
    $location = $_POST["location"];
    $type = $_POST["Type"];

    // Database connection settings (modify with your credentials)
    $servername = "localhost";
    $username = "Atharav";
    $password = "Atharav@31";
    $dbname = "project";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function getMatchingHotels($conn, $location, $type)
    {
        $sql = "SELECT * FROM hotel WHERE location = ? AND type = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $location, $type);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    $matchingHotels = getMatchingHotels($conn, $location, $type);

    if ($matchingHotels->num_rows > 0) {
        while ($row = $matchingHotels->fetch_assoc()) {
            echo "<tr>";
            // Make the hotel name clickable only if the status is not "full"
            $hotelName = $row["name"];
            $_SESSION["hotel_name"] = $hotelName; // Store hotel name in a session variable
            $status = $row["status"];
            
            if ($status === "FULL") {
                echo "<td>" . $hotelName . "</td>";
            } else {
                echo "<td><a href='menuhtml.php?id=" . $row["id"] . "'>$hotelName</a></td>";
            }
            
            echo "<td>" . $row["type"] . "</td>";
            echo "<td>" . $status ."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No matching hotels found</td></tr>";
    }

    $conn->close();
}
?>
