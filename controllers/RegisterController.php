<?php
/* echo "Inside Articles Controller"; */
if ($_SERVER['REQUEST_METHOD'] != "POST") {
  http_response_code(405);
  die();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

/* print_r($data); */

// Inlcude the database connection.
include("database.php");

$hashed_password = password_hash($data["password"], PASSWORD_DEFAULT);

$prep = $pdo->prepare("SELECT * FROM Users WHERE username = :username;");
$prep->bindParam(":username", $data['username']);
$prep->execute();
$results = $prep->fetchAll(PDO::FETCH_ASSOC);

if(!empty($results)){
  echo json_encode([
    "error" => "username already exists."
  ]);
  die();
}

$prep = $pdo->prepare("INSERT INTO Users (username, hashed_password) VALUES (:username, :hashed_password);");
$prep->bindParam(":username", $data['username']);
$prep->bindParam(":hashed_password", $hashed_password);
$prep->execute();

echo json_encode(
  [
    "success" => "User registered"
  ]
);

// Close the database connection.
$pdo = null;
