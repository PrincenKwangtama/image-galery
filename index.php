<?php
session_start();

if (isset($_SESSION['user_id'])) {
  // Redirect to main.php if the user is already logged in
  header("Location: main.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login Page</title>
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

  <div class="container mt-5">  
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4>Login</h4>
          </div>
          <div class="card-body">

            <?php if (isset($_SESSION['login_error'])): ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['login_error']; ?>
              </div>
              <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['login_success'])): ?>
              <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['login_success']; ?>
              </div>
              <?php unset($_SESSION['login_success']); ?>
            <?php endif; ?>

            <form method="POST" action="login.php">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary">Login</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>