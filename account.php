<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>Accounts</title> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
  <div class="container">
    <?php
      session_start();

      if (!isset($_SESSION['UserID'])) {
        header("Location: login.php");
        exit();
      }

      $username = $_SESSION['UserID'];

      // Connect to the database
      $conn = new mysqli('localhost', 'root', '', 'project_management_platform');

      if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
      }

      // Retrieve the user's details from the database
      $sql = "SELECT username,FirstName,lastname, email FROM user WHERE UserID='$username'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set the initial values for the form fields
        $Username = $row['username'];
        $Email = $row['email'];
        $FirstName = $row ['FirstName'];
        $Lastname = $row['lastname'];
      } else {
        echo "Error retrieving user details.";
        exit;
      }

      // Close the database connection
      $conn->close();
    ?>
    <h3>User Details</h3>
    <ul>
      <h1><?php echo $Username; ?></h1>
      <li><?php echo $Email; ?></li>
      <li><?php echo $FirstName; ?></li>
      <li><?php echo $Lastname; ?></li>
    </ul>

    <button id="showEditAccountForm" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal">
      Edit Account
    </button>

    <!-- <a href="delete_user.php" role="button" class="btn btn-danger">Delete Account</button> -->

    <button id="deleteAccountButton" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>

    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">    
      <div class="modal-dialog">     
        <div class="modal-content">      
          <div class="modal-header">          
            <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>           
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>     
          </div>
          <form id="editAccountForm" action="update_account.php" method="post">
          <div class="modal-body">
            <label for="username">Username:</label>
            <input type="text" id="username" class="form-control" name="username" value="<?php echo $Username; ?>" >

            <label for="email">Email:</label>
            <input type="email" id="email" class="form-control" name="email" value="<?php echo $Email; ?>">

            <label for="FistName">First Name:</label>
            <input type="text" id="FirstName" class="form-control" name="FirstName" value="<?php echo $FirstName; ?>" >
            
            <label for="Lastname">LastName:</label>
            <input type="text" id="LastName" class="form-control" name="LastName" value="<?php echo $Lastname; ?>">

            <label for="oldPassword">Old Password:</label>
            <input type="password" id="oldPassword" class="form-control" name="oldPassword">

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" class="form-control" name="newPassword">

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" class="form-control" name="confirmPassword">
          </div>
          <div class="modal-footer"> 
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>   
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>