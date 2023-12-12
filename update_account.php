<?php
$conn = new mysqli('localhost', 'root', '', 'project_management_platform');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['UserID'];
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    $email = '';
}

$sess = $userID;
$result = $conn->query("SELECT Username, password, FirstName, LastName, Email FROM user WHERE userID='$userID'");
$row = $result->fetch_assoc();
$username = $row['Username'];
$email = $row['Email'];
$firstname = $row ['FirstName'];
$lastname = $row['LastName'];
$password = $row['password'];

$editUserName = $_POST['username'];
$editFirstName = $_POST['FirstName'];
$editLastName = $_POST['LastName'];
$editEmail = $_POST['email'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

// Check if any of the user's details have been changed
$fieldsChanged = false;
if($username != $editUserName){
    $fieldsChanged = true;
} elseif ($firstname != $editFirstName) {
    $fieldsChanged = true;
} else if ($lastname != $editLastName) {
    $fieldsChanged = true;
} else if ($email != $editEmail) {
    $fieldsChanged = true;
} else if ($newPassword != $password && $newPassword == $confirmPassword) {
    $fieldsChanged = true;
}

// Only update the database if at least one field was changed
if ($fieldsChanged) {
    // Update the user's details in the database
    if ($fieldsChanged === true) {
        $sql = "UPDATE user SET firstname='$editFirstName', lastname='$editLastName', email='$editEmail', username='$editUserName' WHERE userID='$userID'";
    } else if ($newPassword != '') {
        $sql = "UPDATE user SET password='$newPassword' WHERE userID='$userID'";
    }

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        if ($fieldsChanged === true) {
            echo "User details updated successfully.";
        } else if ($newPassword != '') {
            echo "Password updated successfully.";
        }
    } else {
        echo "Error updating user details: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    if ($newPassword == '') {
        echo "No changes made to user details.";
    } else {
        echo "Password change unsuccessful. Please check that all fields are filled out correctly.";
    }
}
?>
