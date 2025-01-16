<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teacher_system"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the teacher ID (assuming the teacher is logged in)
    session_start();
    $teacher_id = $_SESSION['teacher_id']; // Or retrieve from database if needed

    // Get the selected slots
    $selected_slots = isset($_POST['slot']) ? implode(", ", $_POST['slot']) : '';
    $one_credit_subjects = isset($_POST['oneCreditSubject']) ? implode(", ", $_POST['oneCreditSubject']) : '';

    // Insert the data into the database
    $sql = "INSERT INTO allocations (teacher_id, slots, one_credit_subjects) 
            VALUES ('$teacher_id', '$selected_slots', '$one_credit_subjects')";

    if ($conn->query($sql) === TRUE) {
        echo "Allocation submitted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
