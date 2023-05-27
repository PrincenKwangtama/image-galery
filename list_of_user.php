<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User List</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="script/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">My App</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="regisform.php">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="list_of_user.php">List of Users</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<div class="container mt-4">
  <h1>User List</h1>

  <table class="table" style="color: white;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        // Fetch users from the server using REST API
        $users_json = file_get_contents("http://localhost:8080/SOA_UTS/showuser.php");
        $users = json_decode($users_json, true);

        // Check if any users were found
        if (empty($users)) {
          echo "<tr><td colspan='4'>No users found</td></tr>";
        } else {
          // Loop through the users and display each one in a table row
          foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td><button class='btn btn-danger' onclick='deleteUser(" . $user['id'] . ")'>Delete</button></td>";
            echo "</tr>";
          }
        }
      ?>
    </tbody>
  </table>
</div>

<!-- Delete user script -->
<script>
function deleteUser(userId) {
  if (confirm("Are you sure you want to delete this user?")) {
    // Send DELETE request to server using REST API
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", "http://localhost:8080/SOA_UTS/deleteuser.php?id=" + userId, true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        alert("User deleted successfully " + userId);
        location.reload();
      } else {
        alert("An error occurred while deleting the user");
      }
    };
    xhr.send();
  }
}
</script>

<!-- Bootstrap JS -->
<script src="bootstrap/js/bootstrap.bundle.min.js
</body>
</html>
