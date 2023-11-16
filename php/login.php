<?php
// Start a new session or resume the existing session
session_start();

// Include the Database class
include_once '../db/db.php';

// Check if the 'username' and 'password' POST parameters are set
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Fill in all the necessary input fields!');
}

// Check if the 'username' and 'password' POST parameters are not empty
if (empty($_POST['username']) || empty($_POST['password'])) {
    exit('Fill in all the necessary input fields!');
}

$db = new Database();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare an SQL statement to retrieve user information by username
    $db->query("SELECT id, username, password, email, admin FROM accounts WHERE username=:username");
    $db->bind(':username', $username);

    // Execute the query and fetch the result as an associative array
    $result = $db->single();

    // Check if a user with the given username exists
    if ($result) {
        // Verify if the provided password matches the stored password hash
        if (password_verify($password, $result['password'])) {
            // Store user information in session variables
            $_SESSION["ID"] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION["email"] = $result['email'];
            $_SESSION["admin"] = ($result['admin'] == 1) ? true : false;

            // Display a success message and redirect to the profile page
            echo 'Successfully signed in.';
            header("refresh:3;url=profile.php");
        } else {
            // Display an error message for an invalid password and redirect to the login page
            echo 'Invalid password.';
            header("refresh:3;url=../html/login.html");
        }
    } else {
        // Display an error message for an invalid username and redirect to the login page
        echo "Invalid username.";
        header("refresh:3;url=../html/login.html");
    }
}

?>