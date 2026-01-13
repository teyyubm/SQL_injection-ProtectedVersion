<?php require_once "lib/config.php"; ?>
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

    

    <div class="feedback"> 
        <div class="container">
            <h2>My Profile</h2>
            <p> Welcome <b><?php echo $_COOKIE['login']; ?></b> !</p>

            <form method="post" action="<?php echo BASE_PATH; ?>/lib/add_game.php" enctype="multipart/form-data">
                
                
                <label>Image</label>
                <input class="one_line" type="file" name="image">
                
                
                <label>Folowers</label>
                <input class="one_line" type="text" name="followers">
                
                <button type="submit">ADD</button>
                <a id="log_out" href="<?php echo BASE_PATH; ?>/lib/logout.php">LOG OUT</a>
            </form>
        </div>
    </div>

       

    

    <?php require_once "blocks/footer.php"; ?>

    
</body>
</html>