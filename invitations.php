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
$stmt = $mysqli->prepare("SELECT i.InvitationID, u.Username AS SenderUserName, p.ProjectName, i.Status
    FROM invitation i
    INNER JOIN user u ON i.SenderUserID = u.userID
    INNER JOIN project p ON i.ProjectID = p.ProjectID
    WHERE i.ReceiverUserID = ? AND i.Status = 'pending'");

$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$invitations = [];
while ($row = $result->fetch_assoc()) {
    $invitations[] = $row;
}

$stmt->close();
$mysqli->close();
?>