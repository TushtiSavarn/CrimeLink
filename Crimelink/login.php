<?php
session_start();

// Add this at the beginning after session_start()
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

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
    $name=isset($_POST['uname']) ? $conn->real_escape_string($_POST['uname']) : '';
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Prevent SQL Injection
    $name = $conn->real_escape_string($name);
    $password = $conn->real_escape_string($password);
    $role = $conn->real_escape_string($role);

    // Query without password hashing
    $sql = "SELECT * FROM user1 WHERE userid = '$name' AND password = '$password' AND role = '$role'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['uname'] = $user['uname'];
        $_SESSION['password'] = $user['password'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($role === 'police') {
            header("Location: policedashboard.html");
        } else if($role==='citizen'){
            header("Location: homee.html");
        }
        else{
            header("Location:admin.html");
        }
        exit();
    } else {
        $_SESSION['error_message'] = "Invalid userID or password";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

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
            padding: 20px 0;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px 0;
        }
        .logo {
            height: 120px;
            width: auto;
            max-width: 200px;
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="logo.png" alt="CrimeLink Logo" class="logo">
        </div>
        <h2>Login</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="uname">User ID</label>
            <input type="text" name="uname" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="role">Select Role</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="police">Police</option>
                <option value="citizen">Citizen</option>
            </select>

            <button type="submit">Login</button>
            <a href="forgotpassword.php">Forgot Password</a>
        </form>
    </div>
</body>
</html>
