<?php
$conn = new mysqli("localhost", "root", "", "crimelink");
$sql = "SELECT reportid, date, time, description, location, crimetype, userid FROM report";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>See Complaints</title>
    <meta charset="utf-8">
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

        /* Crime Type specific styling */
        td:nth-child(6) {
            font-weight: 600;
        }

        td:nth-child(6):contains('Women & Children Crime') {
            color: #ff4d4d;
        }

        td:nth-child(6):contains('Financial Fraud') {
            color: #ffa500;
        }

        td:nth-child(6):contains('Cyber Crime') {
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
    </style>
</head>
<body>
    <h2>All Complaints</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Report ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Description</th>
                <th>Location</th>
                <th>Crime Type</th>
                <th>User ID</th>
            </tr>
            <?php 
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { 
                    // Add htmlspecialchars to prevent XSS
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['reportid']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['crimetype']); ?></td>
                    <td><?php echo htmlspecialchars($row['userid']); ?></td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>No complaints found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>