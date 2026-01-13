<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>JS</title>
    
       
</head>
<body>
    
    <?php require_once "blocks/header.php"; ?>

    <div class="container hero_contacts">
        <h1>Lorem Ipsum is simply dummy text of the printing and.</h1>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
        <img src="<?php echo BASE_PATH; ?>/imgs/blocks/Huge Global.png" alt="">
    </div>

    <div class="feedback"> 
        <div class="container">
            <h2>Say hello</h2>
            <p>Lorem Ipsum is simply dummy text of the printing .</p>

            <?php
            // Display success notification
            if (isset($_GET['success']) && $_GET['success'] == '1') {
                echo '<div id="notification" class="notification success" style="background-color: #4CAF50; color: white; padding: 15px 40px 15px 15px; margin-bottom: 20px; border-radius: 5px; position: relative; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                        
                        Thank you! Your message has been submitted successfully. We will get back to you soon.
                        <span class="close-notification" onclick="this.parentElement.remove()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; font-weight: bold; opacity: 0.8;">&times;</span>
                      </div>';
            }
            
            // Display error notification
            if (isset($_GET['error'])) {
                $error_msg = htmlspecialchars(urldecode($_GET['error']));
                echo '<div id="notification" class="notification error" style="background-color: #f44336; color: white; padding: 15px 40px 15px 15px; margin-bottom: 20px; border-radius: 5px; position: relative; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                       
                        Error: ' . $error_msg . '
                        <span class="close-notification" onclick="this.parentElement.remove()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; font-weight: bold; opacity: 0.8;">&times;</span>
                      </div>';
            }
            ?>

            <form method="post" action="<?php echo BASE_PATH; ?>/lib/contact.php">
                <div class="inline">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name" required minlength="2" value="<?php echo isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name" required minlength="2" value="<?php echo isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                </div>
                
                <label>Email Address</label>
                <input class="one_line" type="email" name="email" required value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                
                
                <label>Message</label>
                <textarea class="one_line" name="message" required minlength="10"><?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                
                <button type="submit">Get in touch</button>
            </form>
        </div>
    </div>

    <style>
        .notification {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .close-notification:hover {
            opacity: 1 !important;
        }
    </style>
    
    <script>
        // Auto-hide notification after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const notification = document.getElementById('notification');
            if (notification) {
                setTimeout(function() {
                    notification.style.transition = 'opacity 0.5s, transform 0.5s';
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(function() {
                        notification.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>

       

    

    <?php require_once "blocks/footer.php"; ?>

    
</body>
</html>