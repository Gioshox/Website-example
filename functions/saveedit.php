<?php
// Include database connection and functions
include_once '../db/db.php';
include_once 'functions.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "form" parameter is set in the POST data
    if (isset($_POST["form"])) {
        $userid = $_SESSION['ID'];
        $newUsername = $_POST['username'];

        // Check if the new username is not empty
        if (!empty($newUsername)) {
            // Check if the new username is different from the current username
            if ($newUsername != $_SESSION['username']) {
                // Prepare an SQL statement to update the username
                $db = new Database();

                $whereCondition = ['id' => $userid];
                
                $db->update('accounts', ['username' => $newUsername], $whereCondition);
                
                // Check if the SQL statement was prepared successfully
                if ($db->rowCount() > 0) {
                        // Update the session variable with the new username
                        $_SESSION['username'] = $newUsername;
                        
                        // Redirect to the profile page after updating
                        header("refresh:0;url=../php/profile.php");
                    } else {
                        echo "Error updating record: " . $error;
                    }
                } else {
                    echo "Error: " . $error;
                }
            } else {
                echo "No changes were made. Redirecting in 2 seconds.";
                
                // Redirect to the profile page after 2 seconds
                header("refresh:2;url=../php/profile.php");
            }
        }
    }
?>
