<?php
// Start a new session or resume the existing session
session_start();

// Include database connection
include_once '../db/db.php';

// Check if the 'username' and 'password' POST parameters are set
if (!isset($_POST['username'], $_POST['password'])) {
	exit('Fill in all the necessary input fields!');
}
// Check if the 'username' and 'password' POST parameters are not empty
if (empty($_POST['username']) || empty($_POST['password'])) {
	exit('Fill in all the necessary input fields!');
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare an SQL statement to retrieve user information by username
    $sql = "SELECT id, username, password, email, admin FROM accounts WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if a user with the given username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $passwordhash, $email, $isadmin);
        $stmt->fetch();

        // Verify if the provided password matches the stored password hash
        if (password_verify($password, $passwordhash)) {
            // Store user information in session variables
            $_SESSION["ID"] = $id;
            $_SESSION['username'] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["admin"] = ($isadmin == 1) ? true : false;

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

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
