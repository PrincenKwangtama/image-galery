<?php
// Require the database connection file
require_once('connect.php');


// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is DELETE
if ($method == 'DELETE') {

  // Get the user ID from the DELETE request
  $user_id = $_GET['id'];


  // Check if the user exists in the database
  $sql = "SELECT id FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $num_rows = mysqli_stmt_num_rows($stmt);

  // Close the prepared statement
  mysqli_stmt_close($stmt);

  // If user exists, delete it from the database
  if ($num_rows > 0) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: index.php");
    exit();
  } else {
    // Return an error response if the user does not exist
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User does not exist']);
    exit();
  }

}

// Close the database connection
mysqli_close($conn);
?>
