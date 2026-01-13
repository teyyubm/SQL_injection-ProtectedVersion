<?php
require_once __DIR__ . '/config.php';

session_start();
setcookie("login", "", time() -3600 ,"/");
session_destroy();

header('Location: ' . BASE_PATH . '/');
