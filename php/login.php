<?php
// Start a new session or resume the existing session
session_start();

// Only accept AJAX requests
if( null !== ( null !== ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? null) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ) {

// Set the response header to JSON
header("Content-Type: application/json");
    
// Initialize the response array
$response = array();

// Include the Database class
include_once '../db/db.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response['error'] = 'Invalid request method!';
    echo json_encode($response);
    exit;
}

// Check if the 'username' and 'password' POST parameters are set
if (!isset($_POST['username'], $_POST['password'])) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Check if the 'username' and 'password' POST parameters are not empty
if (empty($_POST['username']) || empty($_POST['password'])) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Create a new Database object
$db = new Database();

// Sanitize the 'username' POST parameter
$username = strip_tags($_POST["username"]);
$password = strip_tags($_POST["password"]);

// Prepare an SQL statement to retrieve user information by username
$db->query("SELECT id, username, password, email, admin FROM accounts WHERE username=:username");
$db->bind(':username', $username);

// Execute the query and fetch the result as an associative array
$result = $db->single();

// Check if a user with the given username exists
if (!$result) {
    // Display an error message for an invalid username
    $response['error'] = 'Invalid username.';
    echo json_encode($response);
    exit;
}

// Verify if the provided password matches the stored password hash
if (!password_verify($password, $result['password'])) {
    // Display an error message for an invalid password
    $response['error'] = 'Invalid password.';
    echo json_encode($response);
    exit;
}

// Store user information in session variables
$_SESSION["ID"] = $result['id'];
$_SESSION['username'] = $result['username'];
$_SESSION["email"] = $result['email'];
$_SESSION["admin"] = ($result['admin'] == 1) ? true : false;

// Display a success message
$response['success'] = 'successfully logged in! You will be redirected to your profile page shortly.';
$response['redirect'] = '../php/profile.php';
echo json_encode($response);
exit;
} else {
    // Redirect to the login page if the request is not AJAX
    header("Location: ../html/login.html");
    exit;
}

?>