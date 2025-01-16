<?php
header('Content-Type: application/json');

// Configuration
$config = [
    'db' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'room_allocation_db'
    ]
];

// Response helper function
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Database connection
try {
    $conn = new mysqli(
        $config['db']['host'],
        $config['db']['username'],
        $config['db']['password'],
        $config['db']['database']
    );

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    sendResponse(false, "Database connection error: " . $e->getMessage());
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Decode JSON data from the form
        $roomDetails = json_decode($_POST['roomDetails'] ?? '', true);

        if (!is_array($roomDetails)) {
            throw new Exception("Invalid room details format");
        }

        // Begin transaction
        $conn->begin_transaction();

        // Prepare statement to insert or update room status
        $stmt = $conn->prepare("
            INSERT INTO room_allocation (
                room_number, exam_date, exam_day, start_time, 
                duration, end_time, block, floor, status
            ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = 'booked'
        ");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Insert each room
        foreach ($roomDetails as $room) {
            // Validate required fields
            $requiredFields = ['roomNumber', 'examDate', 'examDay', 'startTime', 
                             'examDuration', 'endTime', 'block', 'floor'];
            foreach ($requiredFields as $field) {
                if (empty($room[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Set room status to "booked"
            $status = 'booked';

            // Bind parameters and execute
            $stmt->bind_param("ssssdssss",
                $room['roomNumber'],
                $room['examDate'],
                $room['examDay'],
                $room['startTime'],
                $room['examDuration'],
                $room['endTime'],
                $room['block'],
                $room['floor'],
                $status
            );

            if (!$stmt->execute()) {
                throw new Exception("Error inserting or updating room: " . $stmt->error);
            }
        }

        // Commit transaction
        $conn->commit();

        // Get room status (booked/available)
        $roomStatus = getRoomStatus($conn);

        sendResponse(true, "Room allocations saved successfully", [
            'roomsInserted' => count($roomDetails),
            'status' => $roomStatus
        ]);

    } catch (Exception $e) {
        // Rollback on error
        if ($conn->connect_error === null) {
            $conn->rollback();
        }
        sendResponse(false, "Error: " . $e->getMessage());
    }
} else {
    sendResponse(false, "Invalid request method");
}

// Close resources
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>

<?php
// Function to get current status of room bookings
function getRoomStatus($conn) {
    $status = [
        'totalRooms' => 0,
        'roomsBooked' => 0,
        'roomsAvailable' => 0
    ];

    // Get total number of rooms in the system
    $result = $conn->query("SELECT COUNT(*) as totalRooms FROM room_allocation");
    if ($result) {
        $row = $result->fetch_assoc();
        $status['totalRooms'] = $row['totalRooms'];
    }

    // Get the number of rooms booked
    $result = $conn->query("SELECT COUNT(*) as roomsBooked FROM room_allocation WHERE status = 'booked'");
    if ($result) {
        $row = $result->fetch_assoc();
        $status['roomsBooked'] = $row['roomsBooked'];
    }

    // Calculate available rooms
    $status['roomsAvailable'] = $status['totalRooms'] - $status['roomsBooked'];

    return $status;
}
?>
