<?php
session_start();
include 'db_connection.php'; // Ensure this file contains your database connection details

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    if (!$stmt->bind_param("s", $username)) {
        die("Bind param failed: " . htmlspecialchars($stmt->error));
    }

    // Execute the statement
    if (!$stmt->execute()) {
        die("Execute failed: " . htmlspecialchars($stmt->error));
    }

    // Bind result variables
    if (!$stmt->bind_result($password_hash)) {
        die("Bind result failed: " . htmlspecialchars($stmt->error));
    }

    // Fetch the results
    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $password_hash)) {
            // Store user information in session
            $_SESSION['username'] = $username;
            // Redirect to the portfolio page
            header("Location: admin.php");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title> 
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px; 
            box-sizing: border-box; 
        }

        form {
            width: 100%;
            max-width: 400px; 
            background-color: #ffffff;
            padding: 30px; 
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; 
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px; 
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; 
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required> 
            <br>
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me</label>
            <br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
