<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs</title>
    <link rel="icon" type="image/x-icon" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
        }
        pre {
            background: #222;
            color: #0f0;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📜 System Logs</h2>
        <pre>
            <?php
            $logFile = "logs.txt";
            if (file_exists($logFile) && filesize($logFile) > 0) {
                echo  htmlspecialchars(file_get_contents($logFile)) ;
            } else {
                echo "<p>No logs available. Make sure logs.txt is being updated.</p>";
            }
            ?>
        </pre>
    </div>
</body>
</html>
