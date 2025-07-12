<?php
session_start();
require_once 'includes/Auth.php';

logout();
header('Location: login.php');
exit;
