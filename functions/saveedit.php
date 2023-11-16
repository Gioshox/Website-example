<?php
// Include database connection and functions
include_once '../db/db.php';
include_once 'functions.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["form"])) {
    exit('Invalid request');
}

$userid = $_SESSION['ID'];
$newUsername = $_POST['username'];

// Check if the new username is empty
if (empty($newUsername)) {
    exit('New username is empty');
}

// Check if the new username is the same as the current username
if ($newUsername == $_SESSION['username']) {
    echo "No changes were made. Redirecting in 2 seconds.";
    
    // Redirect to the profile page after 2 seconds
    header("refresh:2;url=../php/profile.php");
    exit;
}

// Prepare an SQL statement to update the username
$db = new Database();
$whereCondition = ['id' => $userid];

$db->update('accounts', ['username' => $newUsername], $whereCondition);

// Check if the SQL statement was prepared successfully
if ($db->rowCount() > 0) {
    // Update the session variable with the new username
    $_SESSION['username'] = $newUsername;
    
    // Redirect to the profile page after updating
    echo "Successfully updated username. Redirecting...";
    header("refresh:2;url=../php/profile.php");
} else {
    echo "Error updating record.";
}

?>
