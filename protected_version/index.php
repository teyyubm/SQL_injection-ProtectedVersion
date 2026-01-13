<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo buildUrl('/style.css'); ?>">
    <title>Home Page</title>
    <script src="<?php echo buildUrl('/java.js'); ?>"></script>
       
</head>
<body>
    <div class="wrapper">
        <?php require_once "blocks/header.php"; ?>
    
        <main>
            <div class="hero container">
                <div class="hero info">
                    <h1>3D Game DEV</h1>
                    <h2>Work that we produce for our clients</h2>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Adipisci laudantium, quisquam nam non illum earum!</p>
                    <button class="btn">Get more details</button>
                </div>
                <img class="controller" src="<?php echo buildUrl('/imgs/controller-Photoroom.png-Photoroom.png'); ?>" alt="">
        
            </div>

            <div class="trending container">
                <a class="see_all" href="<?php echo buildUrl('/trending.php'); ?>">SEE ALL</a>
                <h2>Currently Trending Games</h2>
                
                <div class="blocks">

                <?php
                    //DB
                    require_once "lib/db.php";

                    // SQL
                    $sql = 'SELECT * FROM trending ORDER BY id DESC LIMIT 4';
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
                            <img src="'.buildUrl('/imgs/blocks/'. $image).'" alt="">
                            <p>' . $followers . ' Followers</p>
                        </a>
                    </div>';
                    }
                ?>    

                </div>

                <div class="post">
                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. </h3>
                    <h4>Lorem Ipsum </h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing Maxime asperiores error, numquam  <br>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Harum, reiciendis! <br>
                        Lorem ipsum dolor sit amet.                      
                    </p>
                    <img class="poster" src="<?php echo buildUrl('/imgs/poster_MilesMorales_PS5_Whoops.webp'); ?>" alt="">
                </div>
            </div>

            <div class="features">
                <div class="container">
                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been 
                        <br>the industry's standard dummy text ever since the 1500s,</p>
                     <div class="info">
                        <div class="block">
                        
                            <img src="<?php echo buildUrl('/imgs/features/feature1.png'); ?>" alt="">
                                
        
                            <p>Mobile Game Development</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                        <div class="block">
                            
                            <img src="<?php echo buildUrl('/imgs/features/feature2.png'); ?>" alt="">
                                
        
                            <p>PC Game Development</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                        <div class="block">
                            
                            <img src="<?php echo buildUrl('/imgs/features/feature3.png'); ?>" alt="">
                                
        
                            <p>PS4 Game Development</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                        <div class="block">
                            <img src="<?php echo buildUrl('/imgs/features/feature4.png'); ?>" alt="">
                                
                            <p>AR/VR Solutions</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                        <div class="block">
                            <img src="<?php echo buildUrl('/imgs/features/feature5.png'); ?>" alt="">
                                
                            <p>AR/ VR design</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                        <div class="block">
                            <img src="<?php echo buildUrl('/imgs/features/feature6.png'); ?>" alt="">
                                
                            <p>3D Modelings</p>
        
                            <img src="<?php echo buildUrl('/imgs/features/arrow 3.png'); ?>" alt="">
                                
                        </div>
                     </div>
                </div>
            </div>

            <div class="container projects">
                <h3>Our Recent Projects</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                <div class="images">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 15.png'); ?>" alt="">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 16.png'); ?>" alt="">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 17.png'); ?>" alt="">
                </div>
                <div class="images">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 19.png'); ?>" alt="">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 18.png'); ?>" alt="">
                    <img src="<?php echo buildUrl('/imgs/projects/Rectangle 20.png'); ?>" alt="">
                </div>
            </div>

            <div class="container email">
                <h3>Lorem Ipsum</h3>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
                <div class="block">
                    <div>
                        <h4>Stay in the loop</h4>
                        <p>Subscribe to receive the latest news and updates about TDA.
                            We promise not to spam you! </p>
                    </div>
                    <div>
                        <input type="email" id="emailField" placeholder="Enter email address">
                        <button onclick="checkEmail()">Continue</button>
                    </div>

                    <button class="scroll" onclick="scroll_up() ">SCROLL UP</button>
                    <script>
                        function scroll_up() {
                            window.scrollTo(0, 0);
                        }


                        function checkEmail() {
                            let email = document.querySelector('#emailField').value;
                            if(!email.includes('@')) alert('Where is "@"?');
                            else if (!email.includes ('.')) alert('Where is "."?');
                            else alert('Thank You!');

                        }
                    </script>
                </div>
            </div>

        </main>
            
    </div>

    <?php require_once "blocks/footer.php"; ?>
    

    
</body>
</html>