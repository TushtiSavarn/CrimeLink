<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "crimelink");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update status if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = $_POST['reportid'];
    $newStatus = $_POST['status'];
    
    $updateSql = "UPDATE status SET status = ? WHERE reportid = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ss", $newStatus, $reportId);
    
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch status data
$sql = "SELECT reportid, status FROM status";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Complaint Status</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #002244, #004488);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        h2 {
            color: white;
            text-align: center;
            margin: 30px 0;
            font-size: 32px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            margin: 20px auto;
            max-width: 1200px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            background: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background: linear-gradient(135deg, #002244, #004488);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #f0f4f8;
            transition: background-color 0.3s ease;
        }

        td {
            font-size: 14px;
            color: #333;
        }

        /* Status-specific styling */
        td:nth-child(4) {
            font-weight: 600;
        }

        td:nth-child(4):contains('Pending') {
            color: #ffa500;
        }

        td:nth-child(4):contains('Resolved') {
            color: #008000;
        }

        td:nth-child(4):contains('Delegated') {
            color: #0056b3;
        }

        @media screen and (max-width: 768px) {
            .table-container {
                padding: 15px;
                margin: 10px;
            }

            th, td {
                padding: 10px;
                font-size: 12px;
            }
        }
        select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        button {
            background: linear-gradient(135deg, #002244, #004488);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        button:hover {
            background: linear-gradient(135deg, #002c5a, #0055aa);
        }
    </style>
</head>
<body>
    <h2>Complaint Status</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Report ID</th>
                <th>Current Status</th>
                <th>Update Status</th>
                
            </tr>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['reportid']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="reportid" value="<?php echo htmlspecialchars($row['reportid']); ?>">
                            <select name="status" required>
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Case Reported">Case Reported</option>
                                <option value="Complaint Verified">Complaint Verified</option>
                                <option value="Evidence Collected">Evidence Collected</option>
                                <option value="Delegated to Further Authority">Delegated to Further Authority</option>
                            </select>
                            <button type="submit" style="padding: 5px 10px; margin-left: 10px;">Update</button>
                        </form>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='3' style='text-align: center;'>No status records found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
