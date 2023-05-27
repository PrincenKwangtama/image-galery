<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $photo_id = $_GET["photo_id"];
  $sql = "SELECT * FROM comments where photo_id = $photo_id";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $comments = array();
    while ($row = $result->fetch_assoc()) {
      $comment = array(
        'user_id' => $row['user_id'],
        'comment_text' => $row['comment_text']
      );
      array_push($comments, $comment);
    }
    header('Content-Type: application/json');
    echo json_encode($comments);
  } else {
    echo "No comments found.";
  }
}

$conn->close();
?>
