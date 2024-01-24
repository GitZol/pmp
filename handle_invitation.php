<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $invitationID = isset($_POST['invitation_id']) ? $_POST['invitation_id'] : null;

    if (isset($_POST['accept'])) {
        handleAccept($mysqli, $invitationID);
    } elseif (isset($_POST['decline'])) {
        handleDecline($mysqli, $invitationID);
    } else {
        echo "Invalid form submission";
    }
} else {
    echo "Invalid request method";
}

function handleAccept($mysqli, $invitationID) {
    $stmt = $mysqli->prepare("UPDATE invitation SET Status = 'accepted' WHERE InvitationID = ?");
    $stmt->bind_param("i", $invitationID);
    $stmt->execute();
    $stmt->close();

    $projectStmt = $mysqli->prepare("SELECT ProjectID FROM invitation WHERE InvitationID = ?");
    $projectStmt->bind_param("i", $invitationID);
    $projectStmt->execute();
    $projectID = null;
    $projectStmt->bind_result($projectID);

    if ($projectStmt->fetch()) {
        $projectStmt->close();

        $userProjectStmt = $mysqli->prepare("INSERT INTO user_project (UserID, ProjectID) VALUES (?, ?)");
        $userProjectStmt->bind_param("ii", $_SESSION["UserID"], $projectID);
        $userProjectStmt->execute();
        $userProjectStmt->close();

        $deleteStmt = $mysqli->prepare("DELETE FROM invitation WHERE InvitationID = ?");
        $deleteStmt->bind_param("i", $invitationID);
        $deleteStmt->execute();
        $deleteStmt->close();
    } else {
        echo "Error retrieving ProjectID from the invitation.";
    }
}

function handleDecline($mysqli, $invitationID) {
    $stmt = $mysqli->prepare("UPDATE invitation SET Status = 'declined' WHERE InvitationID = ?");
    $stmt->bind_param("i", $invitationID);
    $stmt->execute();
    $stmt->close();

    $deleteStmt = $mysqli->prepare("DELETE FROM invitation WHERE InvitationID = ?");
    $deleteStmt->bind_param("i", $invitationID);
    $deleteStmt->execute();
    $deleteStmt->close();
}

$mysqli->close();
?>