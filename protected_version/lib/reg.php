<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session_check.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Return 405 Method Not Allowed instead of redirecting (prevents redirect loops)
    http_response_code(405);
    die('Method Not Allowed. This page only accepts POST requests.');
}

// Sanitize input
$login = trim(filter_var($_POST['login'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$username = trim(filter_var($_POST['username'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

// Validation
$errors = [];
    
if(strlen($login) < 2) {
    $errors[] = "Login must be at least 2 characters";
}
if(strlen($username) < 2) {
    $errors[] = "Name must be at least 2 characters";
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address";
}
if(strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

// If there are errors, redirect back
if (!empty($errors)) {
    $error_msg = urlencode(implode(', ', $errors));
    $redirect_url = buildUrl('/reg.php?error=' . $error_msg);
    header('Location: ' . $redirect_url);
    exit;
}

// Hash password securely
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
try {
    $sql = 'INSERT INTO users(login, username, email, password) VALUES(?, ?, ?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([$login, $username, $email, $password_hash]);
    
    // Redirect back to registration page with success message
    $redirect_url = buildUrl('/reg.php?success=1');
    header('Location: ' . $redirect_url);
    exit;
} catch(PDOException $e) {
    // Log error instead of displaying
    error_log("Registration error: " . $e->getMessage());
    
    // Handle duplicate login or other database errors
    if($e->getCode() == 23000) {
        $redirect_url = buildUrl('/reg.php?error=' . urlencode('This login already exists. Please choose a different login.'));
    } else {
        $redirect_url = buildUrl('/reg.php?error=' . urlencode('Registration failed. Please try again.'));
    }
    header('Location: ' . $redirect_url);
    exit;
}


