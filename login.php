<?php
// Include database connection file
require_once('connect.php');

// Check the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process only when method is POST
if ($method == 'POST') {
	// Get the input data from the request
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Check if the username and password are correct
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($sql);

	// Check if query was successful and if there is a matching user
	if ($result && $result->num_rows > 0) {
		// Start a session and save the user ID and username in it
		session_start();
		$user = $result->fetch_assoc();
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['username'] = $user['username'];

		// Respond with JSON data
		$response = array(
			'success' => true,
			'user_id' => $user['id'],
			'message' => 'Login successful'
		);
		echo json_encode($response);
		
		// Redirect to the main page
        header('Location: main.php');
        exit();
	} else {
		// If the username and password are incorrect, show an error message
		$response = array(
			'success' => false,
			'message' => 'Invalid username or password'
		);
		echo json_encode($response);
	}
} else {
	// If the request method is not POST, show an error message
	$response = array(
		'success' => false,
		'message' => 'Invalid request method'
	);
	echo json_encode($response);
}
