<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teacher_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data from the 'allocations' table
$sql = "SELECT allocation_id, teacher_id, slots, one_credit_subjects, allocation_date FROM allocations";

// Execute the query and store the result
$result = $conn->query($sql);

// Initialize an array to store the results
$allocated_data = [];

if ($result->num_rows > 0) {
    // Loop through the result and store data in the $allocated_data array
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

// Output the data as JSON
echo json_encode($allocated_data);
?>
