<?php
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Admin kontrolü
$admin_password = 'emir1q2q';

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = 'Hatalı şifre!';
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        include 'login.php';
        exit;
    }
}

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: index.php');
    exit;
}

// Sayfa yönlendirme
$page = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard', 'users', 'online', 'guests', 'settings'];

if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

include 'layout.php';
?>
