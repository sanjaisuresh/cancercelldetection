<?php
// Establish a database connection
$conn = new mysqli("localhost", "root", "", "healthcare_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the GET request
$userEmail = $_GET['email'];

// Query the patient_details table for the user's details
$query = "SELECT * FROM patient_details WHERE email = '$userEmail'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    // Return the user's data in JSON format
    echo json_encode($userData);
} else {
    echo json_encode(['error' => 'User not found']);
}

$conn->close();
?>
