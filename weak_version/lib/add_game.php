<?php
require_once __DIR__ . '/config.php';

// DB
require_once __DIR__ . '/db.php';

// Check if user is logged in
if(!isset($_COOKIE['login'])) {
    header('Location: ' . BASE_PATH . '/auth.php');
    exit;
}

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
        echo "Upload error: " . (isset($error_messages[$error_code]) ? $error_messages[$error_code] : "Unknown error ($error_code)");
        exit;
    }
    
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    
    // Use absolute path for upload folder
    $upload_dir = __DIR__ . '/../imgs/blocks/';
    
    // Create directory if it doesn't exist
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $folder = $upload_dir . $file_name;

    // Validate file type
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if(in_array($ext, $allowed)) {
        if(move_uploaded_file($tempname, $folder)) {
            $image = $file_name;
        } else {
            echo "File upload error: Could not move uploaded file. Check folder permissions.";
            exit;
        }
    } else {
        echo "Invalid file type. Allowed: " . implode(', ', $allowed);
        exit;
    }
} else {
    echo "No image uploaded. Please select a file.";
    exit;
}

$followers = trim(filter_var($_POST['followers'], FILTER_SANITIZE_SPECIAL_CHARS));

if(strlen($followers) < 1 || !is_numeric($followers)) {
    echo "Followers error: Please enter a valid number";
    exit;
}

// SQL
try {
    $sql = 'INSERT INTO trending(image, followers) VALUES(?, ?)';
    $query = $pdo->prepare($sql);
    $query->execute([$image, $followers]);
    
    header('Location: ' . BASE_PATH . '/trending.php');
    exit;
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}