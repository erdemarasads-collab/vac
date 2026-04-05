<?php
// HTTPS kontrolü - Railway proxy arkasında çalışır
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');

ini_set('session.cookie_secure', $isHttps ? '1' : '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_lifetime', '86400');
ini_set('session.gc_maxlifetime', '86400');

session_start();

$admin_password = 'emir1q2q';

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Hatalı şifre!';
        }
    }
    include 'login.php';
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard', 'users', 'online', 'guests', 'settings'];
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

include 'layout.php';
?>
