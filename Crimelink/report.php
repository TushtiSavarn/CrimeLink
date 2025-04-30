<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crimelink";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reportID = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $userid = $_POST['userid'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $crime_type = $_POST['crime_type'];
    $generatedreportID = "Report_" . substr(md5(uniqid()), 0, 8);
    $reportID = $generatedreportID; // Store the report ID for JavaScript

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO report (userid, date, time, description, location, crimetype, reportid) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $userid, $date, $time, $description, $location, $crime_type, $generatedreportID);

    // Execute and check success
    if ($stmt->execute()) {
        // Insert into status table
        $initialStatus = "Case Reported";
        $statusStmt = $conn->prepare("INSERT INTO status (reportid, status) VALUES (?, ?)");
        $statusStmt->bind_param("ss", $generatedreportID, $initialStatus);
        $statusStmt->execute();
        $statusStmt->close();

        // Store report ID in session to show the popup
        session_start();
        $_SESSION['reportID'] = $reportID;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Report Form</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #002244, #004488);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.form-container {
    background: rgba(255, 255, 255, 0.97);
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 500px;
    backdrop-filter: blur(10px);
}

.form-container h2 {
    color: #002244;
    margin-bottom: 30px;
    font-size: 32px;
    text-align: center;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

input[type="text"], 
input[type="date"], 
input[type="time"], 
textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
}

input:focus, 
textarea:focus {
    outline: none;
    border-color: #004488;
    box-shadow: 0 0 10px rgba(0, 68, 136, 0.2);
    transform: translateY(-2px);
}

label {
    display: block;
    margin-bottom: 8px;
    color: #002244;
    font-weight: 600;
    font-size: 16px;
}

.radio-group {
    background: rgba(255, 255, 255, 0.9);
    padding: 15px;
    border-radius: 10px;
    border: 2px solid #e0e0e0;
    margin-bottom: 20px;
}

.radio-group label {
    display: flex;
    align-items: center;
    padding: 10px;
    margin: 5px 0;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
}

.radio-group label:hover {
    background: rgba(0, 68, 136, 0.1);
}

.radio-group input[type="radio"] {
    margin-right: 10px;
}

button {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #002244, #004488);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 20px rgba(0, 34, 68, 0.4);
    background: linear-gradient(135deg, #002c5a, #0055aa);
}

button:active {
    transform: translateY(-1px);
}

textarea {
    min-height: 120px;
    resize: vertical;
}
</style>
</head>
<body>
    <div class="form-container">
        <h2>Crime Report Form</h2>
        <form action="" method="post">
            <label for="userid">User ID:</label>
            <input type="text" id="userid" name="userid" required>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label>Crime Type:</label>
            <div class="radio-group">
                <label><input type="radio" id="women_children" name="crime_type" value="Women & Children Crime"> Women & Children Crime</label>
                <label><input type="radio" id="financial_fraud" name="crime_type" value="Financial Fraud"> Financial Fraud</label>
                <label><input type="radio" id="cyber_crime" name="crime_type" value="Cyber Crime"> Cyber Crime</label>
            </div>

            <button type="submit">Submit Report</button>
        </form>
    </div>

    <script>
        function showPopup(reportID) {
            alert("Your report ID is: " + reportID);
            window.location.href = 'homee.html'; // Add redirect after showing popup
        }

        // Show popup if report ID is set in PHP session
        <?php
        session_start();
        if (isset($_SESSION['reportID'])) {
            echo "showPopup('" . $_SESSION['reportID'] . "');";
            unset($_SESSION['reportID']); // Remove the session variable after showing the popup
        }
        ?>
    </script>
</body>
</html>
