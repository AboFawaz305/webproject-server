<?php
/* echo "Script Name: " . $_SERVER['PHP_SELF'] . "<br>"; */
/* echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>"; */
/* echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>"; */
/* echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "<br>"; */
/* echo "Remote Address: " . $_SERVER['REMOTE_ADDR'] . "<br>"; */
/**/

/* echo "<pre>"; */
/* print_r($_SERVER); */
/* echo "</pre>"; */
/* echo "<br>"; */

$parts = explode("/", $_SERVER['REQUEST_URI']);

/* echo "<pre>"; */
/* echo "<br>"; */
/* print_r($parts); */
/* echo "</pre>"; */
/* echo "<br>"; */

$arg1 = $parts[3];

header('Content-type: application/json; charset=UTF-8');

switch ($parts[2]) {
case 'articles':
  include("controllers/ArticlesController.php");
  break;

case 'comments':
  include("./controllers/CommentsController.php");
  break;

case 'login':
  include("./controllers/LoginController.php");
  break;

case 'register':
  include("./controllers/RegisterController.php");
  break;

default:
  http_response_code(404);
  break;
}
