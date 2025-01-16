<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teacher_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch allocated slots along with teacher and additional details
$sql = "SELECT allocation_id, teacher_id, slots, one_credit_subjects, allocation_date FROM allocations"; 

$result = $conn->query($sql);

// Prepare an array to store the allocated slots
$allocated_data = [];

if ($result->num_rows > 0) {
    // Fetch the results and store them in the $allocated_data array
    while ($row = $result->fetch_assoc()) {
        $allocated_data[] = [
            'allocation_id' => $row['allocation_id'],
            'teacher_id' => $row['teacher_id'],
            'slots' => $row['slots'],
            'one_credit_subjects' => $row['one_credit_subjects'],
            'allocation_date' => $row['allocation_date']
        ];
    }
} else {
    echo "No allocations found.";
}

$conn->close();

// Convert the allocated data into JSON format to be passed to JavaScript
$allocated_json = json_encode($allocated_data);

// Debug: Print the output to see if data is correctly passed to the frontend
echo "<pre>";
print_r($allocated_data);  // See if the data is fetched properly
echo "</pre>";

?>
