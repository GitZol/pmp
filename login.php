<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <?php session_start();
        include 'navbar.php';
    ?>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    
        <div class="card border-1 p-4">
            <h2>Login</h2>
            <?php
            $hostname = "127.0.0.1";
            $username = "root";
            $password = "";
            $db_name = "project_management_platform";

            if (isset($_POST["login"])) {
                $mysqli = new mysqli($hostname, $username, $password, $db_name);

                if ($mysqli->connect_error) {
                    die("Connection failed: ". $mysqli->connect_error);
                }

                $stmt = $mysqli->prepare("SELECT UserID, password FROM user WHERE Username=?"
                );

            $stmt->bind_param("s", $username);

            $username = $_POST["username"];
            $password = $_POST["password"];
            $stmt->execute();
            $stmt->store_result();

            $user_retrieve_error = "That user was not found!";
            $password_retrieve_error = "That password may have been incorrect!";

            if($stmt->num_rows > 0) {
                $stmt->bind_result($UserID, $hashed_password);
                $stmt->fetch();

                if(password_verify($password, $hashed_password)) {
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["UserID"] = $UserID;
                    $_SESSION["Username"] = $username;

                    header("Location: home.php");
                    exit();
                } else {
                    echo '
                    <div class="alert alert-danger" role="alert">'
                        . $password_retrieve_error .
                    '</div>';
                }
            } else {
                echo '
                    <div class="alert alert-danger" role="alert">'
                        . $user_retrieve_error .
                    '</div>';
            }

            $stmt->close();
            $mysqli->close();
        }
        ?>

        <form class="login-form" action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="text-secondary" style="font-weight: 500;">Username:</label>
                <input id="username" name="username" required=""
                type="text" class="form-control"/>
            </div>
            <div class="mb-3">
                <label for="password" class="text-secondary" style="font-weight: 500;">Password:</label>
                <input id="password" name="password" required=""
                type="password" class="form-control" />
            </div>
            <input class="btn btn-primary" name="login" type="submit" value="Login"/>
        </form>
        <hr class="hr hr-blurry" />
        <p class="text-secondary"><a href="register.php" style="text-decoration: none; color: inherit;">New here?</a></p>
        </div>  
    </div>
    <script>
        includeHTML();
    </script>
</body>
</html>