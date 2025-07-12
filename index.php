<?php
session_start();

require_once 'includes/Auth.php';
require_once 'includes/Rcon.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8" />
<title>Consola Minecraft Staff Panel</title>
<link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<div class="container">
    <h1>Consola Minecraft Server</h1>
    <a href="logout.php" style="float:right;">Logout</a>
    <div id="console-output" class="console-output"></div>
    <form id="command-form">
        <input type="text" id="command-input" placeholder="Scrie o comandă..." autocomplete="off" required />
        <button type="submit">Trimite</button>
    </form>
</div>
<script src="assets/script.js"></script>
</body>
</html>
