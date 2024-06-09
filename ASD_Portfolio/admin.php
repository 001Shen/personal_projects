<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Include your database configuration
include 'config.php';

$target_file = "";

// Handle Home Update
if (isset($_POST['update_home'])) {
    $header = $_POST['header'];
    $text = $_POST['text'];
    $cv_file = $_FILES['cv_file']['name'];
    
    // Handle file upload
    if ($cv_file) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cv_file);
        move_uploaded_file($_FILES["cv_file"]["tmp_name"], $target_file);
    } else {
        // Retrieve the existing file path from the database if no new file is uploaded
        $sql = "SELECT cv_file FROM home WHERE id=1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target_file = $row['cv_file'];
        }
    }

    // Update database
    $sql = "UPDATE home SET header='$header', text='$text', cv_file='$target_file' WHERE id=1";

    if ($conn->query($sql) === TRUE) {
        echo "Home content updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle About Update
if (isset($_POST['update_about'])) {
    // Check if the file upload field is set and not empty
    if (isset($_FILES['image_path']) && $_FILES['image_path']['name']) {
        $image_path = $_FILES['image_path'];
        
        // Handle file upload
        $target_dir = "uploads/";

        // Create the uploads directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($image_path['name']);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($image_path["tmp_name"], $target_file)) {
            // Update database
            $details = $_POST['details'];
            $sql = "UPDATE about SET image_path='$target_file', details='$details' WHERE id=1";

            if ($conn->query($sql) === TRUE) {
                echo "About content updated successfully.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Error: Failed to move the uploaded file.";
        }
    } else {
        echo "Error: Image file not provided.";
    }
}

// Handle file upload
$conn = new mysqli("localhost", "root", "", "vonn_portfolio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function updateProject($project_id, $image_field, $details_field) {
    global $conn;

    $project_image = $_FILES[$image_field]['name'];
    $project_details = $_POST[$details_field];
    $target_file = '';

    // Handle file upload
    if ($project_image) {
        $target_dir = "uploads/";

        // Create the uploads directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($project_image);
        if (!move_uploaded_file($_FILES[$image_field]["tmp_name"], $target_file)) {
            echo "Error: Failed to move the uploaded file.";
            return;
        }
    } else {
        // Retrieve the existing file path from the database if no new file is uploaded
        $sql = "SELECT image_path FROM projects WHERE id=$project_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target_file = $row['image_path'];
        }
    }

    // Update database using prepared statements
    $stmt = $conn->prepare("UPDATE projects SET image_path = ?, description = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssi", $target_file, $project_details, $project_id);

    if ($stmt->execute()) {
        echo "Project $project_id content updated successfully.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

// Execute update if the corresponding form is submitted
if (isset($_POST['update_project1'])) {
    updateProject(1, 'project1_image', 'project1_details');
}

if (isset($_POST['update_project2'])) {
    updateProject(2, 'project2_image', 'project2_details');
}

$conn->close();

// Handle Contacts Update
if (isset($_POST['update_contacts'])) {
    $contact_image = $_FILES['contact_image']['name'];
    
    // Handle file upload
    if ($contact_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($contact_image);
        move_uploaded_file($_FILES["contact_image"]["tmp_name"], $target_file);
    } else {
        // Retrieve the existing file path from the database if no new file is uploaded
        $sql = "SELECT contact_image FROM contacts WHERE id=1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $target_file = $row['contact_image'];
        }
    }

    // Update database
    $sql = "UPDATE contacts SET contact_image='$target_file' WHERE id=1";

    if ($conn->query($sql) === TRUE) {
        echo "Contacts content updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .adm-container {
      display: flex;
      height: 100%;
    }

    .adm-sidebar  {
      width: 200px;
      background-color: #0d0d0d;
      color: white;
      padding: 15px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .adm-sidebar h1 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .adm-sidebar ul {
      list-style-type: none;
      padding: 0;
      flex-grow: 1;
    }

    .adm-sidebar ul li {
      margin: 10px 0;
    }

    .adm-sidebar ul li a {
      color: white;
      text-decoration: none;
    }

    .adm-sidebar ul li a:hover {
      text-decoration: underline;
    }

    .adm-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .adm-content div {
      display: none;
    }

    .logout-btn {
      background-color: red;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 20px;
      cursor: pointer;
      text-decoration: none;
    }

    .logout-btn:hover {
      background-color: darkred;
    }
  </style>
</head>

<body class="adm-body">
  <div class="adm-container">
    <div class="adm-sidebar">
      <h1 class="adm-h1">Admin Panel</h1>
      <ul class="adm-menu">
        <li><a href="javascript:void(0)" onclick="showContent('home')">Home</a></li>
        <li><a href="javascript:void(0)" onclick="showContent('about')">About</a></li>
        <li><a href="javascript:void(0)" onclick="showContent('projects')">Projects</a></li>
        <li><a href="javascript:void(0)" onclick="showContent('contacts')">Contacts</a></li>
      </ul>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="adm-content">
      <!-- Here you can add your content for each component -->
      <div id="home">
        <h2>Home</h2>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="header">Header:</label><br>
            <textarea id="text" name="header" rows="4" cols="50"></textarea><br><br>
            <label for="text">Text:</label><br>
            <textarea id="text" name="text" rows="4" cols="50"></textarea><br><br>
            <label for="cv_file">Upload CV:</label><br>
            <input type="file" id="cv_file" name="cv_file"><br><br>
            <input type="submit" name="update_home" value="Save">
        </form>
      </div>

      <div id="about">
        <h2>About</h2>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="image">Image:</label><br>
            <input type="file" id="image" name="image_path"><br><br>
            <label for="details">Details:</label><br>
            <textarea id="adetails" name="details" rows="4" cols="50"></textarea><br><br>
            <input type="submit" name="update_about" value="Save">
        </form>
      </div>

      <div id="projects">
        <h2>Projects</h2>
        <h3>Project 1</h3>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="project1_image">Project 1 Image:</label><br>
            <input type="file" id="project1_image" name="project1_image"><br><br>
            <label for="project1_details">Project 1 Details:</label><br>
            <textarea id="project1_details" name="project1_details" rows="4" cols="50"></textarea><br><br>
            <input type="submit" name="update_project1" value="Save Project 1">
        </form>
  
        <h3>Project 2</h3>
        <form action="#" method="post" enctype="multipart/form-data">wa
            <label for="project2_image">Project 2 Image:</label><br>
            <input type="file" id="project2_image" name="project2_image"><br><br>
            <label for="project2_details">Project 2 Details:</label><br>
            <textarea id="project2_details" name="project2_details" rows="4" cols="50"></textarea><br><br>
            <input type="submit" name="update_project2" value="Save Project 2">
        </form>
      </div>
      <div id="contacts">
        <h2>Contacts</h2>
        <form action="#" method="post" enctype="multipart/form-data">
            <label for="contact_image">Contact Image:</label><br>
            <input type="file" id="contact_image" name="contact_image"><br><br>
        </form>
      </div>
    </div>
  </div>

  <script>
    function showContent(id) {
      // Hide all content divs
      var contents = document.querySelectorAll('.adm-content > div');
      for (var i = 0; i < contents.length; i++) {
        contents[i].style.display = 'none';
      }
      // Show the selected content div
      document.getElementById(id).style.display = 'block';
    }

    // Automatically display the first tab
    document.addEventListener('DOMContentLoaded', function() {
      showContent('home');
    });
  </script>
</body>
</html>
