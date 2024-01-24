<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}


$userID = $_SESSION["UserID"];

$delete_query = $mysqli->prepare("DELETE FROM user WHERE UserID = ?");
$img_delete = $mysqli->prepare("SELECT PFPName FROM user WHERE UserID = ?");

$delete_query->bind_param("i", $userID);
$img_delete->bind_param("i", $userID);

$img_delete->execute();
$result = $img_delete->get_result();
$row = $result->fetch_assoc();
if (!unlink('img/pfp/'. $row["PFPName"])) {
    echo "Error deleting user profile image";
}


if ($delete_query->execute()) {
    session_destroy(); 
    header("Location: login.php"); 
    exit();
} else {
    echo "Error: Unable to delete the user account.";
}

$delete_query->close();
$mysqli->close();
?>
