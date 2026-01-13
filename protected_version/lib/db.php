<?php 
/**
 * Database Connection using Environment Variables
 * 
 * Get database credentials from environment variables with fallback defaults
 * Wasmer.app database credentials (update if environment variables are set)
 */

// Get database credentials from environment variables with fallback defaults
// Wasmer.app database credentials (update if environment variables are set)
$db_host = getenv('DB_HOST') ?: 'db.fr-pari1.bengt.wasmernet.com';
$db_name = getenv('DB_NAME') ?: 'gamedev_php_protected';
$db_port = getenv('DB_PORT') ?: '10272';
$db_user = getenv('DB_USER') ?: '5961b84b701e80008d193d96140f';
$db_password = getenv('DB_PASSWORD') ?: '06965961-b84b-715a-8000-e45c9fa727b7';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;port=$db_port",
        $db_user,
        $db_password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log error instead of exposing details
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
