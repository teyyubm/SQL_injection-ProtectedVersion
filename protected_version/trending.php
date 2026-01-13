<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>Home Page</title>
    <script src="<?php echo BASE_PATH; ?>/java.js"></script>
       
</head>
<body>
    <div class="wrapper">
        <?php require_once "blocks/header.php"; ?>
    
        <main>
            

            <div class="trending container">
                
                <h2>Currently Trending Games</h2>
                
                <div class="blocks blocks-all">

                <?php
                    //DB
                    require_once "lib/db.php";

                    // SQL
                    $sql = 'SELECT * FROM trending ORDER BY id DESC';
                    $query = $pdo->prepare($sql);
                    $query->execute();  
                    $games = $query->fetchAll(PDO::FETCH_OBJ);
                    
                    foreach ($games as $el) {
                        // Escape output to prevent XSS
                        $image = htmlspecialchars($el->image, ENT_QUOTES, 'UTF-8');
                        $followers = htmlspecialchars($el->followers, ENT_QUOTES, 'UTF-8');
                        echo '
                
                    <div class="block">
                        <a href="">
                            <img src="'.BASE_PATH.'/imgs/blocks/'. $image .'" alt="">
                            <p>' . $followers . ' Followers</p>
                        </a>
                    </div>';
                    }
                ?>    

                </div>

                

        </main>
            
    </div>

    <?php require_once "blocks/footer.php"; ?>
    

    
</body>
</html>