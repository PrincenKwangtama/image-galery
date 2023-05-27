<?php
// Set response header to JSON format
header('Content-Type: application/json');

// Include database connection file
require_once('connect.php');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is GET
if ($method == 'GET') {
  // Get the user id from the query string parameter
  $user_id = $_GET['id'];

  // Create SQL query to fetch user with specified id
  $sql = "SELECT * FROM users WHERE id = $user_id";

  // Execute SQL query
  $result = $conn->query($sql);

  // Check if query was successful
  if (!$result) {
    die("Query failed: " . $conn->error);
  }

  // Create an empty array to store the user data
  $user = array();

  // Fetch the user data and add it to the array
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $user = $row;
    }
    // Send the response as JSON
    echo json_encode($user);
  } else {
    // If no user was found, send an error message as JSON
    echo json_encode(array('error' => 'No user found with specified id'));
  }
} else {
  // If request method is not GET, send a 405 Method Not Allowed response
  header('Allow: GET');
  header('HTTP/1.1 405 Method Not Allowed');
  echo json_encode(array('error' => '405 Method Not Allowed'));
}

// Close the database connection
$conn->close();
?>
