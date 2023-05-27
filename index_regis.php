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

	// Check if the username is already taken
	$sql = "SELECT * FROM users WHERE username = '$username'";
	$result = $conn->query($sql);
	if ($result && $result->num_rows > 0) {
		// If the username is already taken, show an error message
		$response = array(
			'success' => false,
			'message' => 'Username already taken'
		);
		echo json_encode($response);
		exit();
	}

	// Insert the new user into the database
	$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
	if ($conn->query($sql) === TRUE) {
		// Start a session and save the user ID and username in it
		session_start();
		$user_id = $conn->insert_id;
		$_SESSION['user_id'] = $user_id;
		$_SESSION['username'] = $username;

		// Respond with JSON data
		$response = array(
			'success' => true,
			'user_id' => $user_id,
			'message' => 'Registration successful'
		);
		echo json_encode($response);    
		
		// Redirect to the main page
        header('Location: regisform.php');
        exit();
	} else {
		// If the query was not successful, show an error message
		$response = array(
			'success' => false,
			'message' => 'Registration failed'
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
?>
