<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . buildUrl('/contacts.php'));
    exit;
}

// Sanitize and validate input
$first_name = trim(filter_var($_POST['first_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$last_name = trim(filter_var($_POST['last_name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$message = trim(filter_var($_POST['message'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));

// Validation
$errors = [];

if(strlen($first_name) < 2) {
    $errors[] = "First name must be at least 2 characters";
}

if(strlen($last_name) < 2) {
    $errors[] = "Last name must be at least 2 characters";
}

if(strlen($email) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address";
}

if(strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters";
}

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $error_msg = urlencode(implode(', ', $errors));
    header('Location: ' . buildUrl('/contacts.php?error=' . $error_msg));
    exit;
}

// Insert into database using prepared statements (secure)
try {
    // Check if contacts table exists, if not create it
    $check_table = $pdo->query("SHOW TABLES LIKE 'contacts'");
    if ($check_table->rowCount() == 0) {
        // Create contacts table
        $create_table = "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($create_table);
    }
    
    // Insert contact using prepared statement
    $sql = "INSERT INTO contacts(first_name, last_name, email, message) VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->execute([$first_name, $last_name, $email, $message]);
    
    // Redirect with success message
    header('Location: ' . buildUrl('/contacts.php?success=1'));
    exit;
    
} catch(PDOException $e) {
    // Log error (in production, log to file instead of showing)
    error_log("Contact form error: " . $e->getMessage());
    header('Location: ' . buildUrl('/contacts.php?error=' . urlencode('Database error. Please try again later.')));
    exit;
}
?>
