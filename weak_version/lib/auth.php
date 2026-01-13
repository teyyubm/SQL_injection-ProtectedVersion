<?php 
require_once __DIR__ . '/config.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This page only accepts POST requests. Please use the login form.");
}

// VULNERABLE: No input sanitization
$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

if(strlen($login)< 2) {
    echo "Login error";
    exit;
}
if(strlen($password)< 2) {
    echo "Password error";
    exit;
}


// DB
require __DIR__ . "/db.php";

// VULNERABLE: Direct string concatenation - SQL INJECTION RISK!
$sql = "SELECT id FROM users WHERE login = '$login' AND password = '$password'";

// DEBUG: Show what we're trying
echo "<h3>Debug Information:</h3>";
echo "<p><strong>Login input:</strong> " . htmlspecialchars($_POST['login']) . "</p>";
echo "<p><strong>Password input:</strong> " . htmlspecialchars($_POST['password']) . "</p>";
echo "<p><strong>Password hash:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>SQL Query:</strong> <code>" . htmlspecialchars($sql) . "</code></p>";
echo "<hr>";

try {
    $query = $pdo->query($sql);
    
    echo "<p><strong>Query executed successfully!</strong></p>";
    echo "<p><strong>Rows found:</strong> " . $query->rowCount() . "</p>";
    
    if($query->rowCount() == 0) {
        echo '<p style="color: red;">NO user found</p>';
    } else {
        setcookie('login', $login, time() + 3600 * 24 * 30, '/');
        echo '<p style="color: green;">Login successful! Redirecting...</p>';
        header('Location: ' . BASE_PATH . '/user.php');
    }
} catch(PDOException $e) {
    echo "<p style='color: red;'><strong>Database error:</strong> " . $e->getMessage() . "</p>";
}