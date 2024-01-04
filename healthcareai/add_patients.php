<?php
// Establish a database connection
$servername = "localhost";  // Replace with your database server hostname
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "healthcare_db";  // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["patient_name"];
    $last_name = $_POST["patient_last_name"];
    $phone_number = $_POST["patient_phone_number"];
    $email = $_POST["patient_email"];
    $address = $_POST["patient_address"];
    $blood_group = $_POST["patient_blood_group"];

    // Insert patient details into the patient_details table
    $sql = "INSERT INTO patient_details (first_name, last_name, phone_number, email, address, blood_group)
            VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$address', '$blood_group')";

    if ($conn->query($sql) === TRUE) {
        echo "Patient details inserted successfully!";
        
        // Redirect to admin_dashboard.html after a brief delay
        echo '<script>
            setTimeout(function() {
                window.location.href = "admin_dashboard.html"; // Replace with the correct URL
            }, 1000); // Redirect after 2 seconds (adjust as needed)
        </script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
