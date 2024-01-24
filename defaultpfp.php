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

// Default image details
$defaultImageName = "user-circle.256x256.png";
$defaultImageSize = 0; 
$defaultImageType = "png"; 

// Update user's profile picture in the database
$sql = "UPDATE user SET PFPName = ?, PFPNameOriginal = ?, PFPSize = ?, PFPType = ? WHERE UserID = ?";
$stmt = mysqli_prepare($mysqli, $sql);
$stmt->bind_param('ssssi', $defaultImageName, $defaultImageName, $defaultImageSize, $defaultImageType, $userID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
   
    header("Location: account.php?message=update_successful");
} else {

    header("Location: account.php?message=no_changes");
}

$stmt->close();
mysqli_close($mysqli);
?>