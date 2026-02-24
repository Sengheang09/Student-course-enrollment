<?php
$host = "sql206.infinityfree.com";
$dbname = "if0_41232377_school_db";
$user = "if0_41232377";
$pass = "94ac4PTHKOyCUM"; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}