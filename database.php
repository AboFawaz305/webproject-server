<?php
$dsn = 'mysql:host=127.0.0.1;dbname=blogsAPI;charset=utf8mb4';
$username = 'blog';
$password = getenv('DATABASE_PASSWORD');

try {
  $pdo = new PDO($dsn, $username, $password);
  // Set error mode to exceptions
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "Connected successfully";
} catch (PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
}
