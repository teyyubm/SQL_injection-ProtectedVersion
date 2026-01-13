<?php
require_once __DIR__ . '/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Destroy session data
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Also clear login cookie - use same path as when it was set
$cookie_path = (BASE_PATH === '' ? '/' : BASE_PATH);
setcookie("login", "", time() - 3600, $cookie_path);

header('Location: ' . buildUrl('/'));
exit;