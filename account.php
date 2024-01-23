<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <style>
        #updateForm {
            display: none;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-container .btn {
            flex: 1;
            margin: 0 5px;
        }
        .center {
            position: absolute;
            left:50%;
            transform: translateX(-50%);
            -webkit-transform: translateX(-50%);
        }
    </style>

</head>
<body>
<?php session_start(); include 'navbar.php'; ?>

<div class="container d-flex flex-column align-items-center justify-content-center vh-100">
    <h4>Account Details</h4>
    <div class="card border-1 p-4" style="width: 22rem;">    
        <?php
        if (!isset($_SESSION["UserID"])) {
            header("Location: login.php");
            exit();
        }
        $hostname = "127.0.0.1";
        $username = "root";
        $password = "";
        $db_name = "project_management_platform";

        $mysqli = new mysqli($hostname, $username, $password, $db_name);
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $userID = $_SESSION["UserID"]; 
        $query = "SELECT * FROM user WHERE UserID = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            echo "Error: " . $mysqli->error;
            exit();
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<img class='border border-1 rounded-circle center' height='100' width='100' style='object-fit: cover;' src='img/pfp/" . $row["PFPName"] . "' alt='Image'>";
        } else {
            echo "User not found.";
        }

        $mysqli->close();
        

        ?>
        
        <button onclick="togglePFP()" class="text-decoration-none btn btn-outline-secondary col-3 center" style="--bs-btn-padding-y: .12rem; --bs-btn-padding-x: .3rem; --bs-btn-font-size: .80rem; margin-top: 110px;">Change
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-pencil" viewBox="-2 1 18 18">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
        </svg>
        </button>
        
        <hr class="hr hr-blurry" style="margin-top:50%;"/>

<div id="uploadPFP" style="display: none;">
    <form action="uploadpfp.php" method="POST" enctype="multipart/form-data" class="mb-2">
        <div class="mb-2">
            <input type="file" class="form-control" name="uploadPFP">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-outline-primary flex-grow-1 me-1">
                Upload
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-floppy2-fill" viewBox="-4 0 20 20">
                    <path d="M12 2h-2v3h2z"/>
                    <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5V2.914a1.5 1.5 0 0 0-.44-1.06L14.147.439A1.5 1.5 0 0 0 13.086 0zM4 6a1 1 0 0 1-1-1V1h10v4a1 1 0 0 1-1 1zM3 9h10a1 1 0 0 1 1 1v5H2v-5a1 1 0 0 1 1-1"/>
                </svg>
            </button>
            <button type="button" class="btn btn-outline-danger flex-grow-1 me-1" onclick="location.href='defaultpfp.php'">
                Remove Picture
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                        <line x1="1" y1="1" x2="11" y2="11" stroke="currentColor" stroke-width="2"/>
                        <line x1="11" y1="1" x2="1" y2="11" stroke="currentColor" stroke-width="2"/>
                </svg>
            </button>
        </div>
    </form>
</div>



        <hr class="hr hr-blurry" style="display: none;" id="pfpFormBar"/>

        <form action="update_account.php" method="post">
            <div class="mb-3">
                <label for="firstName" class="text-secondary" style="font-weight: 500;">First name:</label>
                <input id="firstName" name="firstName" type="text" class="form-control" value="<?php echo $row["FirstName"]; ?>" disabled="disabled"/>
            </div>
            <div class="mb-3">
                <label for="lastName" class="text-secondary" style="font-weight: 500;">Last name:</label>
                <input id="lastName" name="lastName" type="text" class="form-control" value="<?php echo $row["LastName"]; ?>" disabled="disabled"/>
            </div>
            <div class="mb-3">
                <label for="username" class="text-secondary" style="font-weight: 500;">Username:</label>
                <input id="username" name="username" type="text" class="form-control" value="<?php echo $row["Username"]; ?>" disabled="disabled"/>
            </div>
            <div class="mb-3">
                <label for="email" class="text-secondary" style="font-weight: 500;">Email:</label>
                <input id="email" name="email" type="text" class="form-control" value="<?php echo $row["Email"]; ?>" disabled="disabled"/>
            </div>

            <div id="passwordForm" style="display: none;">
                <div class="mb-3">
                    <label for="oldPassword" class="text-secondary" style="font-weight: 500;">Old Password:</label>
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword">
                </div>

                <div class="mb-3">
                    <label for="newPassword" class="text-secondary" style="font-weight: 500;">New Password:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword">
                </div>

                <div class="mb-3">
                    <label for="confirmPassword" class="text-secondary" style="font-weight: 500;">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <button type="submit" name="UpdateDetails" class="text-decoration-none btn btn-outline-primary col-3" style="--bs-btn-padding-y: .12rem; --bs-btn-padding-x: .3rem; --bs-btn-font-size: .75rem; margin-bottom: 20px;">Save
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-floppy2-fill" viewBox="-4 0 20 20">
                    <path d="M12 2h-2v3h2z"/>
                    <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5V2.914a1.5 1.5 0 0 0-.44-1.06L14.147.439A1.5 1.5 0 0 0 13.086 0zM4 6a1 1 0 0 1-1-1V1h10v4a1 1 0 0 1-1 1zM3 9h10a1 1 0 0 1 1 1v5H2v-5a1 1 0 0 1 1-1"/>
                    </svg>
                    </button>
                </div>
            </div>
        </form>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <button onclick="toggleForm()" class="text-decoration-none btn btn-outline-secondary col-3" style="--bs-btn-padding-y: .12rem; --bs-btn-padding-x: .3rem; --bs-btn-font-size: .80rem;">Update
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-pencil" viewBox="-2 1 18 18">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
            </button>
        </div>
        <hr class="hr hr-blurry"/>
         
        <form class="d-flex flex-column align-items-center justify-content-center" id="deleteForm" action="delete_account.php" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <button type="submit" class="text-decoration-none btn btn btn-outline-danger col-5" style="--bs-btn-padding-y: .12rem; --bs-btn-padding-x: .3rem; --bs-btn-font-size: .80rem;">Delete Account
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-trash-fill" viewBox="0 1 18 18">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
            </svg>
            </button>
        </form>
        <?php
    if (isset($_GET['message']) && $_GET['message'] == 'no_changes') {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert">
                No changes made
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if((isset($_GET['message']) && $_GET['message'] == 'update_successful')){
        echo '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                Account Updated Successfully
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
?>

    </div>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById("passwordForm");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
            document.getElementById("firstName").disabled = false;
            document.getElementById("lastName").disabled = false;
            document.getElementById("username").disabled = false;
            document.getElementById("email").disabled = false;
        } else {
            form.style.display = "none";
            document.getElementById("firstName").disabled = true;
            document.getElementById("lastName").disabled = true;
            document.getElementById("username").disabled = true;
            document.getElementById("email").disabled = true;
        }
    }
    function togglePFP() {
        var form = document.getElementById("uploadPFP");
        var bar = document.getElementById("pfpFormBar");
        if ((form.style.display === "none" || form.style.display === "") && (bar.style.display === "none" || bar.style.display === "")) {
            form.style.display = "block";
            bar.style.display = "block";
        } else {
            form.style.display = "none";
            bar.style.display = "none";
        }
    }
</script>
</body>
</html>