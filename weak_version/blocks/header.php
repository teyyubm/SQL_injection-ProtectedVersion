<?php 
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../lib/config.php';
}
?>
<header class="container">
    <span class="logo">Logo</span>
    <nav>
        <ul>
            <li class="active"><a href="<?php echo BASE_PATH; ?>/">Home</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/about.php">About Us</a></li>

            <?php 
                if(isset($_COOKIE['login']) )
                    echo '<li><a style = "color: orange;" href="'.BASE_PATH.'/user.php">My Profile</a></li>';
                else
                    echo '<li><a href="'.BASE_PATH.'/auth.php">Log In</a></li>';

            ?>

            
            <li class="btn"><a href="<?php echo BASE_PATH; ?>/contacts.php">Contacts</a></li>
        </ul>
    </nav>
        </header>