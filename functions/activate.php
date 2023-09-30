<?php
// Include the database connection file
include_once '../db/db.php';

// Check if email and code parameters are set in the URL
if (isset($_GET['email'], $_GET['code'])) {
    // Prepare and execute the SQL query
    if ($stmt = $conn->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
        $stmt->bind_param('ss', $_GET['email'], $_GET['code']);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if a row with the specified email and code exists
        if ($stmt->num_rows > 0) {
            // Prepare and execute an SQL query to update the activation code
            if ($stmt = $conn->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
                $newcode = 'activated';
                $stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
                $stmt->execute();
                echo 'Your account is now activated! You can now <a href="../html/login.html">login</a>!';
            }
        } else {
            echo 'The account is already activated or doesn\'t exist!';
        }
    }
}
?>
