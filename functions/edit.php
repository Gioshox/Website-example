<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaHook</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <!-- Include Bootstrap JavaScript with dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Include FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/f24b0c19a4.js" crossorigin="anonymous"></script>

    <!-- Include custom CSS -->
    <link href="../css/edit.css" rel="stylesheet">
</head>
<body class="text-center">
    <?php
    // Include database connection and functions
    include_once '../db/db.php';
    include_once 'functions.php';

    // Check if the user is signed in; otherwise, redirect to the login page
    if (!isUserSignedIn()) {
        header("Location: login.php");
        exit;
    }

    // Check if 'id' parameter is set in the URL and validate it
    if (isset($_GET['id'])) {
        // Check if the 'id' parameter matches the currently signed-in user's username
        if ($_GET['id'] != $_SESSION['username']) {
            header("Location: ../php/profile.php");
        }
    }
    ?>

    <!-- Main content of the page -->
    <main class="form-signin">
    <form action="saveedit.php" method="post" autocomplete="off">
        <img class="mb-4" src="../img/logo.png" alt="NovaHook" width="477" height="55">
        <h1 class="h3 mb-3 fw-normal">Edit profile</h1>

        <div class="form-floating">
        <input autocomplete="off" name="username" type="text" class="form-control" id="floatingInput" value="<?php echo $_SESSION['username']; ?>">
        <label for="floatingInput">Username</label>
        </div><br>
        <button class="w-100 btn btn-lg btn-primary" name="form" type="submit">Save changes</button>
    </form>
    </main>    
</body>
</html>
