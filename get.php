<?php
// Include database connection file
require_once('connect.php');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is GET
if ($method == 'GET') {
    // Get all photos
  $sql_photos = "SELECT id, user_id, filename, caption, file_size, upload_date FROM photos";
  $result_photos = $conn->query($sql_photos);

  // Check if query was successful
  if (!$result_photos) {
    die("Query failed: " . $conn->error);
  }

  // Create an empty array to store the photos
  $photos = array();

  // Loop through the results and add each photo to the array
  while ($row_photo = $result_photos->fetch_assoc()) {
    $id = $row_photo['id'];
    $photos[$id] = array(
      'id' => $id,
      'user_id' => $row_photo['user_id'],
      'filename' => $row_photo['filename'],
      'caption' => $row_photo['caption'],
      'file_size' => $row_photo['file_size'],
      'upload_date' => $row_photo['upload_date'],
      'username' => null,
      'comments' => array()
    );
  }

  // Get all users
  $sql_users = "SELECT id, username FROM users";
  $result_users = $conn->query($sql_users);

  // Check if query was successful
  if (!$result_users) {
    die("Query failed: " . $conn->error);
  }

  // Create an empty array to store the users
  $users = array();

  // Loop through the results and add each user to the array
  while ($row_user = $result_users->fetch_assoc()) {
    $id = $row_user['id'];
    $users[$id] = array(
      'id' => $id,
      'username' => $row_user['username']
    );
  }

  // Get all comments
  $sql_comments = "SELECT photo_id, user_id, comment_text, comment_date FROM comments";
  $result_comments = $conn->query($sql_comments);

  // Check if query was successful
  if (!$result_comments) {
    die("Query failed: " . $conn->error);
  }

  // Loop through the results and add each comment to the corresponding photo
  while ($row_comment = $result_comments->fetch_assoc()) {
    $photo_id = $row_comment['photo_id'];

    // If the photo exists, add the comment to its comments array
    if (isset($photos[$photo_id])) {
      $comment = array(
        'user_id' => $row_comment['user_id'],
        'username' => null,
        'comment_text' => $row_comment['comment_text'],
        'comment_date' => $row_comment['comment_date']
      );

      // If the user exists, set the username for the comment
      if (isset($users[$comment['user_id']])) {
        $comment['username'] = $users[$comment['user_id']]['username'];
      }

      array_push($photos[$photo_id]['comments'], $comment);
    }
  }

  // Set the username for each photo and comment
  foreach ($photos as &$photo) {
    // If the user exists, set the username for the photo
    if (isset($users[$photo['user_id']])) {
      $photo['username'] = $users[$photo['user_id']]['username'];
    }

    // Set the username for each comment
    foreach ($photo['comments'] as &$comment) {
      if (isset($users[$comment['user_id']])) {
        $comment['username'] = $users[$comment['user_id']]['username'];
      }
    }
  }

// Close the database connection
$conn->close();

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode(array_values($photos), JSON_UNESCAPED_SLASHES);

} else {
  // Send a 405 Method Not Allowed response
  header('Allow: GET');
  header('HTTP/1.1 405 Method Not Allowed');
  echo '405 Method Not Allowed';
}
