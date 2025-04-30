<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root"; // Change if needed
    $password = "";
    $dbname = "crimelink";

    // Connect to MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form input
    $userid = isset($_POST['userid']) ? $conn->real_escape_string($_POST['userid']) : '';
    $new_password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

    // Update password in the database as plain text (NOT RECOMMENDED FOR SECURITY)
    $sql = "UPDATE user1 SET password = '$new_password' WHERE userid='$userid'";

    if ($conn->query($sql) === TRUE) {
        // Password updated successfully, show pop-up and redirect
        echo "<script>
                alert('Password updated successfully!');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Error updating password: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            color: #1e3c72;
            text-align: center;
            margin-bottom: 30px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .login-form label {
            color: #333;
            font-weight: bold;
        }
        .login-form input, .login-form select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .login-form input:focus, .login-form select:focus {
            border-color: #1e3c72;
            outline: none;
        }
        .login-form button {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .login-form button:hover {
            transform: translateY(-2px);
        }
        .error-message {
            background-color: #ffe6e6;
            color: #d63031;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
    <label for="userid">UserID</label>
    <input type="text" name="userid" required>

    <label for="password">New Password</label>
    <input type="password" name="password" required>

    <label for="conpassword">Confirm Password</label>
    <input type="password" name="conpassword" required>

    <button type="submit">Reset Password</button>
</form>
    </div>
</body>
</html>
