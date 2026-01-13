<?php
/**
 * Configuration using Environment Variables
 * 
 * Auto-detects BASE_PATH from script location, or uses environment variable
 */

// Auto-detect BASE_PATH - always use application root, not current script location
// This ensures BASE_PATH is consistent regardless of which script is running
if (!defined('BASE_PATH')) {
    // Check environment variable first
    $env_path = getenv('BASE_PATH');
    if ($env_path !== false) {
        $base_path = rtrim($env_path, '/');
    } else {
        // Calculate from the config file's location
        // Config is always in: protected_version/lib/config.php
        // So we go up one level to get protected_version, then calculate relative to doc root
        $config_dir = __DIR__; // This is protected_version/lib
        $app_root = dirname($config_dir); // This is protected_version
        
        // Get document root
        $doc_root = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        $app_root_normalized = rtrim(str_replace('\\', '/', $app_root), '/');
        
        // Calculate relative path from document root
        if (strpos($app_root_normalized, $doc_root) === 0) {
            // App root is inside document root
            $base_path = substr($app_root_normalized, strlen($doc_root));
            // If it's empty or just /, make it empty string
            if ($base_path === '' || $base_path === '/') {
                $base_path = '';
            }
        } else {
            // Fallback: assume root deployment
            $base_path = '';
        }
        
        // Remove trailing slash
        $base_path = rtrim($base_path, '/');
    }
    
    define('BASE_PATH', $base_path);
}

/**
 * Helper function to build absolute URLs
 * @param string $path The path to append (should start with /)
 * @return string The complete URL path
 */
function buildUrl($path) {
    // Ensure path starts with /
    $path = '/' . ltrim($path, '/');
    
    // If BASE_PATH is empty, just return the path
    if (BASE_PATH === '') {
        return $path;
    }
    
    // Otherwise combine BASE_PATH and path
    return BASE_PATH . $path;
}
?>
