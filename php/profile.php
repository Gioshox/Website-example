<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaHook</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f24b0c19a4.js" crossorigin="anonymous"></script>
    <link href="../css/profile.css" rel="stylesheet">
</head>
<body>
<?php
// Include database connection and functions
include_once '../db/db.php';
include_once '../functions/functions.php';

// Retrieve user information from the session
$userid = $_SESSION['ID'];
$username = $_SESSION['username'];

// Prepare SQL statement to retrieve user's avatar
$sql = "SELECT avatar FROM accounts WHERE id=?";
$stmt = $conn->prepare($sql);

// Check if the SQL statement was prepared successfully
if ($stmt) {
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($avatar);
    $stmt->fetch();
    $stmt->close();
} else {
    // Display an error message if SQL statement preparation fails
    echo "Error in preparing the SQL statement: " . $conn->error;
    exit;
}

// Close the database connection
$conn->close();
?>

<div class="container">
    <div class="main-body">
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
        </nav>
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <img <?php $end = '"'; echo 'src="../avatars/' . $userid . "/" . $avatar . $end . ''; ?> alt="Avatar">
                            <div class="mt-3">
                                <h4><?php echo $_SESSION['username']; ?></h4>
                                <p class="text-secondary mb-1"><?php if($_SESSION['admin'] == false) {echo "user";}else{echo "Administrator";} ?></p>
                                <button id="upload-btn" class="btn btn-primary">Upload avatar</button>
                                <input type="file" id="file-input" style="display: none;">
                                <script>
                                    document.getElementById('upload-btn').addEventListener('click', function () {
                                        document.getElementById('file-input').click();
                                    });

                                    document.getElementById('file-input').addEventListener('change', function (e) {
                                        // Get the selected file
                                        const file = e.target.files[0];

                                        if (file) {
                                            // Use AJAX to upload the file to the server using PHP
                                            const formData = new FormData();
                                            formData.append('avatar', file);

                                            // Send the file to the server
                                            fetch('../functions/upload.php', {
                                                method: 'POST',
                                                body: formData
                                            })
                                                .then(response => response.json())
                                                .then(data => {
                                                    // Handle the response from the server
                                                    if (data.success) {
                                                        // Success: Display a success message and refresh the page
                                                        console.log('Image uploaded:', data.message);
                                                        window.location.reload();
                                                    } else {
                                                        // Error: Display an error message
                                                        console.error('Error:', data.error);
                                                    }
                                                })
                                                .catch(error => {
                                                    // Network or other errors
                                                    console.error('Error:', error);
                                                });
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- User profile details -->
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Username</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $_SESSION['username']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $_SESSION['email']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Password</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                ••••••••••
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Edit and logout buttons -->
                                <a class="btn btn-primary" target="__blank" href="../functions/edit.php?id=<?php echo $username; ?>">Edit</a>
                                <a class="btn btn-primary" target="__blank" href="../functions/logout.php">Log out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
