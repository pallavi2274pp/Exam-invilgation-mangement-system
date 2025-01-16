<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Start of script.<br>";
session_start();
echo "Session started.<br>";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "teacher_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
echo "Database connection successful.<br>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    echo "Form submitted.<br>";

    $username = $_POST['username'];
    $password = $_POST['password'];

    echo "Received username: $username<br>";
    echo "Received password: $password<br>";

    $sql = "SELECT * FROM teachers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }
    echo "Statement prepared successfully.<br>";

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        echo "User found.<br>";
        $teacher = $result->fetch_assoc();

        if (password_verify($password, $teacher['password'])) {
            echo "Password verified.<br>";
            $_SESSION['teacher_id'] = $teacher['teacher_id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            echo "Redirecting to dashboard...<br>";
            // Redirect to dashboard
            header("Location: teacherdashboard.html"); // Enable redirection
            exit();
        } else {
            echo "Invalid password.<br>";
        }
    } else {
        echo "Invalid username.<br>";
    }
}

$conn->close();
echo "End of script.<br>";
?>
