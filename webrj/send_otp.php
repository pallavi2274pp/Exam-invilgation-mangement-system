<?php
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "admin_system";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Generate OTP
    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Insert OTP into database
    $sql = "INSERT INTO otp_requests (email, otp_code, expires_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $otp, $expires_at);

    if ($stmt->execute()) {
        // Send OTP to email (basic example)
        mail($email, "Your OTP Code", "Your OTP is: $otp");

        echo "OTP sent to $email!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
