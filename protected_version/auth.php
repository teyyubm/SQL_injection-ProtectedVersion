<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>Log In</title>
    
       
</head>
<body>
    
    <?php require_once "blocks/header.php"; ?>

    

    <div class="feedback"> 
        <div class="container">
            <h2>Log in</h2>
            <p>Lorem Ipsum is simply dummy text of the printing .</p>

            <?php
            // Display error messages
            if (isset($_GET['error'])) {
                $error_msg = htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8');
                echo '<div style="background-color: #f44336; color: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;"> ' . $error_msg . '</div>';
            }
            if (isset($_GET['expired']) && $_GET['expired'] == '1') {
                echo '<div style="background-color: #ff9800; color: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;"> Your session has expired due to inactivity. Please log in again.</div>';
            }
            if (isset($_GET['success']) && $_GET['success'] == '1') {
                echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;"> Registration successful! Please log in.</div>';
            }
            ?>

            <form method="post" action="<?php echo BASE_PATH; ?>/lib/auth.php">
                <div class="inline">
                    <div>
                        <label>Login</label>
                        <input type="text" name="login" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                </div>
                
                
                
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>

       

    

    <?php require_once "blocks/footer.php"; ?>

    
</body>
</html>