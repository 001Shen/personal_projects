<?php
// Database connection parameters
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "vonn_portfolio";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all users with their plain-text passwords
$query = "SELECT id, username, password_hash FROM users";
$result = $conn->query($query);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    // Loop through each user
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $plainPassword = $row['password_hash'];

        // Check if the password is already hashed
        if (password_needs_rehash($plainPassword, PASSWORD_DEFAULT)) {
            // Hash the plain-text password
            $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

            // Update the database with the hashed password
            $updateQuery = "UPDATE users SET password_hash = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);

            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("si", $hashedPassword, $id);

            if (!$stmt->execute()) {
                die("Error executing statement: " . $stmt->error);
            }
        }
    }

    echo "Passwords hashed successfully!";
} else {
    echo "No users found.";
}

// Close connection
$conn->close();
?>
