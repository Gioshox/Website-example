<?php
// Include the database connection file
include_once '../db/db.php';

// Check if email and code parameters are set in the URL
if (isset($_GET['email'], $_GET['code'])) {
    $email = $_GET['email'];
    // Prepare and execute the SQL query
    $db = new Database();

    $db->query("SELECT * FROM accounts WHERE email=:email");
    $db->bind(':email', $email);
    $result = $db->single();
    
    if ($result) {
        $code = $result['activation_code'];

        // Check if a row with the specified email and code exists
        if ($code === $_GET['code']) {

            // Prepare and execute an SQL query to update the activation code
            $whereCondition = ['email' => $email];
        
            $db->update('accounts', ['activation_code' => "activated"], $whereCondition);
            if ($db->rowCount() > 0) {
                echo 'Your account is now activated! You can now <a href="../html/login.html">login</a>!';
            }
        } else {
            echo 'The account is already activated or doesn\'t exist!';
        }
    }
}
?>
