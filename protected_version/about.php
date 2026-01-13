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
    <div class="wrapper">
        <?php require_once "blocks/header.php"; ?>

        <div class="hero_about container">
            <div class="info">
                <h1>Lorem Ipsum is simply dummy text of the printing and.</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                <button>Get in touch</button>
            </div>
            <img src="<?php echo BASE_PATH; ?>/imgs/banners/about_banner.png" alt="">
        </div>
        
        <div class="container work">
            <h2>Why work with us</h2>
            <div class="blocks">
                <div class="block">
                    <span class="badge purple">Lorem ipsum</span>
                    <h3>Lorem Ipsum</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                </div>

                <div class="block">
                    <span class="badge brown">Lorem ipsum</span>
                    <h3>Lorem Ipsum</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                </div>

                <div class="block">
                    <span class="badge green">Lorem ipsum</span>
                    <h3>Lorem Ipsum</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                </div>

            </div>
        </div>

    </div>

    <?php require_once "blocks/footer.php"; ?>
    
</body>
</html>