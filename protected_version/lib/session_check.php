<?php
/**
 * Session Check Helper
 * Validates user session and redirects to login if not authenticated
 * Includes session timeout functionality
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout: 30 minutes (1800 seconds) of inactivity
define('SESSION_TIMEOUT', 1800);

/**
 * Check if session has expired
 * @return bool True if expired, false otherwise
 */
function isSessionExpired() {
    if (!isset($_SESSION['last_activity'])) {
        return false; // New session, not expired
    }
    
    $timeSinceLastActivity = time() - $_SESSION['last_activity'];
    return $timeSinceLastActivity > SESSION_TIMEOUT;
}

/**
 * Update session activity timestamp
 */
function updateSessionActivity() {
    $_SESSION['last_activity'] = time();
}

/**
 * Check if user is logged in and session is valid
 * @return bool True if logged in and session valid, false otherwise
 */
function isLoggedIn() {
    // Check if session expired
    if (isSessionExpired()) {
        // Destroy expired session
        session_destroy();
        return false;
    }
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['login'])) {
        return false;
    }
    
    // Update activity timestamp on each check
    updateSessionActivity();
    
    return true;
}

/**
 * Require login - redirects to auth page if not logged in or session expired
 */
function requireLogin() {
    if (!isLoggedIn()) {
        // Clear any existing session data
        session_destroy();
        header('Location: ' . buildUrl('/auth.php?expired=1'));
        exit;
    }
}

/**
 * Get current user ID
 * @return int|null User ID or null if not logged in
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user login
 * @return string|null User login or null if not logged in
 */
function getUserLogin() {
    return $_SESSION['login'] ?? null;
}
