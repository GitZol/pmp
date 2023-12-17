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

// File upload handling
if (isset($_FILES['updatePFP']) && $_FILES['updatePFP']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['updatePFP']['name'];
    $file_type = $_FILES['updatePFP']['type'];
    $file_tmp = $_FILES['updatePFP']['tmp_name'];

    $file_data = file_get_contents($file_tmp);

    // Update user profile picture information in the database
    $query = "UPDATE user SET ProfilePicture=?, ProfilePictureType=?, ProfilePictureData=? WHERE UserID=?";
    $stmt = $mysqli->prepare($query);

    $stmt->bind_param("ssbi", $ProfilePicture, $ProfilePictureType, $ProfilePictureData, $userID);
    $stmt->execute();
    $stmt->close();

    echo "File uploaded successfully.";
    header("Location: account.php");
    exit();
} else {
    echo "Error uploading file.";
}

$mysqli->close();
?>