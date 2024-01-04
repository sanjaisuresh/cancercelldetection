<?php
// Establish a database connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the admin's email from the GET request
$adminEmail = $_GET['email'];

// Query the admin_details table for the admin's details by email
$query = "SELECT * FROM admin_details WHERE email = '$adminEmail'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $adminData = $result->fetch_assoc();
    // Return the admin's data in JSON format
    echo json_encode($adminData);
} else {
    echo json_encode(['error' => 'Admin not found']);
}

$conn->close();
?>
