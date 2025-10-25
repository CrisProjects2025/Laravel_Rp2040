<?php
header("Content-Type: application/json");
date_default_timezone_set('America/La_Paz');

$command_file = "rp2040_command.json";
$valid_commands = ["Activate", "Deactivate"];

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['command'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing command"]);
    exit;
}

$command_value = ucfirst(strtolower($input['command']));
if (!in_array($command_value, $valid_commands)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid command"]);
    exit;
}

$command = [
    "timestamp" => date("Y-m-d H:i:s"),
    "command" => $command_value
];

file_put_contents($command_file, json_encode($command, JSON_PRETTY_PRINT));

echo json_encode(["status" => "ok", "command" => $command_value]);
?>
