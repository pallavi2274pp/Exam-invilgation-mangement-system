<?php
// Start session and enable error reporting
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teacher_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href = 'signup_page.html';</script>";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO teachers (name, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $name, $username, $hashed_password);
        if ($stmt->execute()) {
            // Successful signup, redirect to login page
            echo "<script>alert('Your account has been created successfully! Please log in.'); window.location.href = 'teacher_login.php';</script>";
        } else {
            echo "<script>alert('Error during signup: " . $stmt->error . "'); window.location.href = 'signup_page.html';</script>";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare SQL statement: " . $conn->error;
    }
}

$conn->close();
?>
