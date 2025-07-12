<?php
session_start();
require_once 'includes/Auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (try_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Utilizator sau parolă invalidă.';
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8" />
<title>Login Minecraft Staff Panel</title>
<link rel="stylesheet" href="assets/style.css" />
</head>
<body>
<div class="container">
    <h1>Login Staff</h1>
    <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Utilizator" required />
        <input type="password" name="password" placeholder="Parolă" required />
        <button type="submit">Autentificare</button>
    </form>
</div>
</body>
</html>
