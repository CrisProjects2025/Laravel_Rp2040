<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
date_default_timezone_set('America/La_Paz');


$recipient_email = "robotica2020@mail.com"; // Change to your actual email
$sender_email = "cris.sa@baratronics.com";     // Optional: must be a valid domain email
$subject = "Solar System Mode Changed";


$log_file = "rp2040_env_data.json";
$command_file = "rp2040_command.json";

// MySQL credentials
$host = "localhost";
$user = "u373556621_Solar";
$pass = "B@ra2021";
$dbname = "u373556621_SolarControl";



// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}

// Handle incoming JSON POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $input = json_decode(file_get_contents("php://input"), true);

    // Sensor data from RP2040W
    if ($input && isset($input['Light'])) {
        $entry = [
            "timestamp" => date("Y-m-d H:i:s"),
            "Light" => intval($input['Light']),
            "Open or Close" => $input['Open or Close'] ?? "unknown",
            "Mode" => $input['Mode'] ?? "manual",
            "Temperature" => floatval($input['Temperature'] ?? 0),
            "Humidity" => floatval($input['Humidity'] ?? 0)
        ];

        // Save to JSON
        file_put_contents($log_file, json_encode($entry, JSON_PRETTY_PRINT));

        // Save to MySQL
        $stmt = $conn->prepare("INSERT INTO solar_data (timestamp, light, status, mode, temperature, humidity) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissdd", $entry['timestamp'], $entry['Light'], $entry['Open or Close'], $entry['Mode'], $entry['Temperature'], $entry['Humidity']);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "ok", "received" => $entry]);
        exit;
    }

    // Command from dashboard
    if ($input && isset($input['command'])) {
        $command = [
            "timestamp" => date("Y-m-d H:i:s"),
            "command" => $input['command']
        ];

        // Save to JSON
        file_put_contents($command_file, json_encode($command, JSON_PRETTY_PRINT));

        // Save to MySQL
        $stmt = $conn->prepare("INSERT INTO command_log (timestamp, command) VALUES (?, ?)");
        $stmt->bind_param("ss", $command['timestamp'], $command['command']);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "ok", "command" => $input['command']]);
        exit;
     if ($entry['Mode'] === "System is in Automatic Mode") {
    $message = "Alert: The solar system switched to Automatic Mode at " . $entry['timestamp'] . ".\n\n" .
               "Light: " . $entry['Light'] . "%\n" .
               "Temperature: " . $entry['Temperature'] . " °C\n" .
               "Humidity: " . $entry['Humidity'] . " %\n" .
               "Status: " . $entry['Open or Close'];

    $headers = "From: " . $sender_email . "\r\n" .
               "Reply-To: " . $sender_email . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    mail($recipient_email, $subject, $message, $headers);
    
}
   
        
        
        
    }

    // Invalid structure
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON structure"]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Baratronics Solar Control</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            text-align: center;
            padding: 40px;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: inline-block;
        }
        h1 {
            color: #0078D7;
        }
        .data {
            font-size: 1.2em;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 1em;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .activate { background-color: #28a745; color: white; }
        .deactivate { background-color: #dc3545; color: white; }
    </style>
    <script>
        function updateData() {
            fetch('rp2040_env_data.json')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('light').innerText = data.Light + " %";
                    document.getElementById('status').innerText = data["Open or Close"];
                    document.getElementById('mode').innerText = data.Mode;
                    document.getElementById('temp').innerText = data.Temperature + " °C";
                    document.getElementById('hum').innerText = data.Humidity + " %";
                    document.getElementById('time').innerText = data.timestamp;

                    const isManual = data.Mode.toLowerCase().includes("manual");
                    document.getElementById('activateBtn').disabled = !isManual;
                    document.getElementById('deactivateBtn').disabled = !isManual;
                });
        }

        function sendCommand(command) {
            fetch('data_receiver.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ command: command })
            })
            .then(response => response.json())
            .then(data => {
                alert("Command sent: " + data.command);
            })
            .catch(error => {
                alert("Error sending command");
            });
        }

        setInterval(updateData, 5000);
        window.onload = updateData;
    </script>
</head>
<body>
    <div class="container">
        <img src="bara_logo2.png" alt="Logo BARATRONICS">
        <h1>Baratronics Solar Control</h1>
        <div class="data"><strong>Light:</strong> <span id="light">--</span></div>
        <div class="data"><strong>Status:</strong> <span id="status">--</span></div>
        <div class="data"><strong>Mode:</strong> <span id="mode">--</span></div>
        <div class="data"><strong>Temperature:</strong> <span id="temp">--</span></div>
        <div class="data"><strong>Humidity:</strong> <span id="hum">--</span></div>
        <div class="data"><strong>Last Update:</strong> <span id="time">--</span></div>
        <hr>
        <button id="activateBtn" class="activate" onclick="sendCommand('Activate')" disabled>⚡ Activate</button>
        <button id="deactivateBtn" class="deactivate" onclick="sendCommand('Deactivate')" disabled>🛑 Deactivate</button>
    </div>
</body>
</html>