<?php

// Only accept AJAX requests
if( null !== ( null !== ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? null) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ) {

// Set the response header to JSON
header("Content-Type: application/json");
        
// Initialize the response array
$response = array();

// Include the Database class
include_once '../db/db.php';

// Check if all required fields are set in the POST request
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Check if any of the required fields are empty
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Validate the email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Validate the username using a regular expression (alphanumeric characters only)
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Validate the password length
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    $response['error'] = 'Fill in all the necessary input fields!';
    echo json_encode($response);
    exit;
}

// Create a new Database object
$db = new Database();

// Prepare a SQL statement to check if the username already exists
$db->query('SELECT id, password FROM accounts WHERE username = :username');
$db->bind(':username', strip_tags($_POST['username']));
$result = $db->single();

// Check if a user with the same username already exists
if ($result) {
    $response['error'] = 'Username exists. Please choose another!';
    echo json_encode($response);
    exit;
}

// Prepare a SQL statement to check if the email already exists
$db->query('SELECT id, password FROM accounts WHERE email = :email');
$db->bind(':email', strip_tags($_POST['email']));
$result = $db->single();

// Check if a user with the same username already exists
if ($result) {
    $response['error'] = 'Email already in use. Please choose another!';
    echo json_encode($response);
    exit;
}

// Prepare a SQL statement to insert a new user into the database
$db->query('INSERT INTO accounts (username, password, email, activation_code) VALUES (:username, :password, :email, :activation_code)');

// Hash the user's password before storing it
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$uniqid = uniqid();

// Bind the parameters to the SQL query above
$db->bind(':username', strip_tags($_POST['username']));
$db->bind(':password', strip_tags($password));
$db->bind(':email', strip_tags($_POST['email']));
$db->bind(':activation_code', strip_tags($uniqid));

// Execute the query
$db->execute();

// Configure email headers and content for account activation
// Note that this is vulnerable to email header injection which can be used to send spam to the user's email address, but in this example that is meant to run on localhost, it is not a problem.
$from    = 'noreply@yoursite.com';
$subject = 'Account Activation Required';
$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
// !!!! BEFORE USING READ THE WARNING ABOVE !!!!

// Generate an activation link with a unique code
$activate_link = 'https://yoursite.com/functions/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
$message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';

// Send an activation email to the user
// This will only work if the script is hosted on a live server and the SMTP configuration is correct. So don't worry if this doesn't work on your localhost.
// mail($_POST['email'], $subject, $message, $headers);

// Display a confirmation message to the user
$response['success'] = 'Your account has been created! Please verify your email by clicking the link in the verification email we have sent you.';
$response['redirect'] = '../html/login.html';
echo json_encode($response);
exit;
} else {
    // Redirect to the registration page if the request is not AJAX
    header("Location: ../html/register.html");
    exit;
}
?>