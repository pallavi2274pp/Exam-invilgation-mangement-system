<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "room_allocation_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch booked rooms
$sql = "SELECT block, room_number FROM room_allocation WHERE status = 'booked'";
$result = $conn->query($sql);

// Prepare data for frontend
$bookedRooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookedRooms[$row['block']][] = $row['room_number'];
    }
} else {
    echo "No booked rooms found.";
}

// Return the booked rooms as JSON
header('Content-Type: application/json');
echo json_encode($bookedRooms);

// Close the connection
$conn->close();
?>
