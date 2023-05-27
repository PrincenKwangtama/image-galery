<?php
// Start the session
session_start();

// Require the database connection file
require_once('connect.php');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if ($method == 'POST') {
  
  // Get the data from the POST request
  $filename = 'image/' . $_FILES['photo']['name'];
  $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $allowed_exts = array('png', 'jpg', 'jpeg');
  
  if (in_array($file_ext, $allowed_exts)) {
    $caption = $_POST['caption'];
    $file_content = file_get_contents($_FILES['photo']['tmp_name']);
    $file_size = filesize($_FILES['photo']['tmp_name']);
    $user_id = $_SESSION['user_id'];
  
    // Insert the data into the database
    $sql = "INSERT INTO photos (filename, caption, file_content, file_size, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssis", $filename, $caption, $file_content, $file_size, $user_id);
    mysqli_stmt_execute($stmt);
  
    // Get the ID of the newly inserted photo
    $photo_id = mysqli_insert_id($conn);
  
    // Close the prepared statement
    mysqli_stmt_close($stmt);
  
    // Return the ID of the newly inserted photo as a response
    echo $photo_id;
  } else {
    // Return an error message if the file extension is not allowed
    http_response_code(400);
    echo 'Error: Only PNG, JPG, and JPEG files are allowed';
  }
  
}

// Close the database connection
mysqli_close($conn);
?>
