<?php
$host = "localhost"; // Shared hosting usually localhost
$dbname = "rsoa_rsoa17";
$username = "uvr4xjng8qjzg";
$password = "xj9uwxc8g9xg";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
