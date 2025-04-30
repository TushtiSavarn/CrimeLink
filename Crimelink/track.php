<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "crimelink";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$timelineHTML = "";  // Variable to store timeline HTML

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reportid = $_POST["reportid"];

    // Fetch report details
    $sql = "SELECT r.reportid, r.date, r.time, r.description, r.location, r.crimetype, s.status 
            FROM report r 
            LEFT JOIN status s ON r.reportid = s.reportid 
            WHERE r.reportid = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reportid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $currentStatus = $row["status"];
            
            // Status sequence
            $statusSequence = array(
                'Case Reported',
                'Pending',
                'Complaint Verified',
                'Evidence Collected',
                'Delegated to Further Authority',
                'In Progress',
                'Resolved'
            );

            $timelineHTML .= "<div class='timeline'>";
            foreach ($statusSequence as $status) {
                $stepClass = '';
                if ($status == $currentStatus) {
                    $stepClass = 'active';
                } elseif (array_search($status, $statusSequence) < array_search($currentStatus, $statusSequence)) {
                    $stepClass = 'completed';
                }
                
                // Timeline structure
                $timelineHTML .= "<div class='track-step $stepClass'>";
                $timelineHTML .= "<div class='step-icon'>";
                switch ($status) {
                    case 'Case Reported': $timelineHTML .= "🗒"; break;
                    case 'Pending': $timelineHTML .= "⌛"; break;
                    case 'Complaint Verified': $timelineHTML .= "✓"; break;
                    case 'Evidence Collected': $timelineHTML .= "🔎"; break;
                    case 'Delegated to Further Authority': $timelineHTML .= "📋"; break;
                    case 'In Progress': $timelineHTML .= "⚖"; break;
                    case 'Resolved': $timelineHTML .= "✅"; break;
                }
                $timelineHTML .= "</div>";
                $timelineHTML .= "<div class='step-content'><h4>$status</h4>";
                if ($status == $currentStatus) {
                    $timelineHTML .= "<p><strong>Report ID:</strong> " . $row["reportid"] . "</p>";
                    $timelineHTML .= "<p><strong>Date:</strong> " . $row["date"] . " <strong>Time:</strong> " . $row["time"] . "</p>";
                    $timelineHTML .= "<p><strong>Crime Type:</strong> " . $row["crimetype"] . "</p>";
                    $timelineHTML .= "<p><strong>Location:</strong> " . $row["location"] . "</p>";
                }
                $timelineHTML .= "</div></div>";
            }
            $timelineHTML .= "</div>";
        }
    } else {
        $timelineHTML = "<p class='error-message'>No complaints found for this Report ID.</p>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Complaint</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #002244, #1E3A8A);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        h2 { color: #002244; }
        input, button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            font-size: 16px;
        }
        button {
            background: #1E3A8A;
            color: white;
            cursor: pointer;
        }
        .timeline {
            margin-top: 20px;
            text-align: left;
        }
        .track-step {
            position: relative;
            padding: 15px;
            border-left: 4px solid #ccc;
            margin-bottom: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .track-step::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            width: 20px;
            height: 20px;
            background: #ccc;
            border-radius: 50%;
            transform: translateY(-50%);
        }
        .track-step.active::before, .track-step.completed::before {
            background: #1E3A8A;
        }
        .track-step.active { border-left-color: #1E3A8A; }
        .track-step.completed { border-left-color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Track Your Complaint 🔍</h2>
        <form method="POST" action="">
            <input type="text" name="reportid" placeholder="Enter your Report ID" required>
            <button type="submit">Track</button>
        </form>
        <div id="timeline"><?php echo $timelineHTML; ?></div>
    </div>
</body>
</html>
