<?php
// Database connection settings
$servername = "localhost"; // Your database host
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "room_allocation_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch room allocation details
$query = "SELECT room_number, exam_date, start_time, exam_day, duration, end_time, block, floor FROM room_allocation";

// Execute the query and check if it's successful
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Check if any rows are returned
if ($result->num_rows > 0) {
    // Fetch the data as an associative array
    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
} else {
    // If no data is found
    $rooms = [];
}
?>

<?php if (count($rooms) > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Exam Date</th>
                <th>Start Time</th>
                <th>Exam Day</th>
                <th> Duration</th>
                <th>End Time</th>
                <th>Block</th>
                <th>Floor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?= htmlspecialchars($room['room_number']) ?></td>
                    <td><?= htmlspecialchars($room['exam_date']) ?></td>
                    <td><?= htmlspecialchars($room['start_time']) ?></td>
                    <td><?= htmlspecialchars($room['exam_day']) ?></td>
                    <td><?= htmlspecialchars($room['duration']) ?> minutes</td>
                    <td><?= htmlspecialchars($room['end_time']) ?></td>
                    <td><?= htmlspecialchars($room['block']) ?></td>
                    <td><?= htmlspecialchars($room['floor']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No timetable data available.</p>
<?php endif; ?>

<?php
// Close the database connection
$conn->close();
?>
