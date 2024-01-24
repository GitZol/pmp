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
            $error_message = "User is already in the project";
        }
    } else {
        $error_message = "Error checling user project: " . $checkUserProjectStmt->error;
    }
    $checkUserProjectStmt->close();

    $checkInvitationStmt = $mysqli->prepare("SELECT * FROM invitation WHERE SenderUserID = ? AND ReceiverUserID = ? AND ProjectID = ?");
    $checkInvitationStmt->bind_param("iii", $senderUserID, $receiverUserID, $projectID);

    if($checkInvitationStmt->execute()) {
        $invitationResult = $checkInvitationStmt->get_result();

        if ($invitationResult->num_rows > 0) {
            $error_message = "Invitation already sent";
        }
    } else {
        $error_message = "Error checking invitation: " . $checkInvitationStmt->error;
    }

    $checkInvitationStmt->close();

    if (!isset($error_message)) {
        $insertStmt = $mysqli->prepare("INSERT INTO invitation (SenderUserID, ReceiverUserID, ProjectID, Status) VALUES (?, ?, ?, 'pending')");
        $insertStmt->bind_param("iii", $senderUserID, $receiverUserID, $projectID);
    
        if ($insertStmt->execute()) {
            $success_message = "Invitation sent successfully";
        } else {
            $error_message = "Error sending invitation: " . $insertStmt->error;
        }
    
        $insertStmt->close();
    }

    $mysqli->close();

    if (isset($error_message)) {
        echo $error_message;
    } elseif (isset($success_message)) {
        echo $success_message;
    } else {
        echo "Unexpected error";
    }

} else {
    echo "Invalid request method";
}
?>