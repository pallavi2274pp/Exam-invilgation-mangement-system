<?php
// Start session
session_start();

// Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Send OTP to email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Replace with your email
        $mail->Password = 'your-password';       // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your-email@gmail.com', 'Exam Management');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Admin Login';
        $mail->Body = "<p>Your OTP is <strong>$otp</strong>. Use this to log in to your admin account.</p>";

        $mail->send();
        echo "<script>alert('OTP sent to your email');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Failed to send OTP: {$mail->ErrorInfo}');</script>";
    }
}
?>