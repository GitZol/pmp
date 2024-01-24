<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    <div class="card border-1 p-4">
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
                    "INSERT INTO user (Username, Email, Password, FirstName, LastName, PFPName, PFPNameOriginal, PFPSize, PFPType)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );

                $stmt->bind_param("sssssssss", $username, $email, $password, $firstName, $lastName, $uniqueFileName, $fileName, $fileSize, $fileExtension);

                $username = $_POST["username"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];

                $password = password_hash($password, PASSWORD_DEFAULT);
                
                // Code for profile picture upload starts

                 $file = $_FILES['uploadPFP'];
                 $fileName = $file['name'];
                 $fileTmpName = $file['tmp_name'];
                 $targetDir = 'img/pfp/';

                if (empty($fileName)) {
                    $uniqueFileName = "user-circle.256x256.png";
                    $targetFile = $targetDir . $uniqueFileName;
                    $fileSize = 0; 
                    $fileExtension = 'png'; 
                 } else {
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                    $fileSize = $file['size'];
                    if (!exif_imagetype($fileTmpName)) {
                        echo "Invalid image file.";
                        exit;
                            }

                    if ($fileSize > 1024000) {
                    echo "File size exceeds maximum limit of 1MB.";
                    exit;
                            } 

                $uniqueFileName = uniqid() . '.' . $fileExtension;
                $targetFile = $targetDir . $uniqueFileName; // Set target file for uploaded image

                if (!move_uploaded_file($fileTmpName, $targetFile)) {
                    echo "Error uploading file.";
                    exit;
        }
 }
 // Code for profile picture upload ends

 $stmt = $mysqli->prepare(
     "INSERT INTO user (Username, Email, Password, FirstName, LastName, PFPName, PFPNameOriginal, PFPSize, PFPType)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
 );

 $username = $_POST["username"];
 $email = $_POST["email"];
 $password = $_POST["password"];
 $firstName = $_POST["firstName"];
 $lastName = $_POST["lastName"];
 $password = password_hash($password, PASSWORD_DEFAULT);

 $stmt->bind_param("sssssssss", $username, $email, $password, $firstName, $lastName, $uniqueFileName, $fileName, $fileSize, $fileExtension);

 if ($stmt->execute()) {
     echo "New account created successfully!";
     header("Location: login.php");
     exit();
 } else {
     echo "Error: " . $stmt->error;
 }

 $stmt->close();
 $mysqli->close();
}
?>

            <form action="register.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="FirstName" class="text-secondary" style="font-weight: 500;">First name:</label>
                    <input id="firstName" name="firstName" required=""
                    type="text" class="form-control" placeholder="Enter your first name" />
                    <small id="FirstNameHelp" class="form-text text-muted">What's your first name?</small>
                </div>
                <div class="mb-3">
                    <label for="LastName" class="text-secondary" style="font-weight: 500;">Last name:</label>
                    <input id="lastName" name="lastName" required=""
                    type="text" class="form-control" placeholder="Enter your last name" />
                    <small id="LastNameHelp" class="form-text text-muted">What's your last name?</small>
                </div>
                <div class="mb-3">
                    <label for="username" class="text-secondary" style="font-weight: 500;">Username:</label>
                    <input id="username" name="username" required=""
                    type="text" class="form-control" placeholder="Enter your username" />
                    <small id="usernameHelp" class="form-text text-muted">Pick a username (You can change this later)</small>
                </div>
                <!-- Profile Picture Uploading Bit -->
                <div class="mb-3">
                    <label for="uploadPFP" class="text-secondary" style="font-weight: 500;">Profile Picture:</label>
                    <input type="file" class="form-control" name="uploadPFP" value=""/>
                    <small id="pfpHelp" class="form-text text-muted">Size must be less than 1 MB</small>
                </div>
                <div class="mb-3">
                    <label for="email" class="text-secondary" style="font-weight: 500;">Email:</label>
                    <input id="email" name="email" required=""
                    type="email" class="form-control" placeholder="Enter your email address"/>
                    <small id="emailHelp" class="form-text text-muted">What's your email?</small>
                </div>
                <div class="mb-3">
                    <label for="password" class="text-secondary" style="font-weight: 500;">Password:</label>
                    <input id="password" name="password" required=""
                    type="password" class="form-control" placeholder="Enter your password" />
                    <small id="passwordHelp" class="form-text text-muted">Should be super secure!</small>
                </div>
                <input name="register" type="submit"
                value="Register" class="btn btn-primary"/>
                </form>
            <hr class="hr hr-blurry" />
            <p class="text-secondary"><a href="login.php" style="text-decoration: none; color: inherit;">Already have an account?</a></p>
        </div>
        <script>
        includeHTML();
        </script>  
</body>
</html>