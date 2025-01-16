<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . $_SESSION['admin_username'] . "! You are logged in.";
?>
