<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "vonn_portfolio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch home
$sql = "SELECT header, text FROM home WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch and format data
    $row = $result->fetch_assoc();
    $home = array(
        'header' => $row['header'],
        'text' => $row['text']
    );

    // Return data in JSON format
    echo json_encode($home);
} else {
    echo "No data found";
}

// Close database connection
$conn->close();
?>
