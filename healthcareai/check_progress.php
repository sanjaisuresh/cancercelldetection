<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcare_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$notDonePatients = [];
$donePatients = [];

// Get patients who have completed their checklist on the current day
$currentDate = date('Y-m-d'); // Get the current date

$sql = "SELECT * FROM patient_details WHERE checklist IS NOT NULL AND checklist <> '' AND DATE(updated_at) = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donePatients[] = $row;
        }
    }

    $stmt->close();
}

// Get patients who have not completed their checklist on the current day
$sql = "SELECT * FROM patient_details WHERE (checklist IS NULL OR checklist = '') AND DATE(updated_at) = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $currentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $notDonePatients[] = $row;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checklist Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: black;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checklist Status</h1>

        <h2>Done on the Current Day</h2>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
            <?php
            foreach ($donePatients as $patient) {
                echo "<tr>";
                echo "<td>" . $patient["first_name"] . "</td>";
                echo "<td>" . $patient["last_name"] . "</td>";
                echo "<td>" . $patient["email"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <h2>Not Done on the Current Day</h2>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
            <?php
            foreach ($notDonePatients as $patient) {
                echo "<tr>";
                echo "<td>" . $patient["first_name"] . "</td>";
                echo "<td>" . $patient["last_name"] . "</td>";
                echo "<td>" . $patient["email"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
