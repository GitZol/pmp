<?php
session_start();
$loggedIn = isset($_SESSION["UserID"]);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management Platform</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100" >
        <div class="position-fixed top-0 start-0">
            <h2 class="display-4 text-primary">Project Management Platform</h2>
        </div>

        <p class="lead text-muted mt-5">
            Effortlessly manage your projects from start to finish with our intuitive and comprehensive project management platform.
            Bring your team together, organize tasks, track progress, and achieve your goals with ease.
            Seamlessly collaborate with colleagues, share files, and communicate effectively, all from a single platform.
        </p>

        <div class="mt-3">
        <?php
            if ($loggedIn) {
                echo '
                <a href="home.php" class="btn btn-primary btn-lg">Get Started!</a> 
                ';
            } else {
                echo '
                <a href="login.php" class="btn btn-primary btn-lg">Login</a>
                <a href="register.php" class="btn btn-outline-primary btn-lg ms-3">Sign Up</a>
                ';
            }
        ?>
        </div> 
    </div>
</body>
</html>
