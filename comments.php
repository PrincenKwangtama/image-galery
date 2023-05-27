<?php
// Require the database connection file
require_once('connect.php');

// Start a session (if one has not already been started)
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
  // Return an error response
  $response = array('status' => 'error', 'message' => 'User authentication failed');
  echo json_encode($response);
  exit;
}



// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST or DELETE
if ($method == 'POST') {

  // Get the comment data from the POST request
  $photo_id = $_POST['photo_id'];
  $comment_text = $_POST['comment_text'];
  $user_id = $_POST['user_id'];

  // Insert the new comment into the database
  $sql = "INSERT INTO comments (user_id, photo_id, comment_text) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "iis", $user_id, $photo_id, $comment_text);
  mysqli_stmt_execute($stmt);

  // Close the prepared statement
  mysqli_stmt_close($stmt);

  // Return a success response
  $response = array('status' => 'success');
  echo json_encode($response);

} 

// Close the database connection
mysqli_close($conn);
