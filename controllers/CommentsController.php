<?php

// Inlcude the database connection.
include("database.php");

if($arg1 != ""){
	if($_SERVER['REQUEST_METHOD'] == "POST"){

		$json = file_get_contents('php://input');
		$data = json_decode($json, true);

		// Inlcude the database connection.
		include("database.php");

		$prep = $pdo->prepare("SELECT * FROM Users Where username=:username;");
		$prep->bindParam(":username",$data['username']);
		$prep->execute();
		$commenter_id = $prep->fetchAll(PDO::FETCH_ASSOC)[0]['user_id'];

		$prep = $pdo->prepare("INSERT INTO Comments (commenter_id, comment_article_id, content) VALUES (:commenter_id, :comment_article_id, :content);");
		$prep->bindParam(":commenter_id", $commenter_id);
		$prep->bindParam(":comment_article_id",$data['comment_article_id']);
		$prep->bindParam(":content",$data['content']);
		$prep->execute();

		echo json_encode(
			[
				"success" => "Article saved"
			]
		);

		// Close the database connection.
		$pdo = null;
		die();
	}

	$result = $pdo->prepare("SELECT * FROM Comments WHERE comment_article_id=:article_id;");
	$result->bindParam(":article_id", $arg1);
	$result->execute();
	$resultr = $result->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($resultr);

	// Close the database connection.
	$pdo = null;
	die();
}

$result = $pdo->query("SELECT * FROM Comments;");
$resultr = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultr);

// Close the database connection.
$pdo = null;
