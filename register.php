<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
</head>
<body>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    <div class="card border-0 shadow-sm p-3">
            <h2>Register</h2>
            <?php
            $hostname = "127.0.0.1";
            $username = "root";
            $password = "";
            $db_name = "project_management_platform";

            if (isset($_POST["register"])) {
                $mysqli = new mysqli($hostname, $username, $password, $db_name);

                if ($mysqli->connect_error) {
                    die("Connection failed: ". $mysqli->connect_error);
                }

                $stmt = $mysqli->prepare(
                    "INSERT INTO user (Username, Email, Password,FirstName,LastName)
                    VALUES (?, ?, ?,?,?)"
                );

                $stmt->bind_param("sssss", $username, $email, $password,$FistName,$LastName,);

                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $FistName = $_POST["FirstName"];
                $LastName = $_POST["LastName"];

                $password = password_hash($password, PASSWORD_DEFAULT);

                if ($stmt->execute()) {
                    echo "New account created successfully!";
                    sleep(2);
                    header("Location: login.php");
                } else {
                    echo "Error: ". $stmt->error;
                }

                $stmt->close();
                $mysqli->close();
            }
            ?>

            <form action="register.php" method="post">
                <div class="mb-3">
                    <label for="FirstName">First name:</label>
                    <input id="FirstName" name="FirstName" required=""
                    type="text" class="form-control" placeholder="Enter your first name" />
                    <small id="FirstNameHelp" class="form-text text-muted">Choose a first name.</small>
                </div>
                <div class="mb-3">
                    <label for="LastName">Last name:</label>
                    <input id="LastName" name="LastName" required=""
                    type="text" class="form-control" placeholder="Enter your last name" />
                    <small id="LastNameHelp" class="form-text text-muted">Choose a last name.</small>
                </div>
                <div class="mb-3">
                    <label for="username">Username:</label>
                    <input id="username" name="username" required=""
                    type="text" class="form-control" placeholder="Enter your username" />
                    <small id="usernameHelp" class="form-text text-muted">Choose a username.</small>
                </div>
                <div class="mb-3">
                    <label for="email">Email:</label>
                    <input id="email" name="email" required=""
                    type="email" class="form-control" placeholder="Enter your email address"/>
                    <small id="emailHelp" class="form-text text-muted">Your email will not be shared with anyone else.</small>
                </div>
                <div class="mb-3">
                    <label for="password">Password:</label>
                    <input id="password" name="password" required=""
                    type="password" class="form-control" placeholder="Enter your password" />
                </div>
                <input name="register" type="submit"
                value="Register" class="btn btn-primary"/>
                <a href="login.php">already have an account?</a>
            </form> 
        </div>   
</body>
</html>