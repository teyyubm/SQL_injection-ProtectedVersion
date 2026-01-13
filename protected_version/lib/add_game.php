<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session_check.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . buildUrl('/user.php'));
    exit;
}

// Check if user is logged in (session-based)
requireLogin();

// Handle file upload
$image = '';
if(isset($_FILES['image'])) {
    // Check for upload errors
    if($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        $error_code = $_FILES['image']['error'];
        $error_msg = isset($error_messages[$error_code]) ? $error_messages[$error_code] : "Unknown error ($error_code)";
        error_log("File upload error: " . $error_msg);
        header('Location: ' . buildUrl('/user.php?error=' . urlencode($error_msg)));
        exit;
    }
    
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $file_size = $_FILES['image']['size'];
    
    // File size limit: 5MB
    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($file_size > $max_size) {
        header('Location: ' . buildUrl('/user.php?error=' . urlencode('File size exceeds 5MB limit')));
        exit;
    }
    
    // Use absolute path for upload folder
    $upload_dir = __DIR__ . '/../imgs/blocks/';
    
    // Create directory if it doesn't exist
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Validate file type by extension
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if(!in_array($ext, $allowed_extensions)) {
        header('Location: ' . buildUrl('/user.php?error=' . urlencode('Invalid file type. Allowed: ' . implode(', ', $allowed_extensions))));
        exit;
    }
    
    // Validate MIME type (if fileinfo extension is available)
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $tempname);
        finfo_close($finfo);
        
        $allowed_mimes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/avif'
        ];
        
        if(!in_array($mime_type, $allowed_mimes)) {
            header('Location: ' . buildUrl('/user.php?error=' . urlencode('Invalid file type detected')));
            exit;
        }
    }
    
    // Generate unique filename to prevent overwrites and path traversal
    $unique_name = uniqid() . '_' . time() . '.' . $ext;
    
    // Sanitize filename (additional security)
    $real_upload_dir = realpath($upload_dir);
    if ($real_upload_dir === false) {
        // If realpath fails, use the original path but sanitize
        $real_upload_dir = $upload_dir;
    }
    $folder = $real_upload_dir . DIRECTORY_SEPARATOR . basename($unique_name);
    
    if(move_uploaded_file($tempname, $folder)) {
        $image = $unique_name; // Store unique filename in database
    } else {
        error_log("File upload error: Could not move uploaded file to " . $folder);
        header('Location: ' . buildUrl('/user.php?error=' . urlencode('File upload failed. Please try again.')));
        exit;
    }
} else {
    header('Location: ' . buildUrl('/user.php?error=' . urlencode('No image uploaded. Please select a file.')));
    exit;
}

$followers = isset($_POST['followers']) ? trim(filter_var($_POST['followers'], FILTER_SANITIZE_SPECIAL_CHARS)) : '';

if(strlen($followers) < 1 || !is_numeric($followers) || $followers < 0) {
    header('Location: ' . buildUrl('/user.php?error=' . urlencode('Please enter a valid number of followers')));
    exit;
}

// SQL
try {
    $sql = 'INSERT INTO trending(image, followers) VALUES(?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([$image, $followers]);
    
    header('Location: ' . buildUrl('/trending.php?success=1'));
    exit;
} catch(PDOException $e) {
    error_log("Add game error: " . $e->getMessage());
    header('Location: ' . buildUrl('/user.php?error=' . urlencode('Failed to add game. Please try again.')));
    exit;
}