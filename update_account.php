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
        header("Location: account.php");
        exit();
    } else {
        echo "Update failed";
    }
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>
