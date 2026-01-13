<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>Registration</title>
    
       
</head>
<body>
    
    <?php require_once "blocks/header.php"; ?>

    

    <div class="feedback"> 
        <div class="container">
            <h2>Registration</h2>
            <p>Lorem Ipsum is simply dummy text of the printing .</p>

            <?php
            // Display success message
            if (isset($_GET['success']) && $_GET['success'] == '1') {
                echo '<div style="background-color: #4CAF50; color: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;"> Registration successful! You can now log in.</div>';
            }
            
            // Display error messages
            if (isset($_GET['error'])) {
                $error_msg = htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8');
                echo '<div style="background-color: #f44336; color: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;"> ' . $error_msg . '</div>';
            }
            ?>

            <form method="post" action="<?php 
                $form_action = buildUrl('/lib/reg.php');
                // Debug: uncomment next line to see the actual URL
                // echo '<!-- Form action: ' . htmlspecialchars($form_action) . ' -->';
                echo $form_action;
            ?>">
                <div class="inline">
                    <div>
                        <label>Login</label>
                        <input type="text" name="login" required minlength="2">
                    </div>
                    <div>
                        <label>Name</label>
                        <input type="text" name="username" required minlength="2">
                    </div>
                </div>
                
                <label>Email</label>
                <input class="one_line" type="email" name="email" required>
                
                
                <label>Password (minimum 6 characters)</label>
                <input class="one_line" type="password" name="password" required minlength="6">
                
                <button type="submit">Register</button>
            </form>
        </div>
    </div>

       

    

    <?php require_once "blocks/footer.php"; ?>

    
</body>
</html>