<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function try_login($username, $password) {
    $valid_username = 'admin';
    $valid_password = 'parola123';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['logged_in'] = true;
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
}
