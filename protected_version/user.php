<?php 
require_once "lib/config.php";
require_once "lib/session_check.php";

// Check if user is logged in
requireLogin();

// Get user login from session
$user_login = getUserLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>My Profile</title>
    
       
</head>
<body>
    
    <?php require_once "blocks/header.php"; ?>

    <?php
    // Display success/error messages
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo '<div class="container" style="margin-top: 20px;"><div style="background-color: #4CAF50; color: white; padding: 15px; border-radius: 5px; text-align: center;"> Game added successfully!</div></div>';
    }
    if (isset($_GET['error'])) {
        $error_msg = htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8');
        echo '<div class="container" style="margin-top: 20px;"><div style="background-color: #f44336; color: white; padding: 15px; border-radius: 5px; text-align: center;"> ' . $error_msg . '</div></div>';
    }
    ?>

    <div class="feedback"> 
        <div class="container">
            <h2>My Profile</h2>
            <p> Welcome <b><?php echo htmlspecialchars($user_login, ENT_QUOTES, 'UTF-8'); ?></b> !</p>

            <form method="post" action="<?php echo BASE_PATH; ?>/lib/add_game.php" enctype="multipart/form-data">
                <label>Image</label>
                <input class="one_line" type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/avif" required>
                
                
                <label>Folowers</label>
                <input class="one_line" type="number" name="followers" min="0" required>
                
                <button type="submit">ADD</button>
                <a id="log_out" href="<?php echo BASE_PATH; ?>/lib/logout.php">LOG OUT</a>
            </form>
        </div>
    </div>

       

    

    <?php require_once "blocks/footer.php"; ?>

    
</body>
</html>