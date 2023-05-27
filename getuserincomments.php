<?php
require_once('connect.php');

// Get the photo ID from the URL parameter
$photoId = $_GET["id"];

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
  // Initialize the comments array
  $comments = array();

  $sql = "SELECT comments.comment_text, comments.user_id, comments.id, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE photo_id = $photoId";
  $result = $conn->query($sql);

  // Check if the query returned any rows
  if ($result->num_rows > 0) {
    // Loop through each row of the query result and retrieve the username, user_id, and comment_id
    while($row = $result->fetch_assoc()) {
      $comment = $row["comment_text"];
      $username = $row["username"];
      $user_id = $row["user_id"];
      $comment_id = $row["id"];
      $comments[] = array("comment" => $comment, "username" => $username, "user_id" => $user_id, "comment_id" => $comment_id);
    }
  }

  // Close the database connection
  $conn->close();

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode(array_values($comments), JSON_UNESCAPED_SLASHES);

} else {
  // Send a 405 Method Not Allowed response
  header('Allow: GET');
  header('HTTP/1.1 405 Method Not Allowed');
  echo '405 Method Not Allowed';
}
