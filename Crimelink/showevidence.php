<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "crimelink";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from evidence table
$query = "SELECT * FROM evidence";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evidence Records</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="logo.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Evidence Records</h2>
    <table border="1">
        <tr>
            <th>Photo</th>
            <th>Document</th>
            <th>Report ID</th>
        </tr>
        
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            
            // Check for photo
            if (!empty($row['photo']) && file_exists('uploads/' . $row['photo'])) {
                echo "<td><img src='uploads/{$row['photo']}' width='100' height='100'></td>";
            } else {
                echo "<td>No photo</td>";
            }
            
            // Check for document
            if (!empty($row['document']) && file_exists('uploads/' . $row['document'])) {
                echo "<td><a href='uploads/{$row['document']}' target='_blank'>View Document</a></td>";
            } else {
                echo "<td>No document</td>";
            }

            echo "<td>{$row['reportid']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
mysqli_close($conn);
?>
