<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_PATH . '/contacts.php');
    exit;
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if(strlen($first_name) < 2) {
    echo "First name error";
    exit;
}
if(strlen($last_name) < 2) {
    echo "Last name error";
    exit;
}
if(strlen($email) < 2 || strpos($email, "@") === false) {
    echo "Email error";
    exit;
}
if(strlen($message) < 2) {
    echo "Message error";
    exit;
}

// VULNERABLE: Direct string concatenation - SQL INJECTION RISK!
try {
    $sql = "INSERT INTO contacts(first_name, last_name, email, message) VALUES('$first_name', '$last_name', '$email', '$message')";
    $pdo->query($sql);
    
    header('Location: ' . BASE_PATH . '/contacts.php?success=1');
    exit;
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}