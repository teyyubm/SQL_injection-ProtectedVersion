<?php 
try {
    $pdo = new PDO("mysql:host=db.fr-pari1.bengt.wasmernet.com;dbname=gamedev_php;port=10272","57ac0bfa7d5f8000f19beab0f3ec","069657ac-0bfa-7f62-8000-1fb9b38b3edc");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}