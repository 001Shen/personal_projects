<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "vonn_portfolio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch about data
$sql = "SELECT image_path, details FROM about WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch and format data
    $row = $result->fetch_assoc();
    $about = array(
        'image_path' => $row['image_path'],
        'details' => $row['details']
    );

    // Return data in JSON format
    echo json_encode($about);
} else {
    echo "No data found";
}

// Close database connection
$conn->close();
?>