<?php
// Require the database connection file
// Require the database connection file
require_once('connect.php');

// Check if user is logged in
session_start();

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is PUT
if ($method == 'PUT') {
  // Get the photo ID and new caption from the PUT request
  $input_data = json_decode(file_get_contents("php://input"), true);
  $photo_id = isset($input_data['id']) ? $input_data['id'] : null;
  $new_caption = isset($input_data['caption']) ? $input_data['caption'] : null;


  // Verify that the user has permission to edit the photo caption
  $sql = "SELECT * FROM photos WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $photo_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $photo = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  if (!$photo || $photo['user_id'] != $_SESSION['user_id']) {
    // Return an error response if the user is not authorized to edit the caption
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User does not have permission to edit this caption']);
    exit();
  }

  // Update the photo caption in the database
  $sql = "UPDATE photos SET caption = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "si", $new_caption, $photo_id);
  mysqli_stmt_execute($stmt);

  // Close the prepared statement
  mysqli_stmt_close($stmt);

  // Return a success response
  header('Content-Type: application/json');
  echo json_encode(['message' => 'Photo caption updated successfully', 'photo_id' => $photo_id, 'new_caption' => $new_caption]);
}

// Close the database connection
mysqli_close($conn);

?>
