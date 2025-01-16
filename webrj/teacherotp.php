<?php
if (isset($_POST['send_otp'])) {
    $email = $_POST['otp_email'];

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);

    // Send OTP to the teacher's email (use PHP mail() function or PHPMailer)
    // Example using mail() function (make sure your server is configured for mail sending)
    $subject = "Your OTP Code";
    $message = "Your OTP code is: $otp";
    $headers = "From: no-reply@yourdomain.com";

    // Use PHP mail function to send the OTP (this requires a valid mail setup on the server)
    if (mail($email, $subject, $message, $headers)) {
        echo "OTP has been sent to your email.";
        // You can store the OTP in session or database for later verification
    } else {
        echo "Failed to send OTP. Please try again.";
    }
}
?>
