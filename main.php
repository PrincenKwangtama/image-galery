<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
	// If the user is not logged in, redirect to the login page
	header('Location: index.php');
	exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Logout user when logout button is clicked
if (isset($_POST["logout"])) {
  session_unset();
  session_destroy();
  header("Location: index.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Photo Gallery</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="script/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Photo Gallery</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#">Welcome, <?php echo $_SESSION["username"]; ?></a>
        </li>
        <li class="nav-item">
          <form method="POST">
            <button class="nav-link btn btn-link" type="submit" name="logout">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div id="photo-gallery" class="row">
          <div class="col-md-6"></div>
          <div class="col-md-6"></div>
        </div>
      </div>
      <div class="col-md-6">
        <form id="upload-form">
          <div class="form-group">
            <label for="photo" style="color: white;">Choose a photo:</label>
            <input type="file" class="form-control-file" id="photo" name="photo" style="color: white;">
          </div>
          <div class="form-group">
            <label for="caption" style="color: white;">Caption:</label>
            <input type="text" class="form-control" id="caption" name="caption">
          </div>
          <button type="submit" class="btn btn-primary" id="upload-btn">Upload</button>
        </form>
        <h2 style="color: white;">User <?php echo $user_id; ?> logged in as <?php echo $username; ?></h2>
      </div>
    </div>
  </div>
  <div class="modal fade" id="comment-modal" tabindex="-1" role="dialog" aria-labelledby="comment-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="comment-modal-label">Add a comment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="comment-form">
            <div class="form-group">
              <label for="comment-name">Name:</label>
              <input type="text" class="form-control" id="comment-name" name="comment-name">
            </div>
            <div class="form-group">
              <label for="comment-text">Comment:</label>
              <textarea class="form-control" id="comment-text" name="comment-text" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="comment-btn">Save changes</button>
        </div>
      </div>
  </div>
  <script src="bootstrap/js/jquery.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <!-- <script src="script/app.js"></script> -->
  <script>
  function getPhotos(refresh = false) {
  const method = 'GET';
  let url = 'get.php';

  if (refresh) {
    url += '?_ts=' + new Date().getTime();
  }

  fetch(url, {
    method: method,
  })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      const photos = data;
      let html = '';
      let counter = 0; // count the number of photos added to the row

      photos.forEach(photo => {
        // If the counter is 0, start a new row
        if (counter === 0) {
          html += '<div class="row">';
        }

        html += '<div class="col-md-6">';
        html += '<div class="card mb-4 box-shadow">';
        html += '<img class="card-img-top" src="' + photo.filename + '" alt="' + photo.caption + '">';
        html += '<div class="card-body">';
        html += `<p class="card-text">${photo.caption} <button type="button" class="btn btn-link edit-caption-btn" data-photo-id="${photo.id}">Edit</button></p>`;

        // add button to show comments in notification bar
        html += '<button class="btn btn-secondary show-comments-btn" data-photo-id="' + photo.id + '">Show comments</button>';
        html += '<div class="notification-bar" data-photo-id="' + photo.id + '"></div>';

        // add comment section
        html += '<div class="comments-section">';
        html += '<div class="comments-section-inner d-none"></div>';
        html += '<div class="comments-footer">';
        html += '<form class="comment-form" data-user-id="1">';
        html += '<div class="form-group">';
        html += '<input type="hidden" name="photo_id" value="' + photo.id + '">';
        html += '<label for="comment_text">Add Comment</label>';
        html += '<input type="text" class="form-control" id="comment_text" name="comment_text">';
        html += '</div>';
        html += '<button type="submit" class="btn btn-primary add-comment-btn">Submit</button>';
        html += '</form>';
        html += '</div>'; // end of comments-footer
        html += '</div>'; // end of comments-section

        // add delete button for the photo
        html += '<button class="btn btn-danger delete-photo-btn" data-photo-id="' + photo.id + '">Delete</button>';

        html += '</div>';
        html += '</div>';
        html += '</div>';

        // Increment the counter
        counter++;

        // If the counter is 2, end the row and reset the counter
        if (counter === 2) {
          html += '</div>';
          counter = 0;
        }
      });

      // If there is an odd number of photos, close the last row
      if (counter === 1) {
        html += '</div>';
      }

      document.getElementById('photo-gallery').innerHTML = html;

      // Add event listener for delete photo buttons
      const deletePhotoButtons = document.querySelectorAll('.delete-photo-btn');
      deletePhotoButtons.forEach(button => {
        button.addEventListener('click', deletePhoto);
      });

      // Add event listener for comment form submissions
      const commentForms = document.querySelectorAll('.comment-form');
      commentForms.forEach(form => {
        form.addEventListener('submit', addComment);
      });

      // Add event listener for show comments buttons
      const showCommentsButtons = document.querySelectorAll('.show-comments-btn');
      showCommentsButtons.forEach(button => {
        button.addEventListener('click', function() {
          const photoId = this.dataset.photoId;
          showComments(photoId);
        });
      });

      // Add event listener for edit caption buttons
      const editCaptionButtons = document.querySelectorAll('.edit-caption-btn');
      editCaptionButtons.forEach(button => {
        button.addEventListener('click', function() {
          const photoId = this.dataset.photoId;
          editCaptionPrompt(photoId);
        });
      });
    })
      .catch(error => console.log(error));
  }

  function showComments(photoId) {
  const method = 'GET';
  let url = 'getuserincomments.php?id=' + photoId;

  fetch(url, {
    method: method,
  })
    .then(response => response.json())
    .then(data => {
      console.log(data);
      const comments = data;
      let html = '';

      comments.forEach(comment => {
        html += '<div class="comment">';
        html += '<span class="username">' + comment.username + ': </span>';
        html += '<span class="comment-text">' + comment.comment + '</span>';
        html += '</div>';
      });

      const notificationBar = document.querySelector('.notification-bar[data-photo-id="' + photoId + '"]');
      notificationBar.innerHTML = html;
      notificationBar.classList.toggle('d-none'); // toggle the visibility of the comments section
    })
    .catch(error => console.log(error));

    console.log("showComments was called with photoId: ", photoId);
  }
  
  window.onload = function() {
      getPhotos();
    };

  function uploadPhoto(event) {
    event.preventDefault();
  
    const form = document.getElementById('upload-form');
    const formData = new FormData(form);
  
    fetch('post.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        // Update the cache/session with the newly uploaded photo
        const currentPhotos = JSON.parse(sessionStorage.getItem('photos'));
        const updatedPhotos = currentPhotos ? [...currentPhotos, data] : [data];
        sessionStorage.setItem('photos', JSON.stringify(updatedPhotos));
  
        // Refresh the photo gallery to show the newly uploaded photo
        getPhotos(true);
      })
      .catch(error => {
        console.error(error);
        // alert('Error uploading photo. Please try again.');
      });
  }
    
  const form = document.querySelector('#upload-form');
  const submitBtn = document.querySelector('#upload-btn');
  
  submitBtn.addEventListener('click', uploadPhoto);

  // Edit photo caption script
  function editCaption(photoId, newCaption) {
    fetch('editcaption.php', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        id: photoId,
        caption: newCaption
      })
    })
    .then(response => {
      if (response.ok) {
        response.json().then(data => {
          alert(`Photo caption updated successfully!\nPhoto ID: ${data.photo_id}\nNew caption: ${data.new_caption}`);
          location.reload();
        });
      } else {
        alert('An error occurred while updating the photo caption');
      }
    })
    .catch(error => console.log(error));
  }

  function editCaptionPrompt(photoId) {
    let newCaption = prompt('Enter the new caption for the photo:');
    if (newCaption !== null) {
      editCaption(photoId, newCaption);
    }
  } 

  function addComment(event) {
    event.preventDefault();
  
    const formData = new FormData(event.target);
    const user_id = <?php echo $_SESSION['user_id']; ?>;
    formData.append('user_id', user_id);

    fetch('comments.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          // Update the cache/session with the newly added comment
          const currentComments = JSON.parse(sessionStorage.getItem('comments'));
          const updatedComments = currentComments ? [...currentComments, data.comment] : [data.comment];
          sessionStorage.setItem('comments', JSON.stringify(updatedComments));
  
          // Refresh the photo gallery to show the newly added comment
          getPhotos(true);
        } else {
          console.error(data.message);
        }
      })
      .catch(error => console.error(error));
  }
    
    

  const commentForm = document.querySelector('#comment-form');
  commentForm.addEventListener('submit', addComment);

  function deletePhoto(event) {
    const photoId = event.target.dataset.photoId;
    if (!photoId) {
      console.error('Photo ID not found');
      return;
    }

    const confirmed = confirm('Are you sure you want to delete this photo?');
    if (!confirmed) {
      return;
    }

    const method = 'DELETE';
    const url = 'delete.php?id=' + photoId;

    fetch(url, {
      method: method,
    })
      .then(response => response.text())
      .then(data => {
        console.log(data); // log the response data to the console
        // Update the cache/session by removing the deleted photo
        const currentPhotos = JSON.parse(sessionStorage.getItem('photos'));
        const updatedPhotos = currentPhotos.filter(photo => photo.id !== photoId);
        sessionStorage.setItem('photos', JSON.stringify(updatedPhotos));

        // Reload the page to show the updated list of photos
        window.location.reload(true);
      })
      .catch(error => console.error(error));
  }
  </script>
</body>
</html>