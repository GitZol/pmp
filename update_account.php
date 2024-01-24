<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
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

$row = $result->fetch_assoc();

$updateQuery = "UPDATE user SET ";
$updateParams = array();

if (!empty($_POST['username'])) {
    $newUsername = $_POST['username'];
    $updateQuery .= "Username = ?";
    $updateParams[] = $newUsername;
}

if (!empty($_POST['firstName'])) {
    $newFirstName = $_POST['firstName'];
    if (!empty($updateParams)) {
        $updateQuery .= ", ";
    }
    $updateQuery .= "FirstName = ?";
    $updateParams[] = $newFirstName;
}

if (!empty($_POST['lastName'])) {
    $newLastName = $_POST['lastName'];
    if (!empty($updateParams)) {
        $updateQuery .= ", ";
    }
    $updateQuery .= "LastName = ?";
    $updateParams[] = $newLastName;
}

if (!empty($_POST['email'])) {
    $newEmail = $_POST['email'];
    if (!empty($updateParams)) {
        $updateQuery .= ", ";
    }
    $updateQuery .= "Email = ?";
    $updateParams[] = $newEmail;
}

if (!empty($_POST['newPassword']) && !empty($_POST['confirmPassword']) && $_POST['newPassword'] === $_POST['confirmPassword']) {
    $newPassword = $_POST['newPassword'];
    if (!empty($updateParams)) {
        $updateQuery .= ", ";
    }
    $updateQuery .= "Password = ?";
    $updateParams[] = password_hash($newPassword, PASSWORD_DEFAULT);
}

$updateQuery .= " WHERE UserID = ?";
$updateParams[] = $userID;

$stmtUpdate = $mysqli->prepare($updateQuery);
if ($stmtUpdate) {
    $types = str_repeat('s', count($updateParams)); 
    $stmtUpdate->bind_param($types, ...$updateParams);
    $stmtUpdate->execute();

    if ($stmtUpdate->affected_rows > 0) {
        echo "Update successful";
        header("Location: account.php?message=update_successful");
        exit();
    } else {
        header('Location: account.php?message=no_changes');
    }
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
