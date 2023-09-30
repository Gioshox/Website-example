<?php
    session_start();
    
    // A basic check if $_SESSION['ID'] and $_SESSION['username'] are set to know if the user is signed in or not.
    function isUserSignedIn() {
        if(isset($_SESSION['ID'], $_SESSION['username'])) {
            return true;
        }else {
            return false;
        }
    }
?>