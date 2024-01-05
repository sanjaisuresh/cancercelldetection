<?php
session_start();
// Establish a database connection
$conn = new mysqli("localhost", "root", "", "healthcare_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle admin login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["admin_email"];
    $password = $_POST["admin_password"];

    $sql = "SELECT id FROM admin WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION["user_type"] = "admin";
        header("Location: admin_dashboard.html");
    } else {
        echo "Invalid admin credentials";
    }
}

$conn->close();
?>
