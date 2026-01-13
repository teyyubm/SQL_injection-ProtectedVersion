<?php 
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
// Don't include session_check.php here - it's not needed for login processing

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Return 405 Method Not Allowed instead of redirecting (prevents redirect loops)
    http_response_code(405);
    die('Method Not Allowed. This page only accepts POST requests.');
}

// Sanitize input
$login = trim(filter_var($_POST['login'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$password = $_POST['password'] ?? '';

// Validation
if(strlen($login) < 2) {
    header('Location: ' . buildUrl('/auth.php?error=' . urlencode('Login must be at least 2 characters')));
    exit;
}
if(strlen($password) < 2) {
    header('Location: ' . buildUrl('/auth.php?error=' . urlencode('Password must be at least 2 characters')));
    exit;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authenticate user - check password hash
$sql = 'SELECT id, login, password FROM users WHERE login = ?';
$query = $pdo->prepare($sql);
$query->execute([$login]);
$user = $query->fetch(PDO::FETCH_OBJ);

if($user && password_verify($password, $user->password)) {
    // Login successful - create session
    $_SESSION['user_id'] = $user->id;
    $_SESSION['login'] = $user->login;
    $_SESSION['last_activity'] = time(); // Set activity timestamp
    
    // Set cookie path to match BASE_PATH (or root if BASE_PATH is empty)
    $cookie_path = (BASE_PATH === '' ? '/' : BASE_PATH);
    setcookie('login', $user->login, time() + 3600 * 24 * 30, $cookie_path);
    
    header('Location: ' . buildUrl('/user.php'));
    exit;
} else {
    // Login failed - don't reveal if user exists
    header('Location: ' . buildUrl('/auth.php?error=' . urlencode('Invalid login or password')));
    exit;
}