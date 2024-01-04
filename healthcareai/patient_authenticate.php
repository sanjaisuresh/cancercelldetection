<?php
session_start();
// Establish a database connection
$conn = new mysqli("localhost", "root", "", "healthcare_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle patient login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["patient_email"];
    $password = $_POST["patient_password"];

    $sql = "SELECT id FROM patients WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION["user_type"] = "patient";
        header("Location: patients_dashboard.html");
    } else {
        echo "Invalid patient credentials";
    }
}

$conn->close();
?>
