<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <!-- <script src="home.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
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
    </style>
</head>
<body>
<div class="container">
        <h2>User Account Details</h2>
        <div class="card">
            <?php
            session_start();
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
                echo "<p>Username: " . $row["Username"] . "</p>";
                echo "<p>Email: " . $row["Email"] . "</p>";
                echo "<p>First Name: " . $row["FirstName"] . "</p>";
                echo "<p>Last Name: " . $row["LastName"] . "</p>";
                
            } else {
                echo "User not found.";
            }

            $mysqli->close();
            ?>
            <div class="btn-container">
                <a href="home.php" class="btn btn-primary">Go back to Home</a>

                <button class="btn btn-primary" onclick="toggleUpdateForm()">Update Account</button>
                
                <form id="deleteForm" action="delete_account.php" method="post" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <input type="submit" class="btn btn-danger" value="Delete Account">
                </form>
            </div>

            <div id="updateForm" style="display: none;">
                <form action="update_account.php" method="post">
                    <div class="mb-3">
                        <label for="updateUsername" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="updateUsername" name="updateUsername" value="<?php echo $row["Username"]; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="updateFirstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="updateFirstName" name="updateFirstName" value="<?php echo $row["FirstName"]; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="updateLastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="updateLastName" name="updateLastName" value="<?php echo $row["LastName"]; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="updateEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="updateEmail" name="updateEmail" value="<?php echo $row["Email"]; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="oldPassword" class="form-label">Old Password:</label>
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword">
                    </div>

                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password:</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                    </div>

                    <input type="submit" name="updateDetails" class="btn btn-primary" value="Update">
                </form>
            </div>
        </div>
    </div>
<script>
    function toggleUpdateForm() {
        var form = document.getElementById("updateForm");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
</script>
</body>
</html>