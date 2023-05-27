<?php
// Include database connection file
require_once('connect.php');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is GET
if ($method == 'GET') {
  $sql = "SELECT * FROM users";
  $result = $conn->query($sql);

  if (!$result) {
    http_response_code(500);
    echo json_encode(array("message" => "Unable to fetch users from database."));
    exit();
  }

  $users = array();

  while ($row = $result->fetch_assoc()) {
    $users[$row['id']] = array(
      'id' => $row['id'],
      'username' => $row['username']
    );
  }

  $conn->close();

  // Send the response as JSON
  header('Content-Type: application/json');
  echo json_encode(array_values($users), JSON_UNESCAPED_SLASHES);
} else {
  // Send a 405 Method Not Allowed response
  header('Allow: GET');
  header('HTTP/1.1 405 Method Not Allowed');
  echo json_encode(array("message" => "405 Method Not Allowed"));
}
