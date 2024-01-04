<!DOCTYPE html>
<html>
<head>
    <title>Check Disease</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            background-color: black;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space between;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
        }

        .nav-button {
            margin: 0 10px;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

        .container {
            width: 80%;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            margin-top: 50px;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #000;
            color: white;
        }

        .choose-image {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-buttons">
            <div class="nav-button" id="home-button" oncli>Home</div>
            <div class="nav-button" id="about-button">About</div>
            <div class="nav-button" id="contact-button">Contact Us</div>
        </div>
    </header>
    <div class="container">
        <h1>List of Patients</h1>

        <table>
            <tr>
                <th>First Name</th>
                <th>Email</th> <!-- Change this header to "Email" -->
                <th>Image Upload</th>
                <th>Result</th>
                <th>Detect</th>
                <th>Update</th> <!-- New "Update" column -->
            </tr>

            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "healthcare_db";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_result"])) {
                // Get the patient's email from the form
                $email = $_POST["email"];

                // Get the updated result from the form
                $updatedResult = $_POST["updated_result"];

                // Update the result in the database
                $updateSql = "UPDATE patient_details SET result = '$updatedResult' WHERE email = '$email'";

                if ($conn->query($updateSql) === TRUE) {
                    echo "Result updated successfully.";
                } else {
                    echo "Error updating result: " . $conn->error;
                }
            }

            $sql = "SELECT first_name, email, result FROM patient_details"; // Select "email" instead of "last_name"
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row["first_name"] . '</td>';
                    echo '<td>' . $row["email"] . '</td>';
                    echo '<td><input type="file" class="choose-image" id="file_' . $row["first_name"] . '"></td>';
                    echo '<td id="result_' . $row["first_name"] . '">' . $row["result"] . '</td>';
                    echo '<td><button class="detect-button" onclick="detectDisease(\'' . $row["first_name"] . '\')">Detect</button></td>';
                    echo '<td>';
                    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
                    echo '<input type="hidden" name="email" value="' . $row["email"] . '">';
                    echo '<input type="text" name="updated_result" placeholder="Updated Result">';
                    echo '<input type="submit" name="update_result" value="Update">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo "No patients found.";
            }

            $conn->close();
            ?>
        </table>
    </div>

    <script>
        function detectDisease(firstName) {
            var fileInput = document.getElementById('file_' + firstName);
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append("file", file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'http://127.0.0.1:5000/classify', true); // Replace with your Flask server URL
            xhr.send(formData);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('result_' + firstName).textContent = response.result;
                } else {
                    alert('Failed to execute the classification script.');
                }
            };
        }
        const homeButton = document.getElementById("home-button");

        homeButton.addEventListener("click", function () {
            window.location.href = "admin_dashboard.html";
        });
        const aboutButton = document.getElementById("about-button");
        const contactButton = document.getElementById("contact-button");
        const guidelinesButton = document.getElementById("guidelines-button");

        aboutButton.addEventListener("click", function () {
            window.location.href = "about_patients.html";
        });

        contactButton.addEventListener("click", function () {
            window.location.href = "contactus.html";
        });


    </script>
</body>
</html>
