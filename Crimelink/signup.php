<?php
session_start(); // Add at the very top

$signupMessage = "";
$generatedUserID = "";
$showPopup = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = mysqli_connect("localhost", "root", "", "crimelink");
    if (!$conn) die("Connection failed: " . mysqli_connect_error());

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $generatedUserID = "UID_" . substr(md5(uniqid()), 0, 8);

    $checkQuery = "SELECT * FROM user1 WHERE email='$email'";
    $result = mysqli_query($conn, $checkQuery);
    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['signupMessage'] = "Email already registered.";
    } else {
        $insertQuery = "INSERT INTO user1 (name, email, address, phone, password, role, userid) 
                        VALUES ('$name', '$email', '$address', '$phone', '$password', '$role', '$generatedUserID')";

        if (mysqli_query($conn, $insertQuery)) {
            $_SESSION['signupMessage'] = "Registration successful!";
            $_SESSION['showPopup'] = true;
            $_SESSION['generatedUserID'] = $generatedUserID;
            // Show popup before redirect
            echo "<script>
                alert('Your unique ID is: " . $generatedUserID . "');
                window.location.href = 'login.php';
            </script>";
            exit();
        } else {
            $_SESSION['signupMessage'] = "Error: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Get message from session and clear it
if (isset($_SESSION['signupMessage'])) {
    $signupMessage = $_SESSION['signupMessage'];
    unset($_SESSION['signupMessage']);
}

if (isset($_SESSION['showPopup'])) {
    $showPopup = $_SESSION['showPopup'];
    $generatedUserID = $_SESSION['generatedUserID'];
    unset($_SESSION['showPopup']);
    unset($_SESSION['generatedUserID']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
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

        h2 {
            color: white;
            margin-bottom: 30px;
            font-size: 32px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        form {
            background: rgba(255, 255, 255, 0.97);
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
        }

        input, textarea, select {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        input::placeholder, textarea::placeholder {
            color: #888;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #004488;
            box-shadow: 0 0 10px rgba(0, 68, 136, 0.2);
            transform: translateY(-2px);
        }

        select {
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
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

        p {
            text-align: center;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            width: 100%;
            max-width: 500px;
            background: #fff;
        }

        p:contains("successful") {
            background: #e6ffe6;
            color: #006600;
            border-left: 4px solid #006600;
        }

        p:contains("Error"), p:contains("already") {
            background: #ffe6e6;
            color: #cc0000;
            border-left: 4px solid #cc0000;
        }
    </style>
    <script>
        function showPopup(userID) {
            alert("Your unique ID is: " + userID);
        }
    </script>
</head>
<body>
    <div class="logo-container">
        <img src="logo.png" alt="CrimeLink Logo" class="logo">
    </div>
    <h2>Signup</h2>
    <?php if (!empty($signupMessage)) echo "<p>$signupMessage</p>"; ?>
    
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <textarea name="address" placeholder="Address" required></textarea><br>
        <input type="tel" name="phone" placeholder="Phone" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="police">Police</option>
            <option value="admin">Admin</option>
            <option value="citizen">Citizen</option>
        </select><br>
        <button type="submit">Register</button>
    </form>

    <?php if ($showPopup) { ?>
        <script>
            showPopup("<?php echo $generatedUserID; ?>");
        </script>
    <?php } ?>
</body>
</html>
