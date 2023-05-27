<?php
// Require the database connection file
require_once('connect.php');

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
  // Return an error response if user is not logged in
  header('Content-Type: application/json');
  echo json_encode(['error' => 'User not logged in']);
  exit();
}

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is DELETE
if ($method == 'DELETE') {
  
  // Get the photo ID from the DELETE request
  $photo_id = $_GET['id'];

  // Get the user ID of the photo owner
  $sql = "SELECT user_id FROM photos WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $photo_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $photo_owner);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  // Verify that the user requesting the delete is the photo owner
  if ($_SESSION['user_id'] != $photo_owner) {
    // Return an error response if the user is not the photo owner
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User does not have permission to delete this photo']);
    exit();
  }
  
  // Delete the photo from the database
  $sql = "DELETE FROM photos WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $photo_id);
  mysqli_stmt_execute($stmt);
  
  // Close the prepared statement
  mysqli_stmt_close($stmt);
  
  // Return a success response
  header('Content-Type: application/json');
  echo json_encode(['message' => "Photo with ID $photo_id deleted successfully"]);
  
}

// Close the database connection
mysqli_close($conn);
?>
