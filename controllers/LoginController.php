<?php
/* echo "Inside Articles Controller"; */
if ($_SERVER['REQUEST_METHOD'] != "POST") {
  http_response_code(405);
  die();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Inlcude the database connection.
include("database.php");


$prep = $pdo->prepare("SELECT * FROM Users WHERE username = :username;");
$prep->bindParam(":username", $data['username']);
$prep->execute();
$results = $prep->fetchAll(PDO::FETCH_ASSOC);

if(empty($results)){
  echo json_encode([
    "error" => "Wrong username or password."
  ]);
  die();
}

/* print_r($results); */

if(!password_verify($data["password"], $results[0]["hashed_password"])){
  echo json_encode([
    "error" => "Wrong username or password."
  ]);
  die();
}

echo json_encode(
  [
    "success" => "User Found."
  ]
);

// Close the database connection.
$pdo = null;
