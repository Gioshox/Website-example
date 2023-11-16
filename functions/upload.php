<?php
include_once '../db/db.php';
include_once 'functions.php';

// Check if the user is signed in; otherwise, redirect to the login page
if (!isUserSignedIn()) {
    header("Location: ../html/login.html");
    exit;
}

// Define the user ID and avatar path
$userid = $_SESSION['ID'];
$path = "../avatars/" . $userid . "/";

// Ensure the avatar directory exists; create it if not
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}

// Initialize the response array
$response = ['success' => false, 'error' => 'Unknown error'];

// Check if a file was uploaded
if (!isset($_FILES['avatar'])) {
    $response = ['success' => false, 'error' => 'No file uploaded'];
    sendResponse($response);
}

$file = $_FILES['avatar'];
$imageName = $file['name'];
$destination = $path . $imageName;

// Try to move the uploaded file to the destination
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $response = ['success' => false, 'error' => 'Failed to move the file'];
    sendResponse($response);
}

// File upload was successful; now update the avatar name in the database
$db = new Database();
$whereCondition = ['id' => $userid];

$db->update('accounts', ['avatar' => $imageName], $whereCondition);

if ($db->rowCount() > 0) {
    $response = ['success' => true, 'message' => 'File uploaded and database updated'];
} else {
    $response = ['success' => false, 'error' => 'Failed to update the database'];
}

// Send a JSON response
sendResponse($response);

// Function to send JSON response and terminate the script
function sendResponse($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
