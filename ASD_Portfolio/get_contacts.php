<?php
// Establish database connection
$conn = new mysqli("localhost", "root", "", "vonn_portfolio");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$contact_image = ""; // Initialize the variable

// Query to retrieve contact_image from the contacts table
$sql = "SELECT contact_image FROM contacts WHERE id=1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the data
    $row = $result->fetch_assoc();
    $contact_image = $row['contact_image'];
} else {
    // Handle error if no rows found
}

// Close database connection if needed

// Return data as JSON
echo json_encode(array("contact_image" => $contact_image));
?>
