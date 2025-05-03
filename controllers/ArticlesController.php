<?php
// Turn on error reporting for debugging (remove or lower in production)

// Always respond with JSON
header('Content-Type: application/json; charset=utf-8');

// Capture and sanitize the first URL segment, if present.
// e.g. routing /api/articles/123 → $arg1 = '123'
$arg1 = '';
if (!empty($_SERVER['PATH_INFO'])) {
    // PATH_INFO might be like "/123" or "/foo/bar"
    $parts = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    $arg1 = filter_var($parts[0], FILTER_SANITIZE_STRING);
}

// If an argument was passed, hand off to the ArticleController
if ($arg1 !== '') {
    include __DIR__ . '/controllers/ArticleController.php';
    exit;  // stop further execution
}

// Include the database connection once
require_once 'database.php';

// Handle POST → create a new article
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON payload']);
        exit;
    }

    // Lookup the author_id from username
    $prep = $pdo->prepare("
        SELECT user_id 
        FROM Users 
        WHERE username = :username
        LIMIT 1
    ");
    $prep->execute([
        ':username' => $data['username'] ?? ''
    ]);

    $user = $prep->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    $author_id = $user['user_id'];

    // Insert the new article
    $prep = $pdo->prepare("
        INSERT INTO Articles
          (title, content, summary, author_id, title_img_src, title_img_alt, quote)
        VALUES
          (:title, :content, :summary, :author_id, :title_img_src, :title_img_alt, :quote)
    ");

    $prep->execute([
        ':title'         => $data['title']         ?? '',
        ':content'       => $data['content']       ?? '',
        ':summary'       => $data['summary']       ?? '',
        ':author_id'     => $author_id,
        ':title_img_src' => $data['title_img_src'] ?? '',
        ':title_img_alt' => $data['title_img_alt'] ?? '',
        ':quote'         => $data['quote']         ?? '',
    ]);

    if ($prep->rowCount() > 0) {
        echo json_encode(['success' => 'Article saved']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save article']);
    }

    // Clean up and exit
    $pdo = null;
    exit;
}

// For GET → return all articles with usernames
$result = $pdo->query("
    SELECT A.*, U.username
    FROM Articles AS A
    JOIN Users    AS U ON A.author_id = U.user_id
");
$articles = $result->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($articles);

// Close the DB connection
$pdo = null;

