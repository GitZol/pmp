<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senderUserID = $_SESSION['UserID'];
    $receiverUserID = $_POST['receiverUserID'];
    $projectID = $_POST['projectID'];

    $checkUserProjectStmt = $mysqli->prepare("SELECT * FROM user_project WHERE UserID = ? AND ProjectID = ?");
    $checkUserProjectStmt->bind_param("ii", $receiverUserID, $projectID);
    
    if ($checkUserProjectStmt->execute()) {
        $userProjectResult = $checkUserProjectStmt->get_result();

        if ($userProjectResult->num_rows > 0) {
            echo "User is already in the project";
            exit();
        }
    } else {
        echo "Error checling user project: " . $checkUserProjectStmt->error;
        exit();
    }
    $checkUserProjectStmt->close();

    $checkInvitationStmt = $mysqli->prepare("SELECT * FROM invitation WHERE SenderUserID = ? AND ReceiverUserID = ? AND ProjectID = ?");
    $checkInvitationStmt->bind_param("iii", $senderUserID, $receiverUserID, $projectID);

    if($checkInvitationStmt->execute()) {
        $invitationResult = $checkInvitationStmt->get_result();

        if ($invitationResult->num_rows > 0) {
            echo "Invitation already sent";
            exit();
        }
    } else {
        echo "Error checking invitation: " . $checkInvitationStmt->error;
        exit();
    }

    $checkInvitationStmt->close();
    $insertStmt = $mysqli->prepare("INSERT INTO invitation (SenderUserID, ReceiverUserID, ProjectID, Status) VALUES (?, ?, ?, 'pending')");
    $insertStmt->bind_param("iii", $senderUserID, $receiverUserID, $projectID);

    if ($insertStmt->execute()) {
        echo "Invitation sent successfully";
    } else {
        echo "Error sending invitation: " . $insertStmt->error;
    }

    $insertStmt->close();
    $mysqli->close();

} else {
    echo "Invalid request method";
}
?>