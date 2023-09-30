<?php
// Include the database connection file
include_once '../db/db.php';

// Check if all required fields are set in the POST request
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	exit('Please complete the registration form!');
}

// Check if any of the required fields are empty
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	exit('Please complete the registration form');
}

// Validate the email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

// Validate the username using a regular expression (alphanumeric characters only)
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

// Validate the password length
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

// Prepare a SQL statement to check if the username already exists
if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

	// Check if a user with the same username already exists
	if ($stmt->num_rows > 0) {
		echo 'Username exists, please choose another!';
        header( "refresh:3;url=signup.php" );
	} else {
		// Prepare a SQL statement to insert a new user into the database
		if ($stmt = $conn->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
            // Hash the user's password before storing it
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $uniqid = uniqid();
            $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
            $stmt->execute();

            // Configure email headers and content for account activation
            $from    = 'noreply@yoursite.com';
            $subject = 'Account Activation Required';
            $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
            
            // Generate an activation link with a unique code
            $activate_link = 'https://yoursite.com/functions/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
            $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';

            // Send an activation email to the user
            mail($_POST['email'], $subject, $message, $headers);

            echo 'Please check your email to activate your account!';
            header( "refresh:3;url=../html/login.html" );
        } else {
            echo 'Could not prepare statement!';
        }
	}
	$stmt->close();
} else {
	echo 'Could not prepare statement!';
}

// Close the database connection
$conn->close();
?>
