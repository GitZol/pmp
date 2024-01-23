<?php
session_start();

if (!isset($_SESSION["Username"] )) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["projectID"])) {
    $projectID = $_POST["projectID"];
    $userID = $_SESSION["UserID"];

    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $checkPermissionStmt = $mysqli->prepare("SELECT UserID FROM project WHERE ProjectID = ? AND UserID = ?");
    $checkPermissionStmt->bind_param("ii", $projectID, $userID);
    $checkPermissionStmt->execute();
    $checkPermissionResult = $checkPermissionStmt->get_result();

    if ($checkPermissionResult->num_rows > 0) {

        $delete_query = $mysqli->prepare("DELETE FROM project WHERE projectID = ?");
        $delete_query->bind_param("i", $projectID);
    
        if( $delete_query->execute()) {
            header("HTTP/1.1 200 OK");
            exit();
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "Error: Unable to delete this project.";
        }
    
        $delete_query->close();
    } else {
        header("HTTP/1.1 403 Forbidden");
        echo "You do not have permission to delete this project.";
    }
    $checkPermissionResult->close();
    $mysqli->close();
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "Bad request.";
    exit();
}
?>