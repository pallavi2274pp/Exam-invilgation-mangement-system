<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Script started.<br>";

session_start();
echo "Session started.<br>";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_system";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
echo "Database connection successful.<br>";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    echo "Form submitted.<br>";
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    echo "Username: $username<br>";
    echo "Password: $password<br>";

    // Fetch user
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    echo "Statement prepared.<br>";

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        echo "User found.<br>";
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password_hash'])) {
            echo "Password verified.<br>";
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            echo "Redirecting to dashboard.<br>";
            header("Location: admindashboard.html");
            exit();
        } else {
            echo "Invalid password.<br>";
        }
    } else {
        echo "Invalid username.<br>";
    }
}

$conn->close();
echo "Script ended.<br>";
?>
