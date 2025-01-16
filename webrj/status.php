<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "room_allocation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booked rooms
$sql = "SELECT block, room_number, exam_date, exam_day FROM room_allocation WHERE status = 'booked'";
$result = $conn->query($sql);

$bookedRooms = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookedRooms[] = $row;
    }
}

// Return data as JSON
echo json_encode($bookedRooms);

$conn->close();
?>
