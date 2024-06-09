<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "vonn_portfolio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch projects
$sql = "SELECT description, image_path, project_link FROM projects";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    $projects = array();
    // Fetch and format data
    while ($row = $result->fetch_assoc()) {
        $project = array(
            'description' => $row['description'],
            'image_path' => $row['image_path'],
            'project_link' => $row['project_link']
        );
        $projects[] = $project;
    }

    // Return data in JSON format
    echo json_encode($projects);
} else {
    echo json_encode(array()); // Return an empty array if no projects are found
}

// Close database connection
$conn->close();
?>
