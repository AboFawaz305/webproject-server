<?php
/* echo "Inside Articles Controller"; */

// Inlcude the database connection.
include("database.php");

$result = $pdo->prepare("SELECT Articles.*, Users.username FROM Articles INNER JOIN Users ON author_id = user_id WHERE article_id=:article_id;");
$result->bindParam(":article_id", $arg1);
$result->execute();
$resultr = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultr);

// Close the database connection.
$pdo = null;
