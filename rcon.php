<?php
session_start();
require_once 'includes/Auth.php';
require_once 'includes/Rcon.php';
$config = require 'config.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$command = trim($data['command'] ?? '');

if ($command === '') {
    echo json_encode(['error' => 'Comandă goală']);
    exit;
}

use Thedudeguy\Rcon;

$rcon = new Rcon($config['rcon_host'], $config['rcon_port'], $config['rcon_password'], 3);

if (!$rcon->connect()) {
    echo json_encode(['error' => 'Conexiunea la RCON a eșuat']);
    exit;
}

$output = $rcon->sendCommand($command);

echo json_encode(['output' => $output]);
