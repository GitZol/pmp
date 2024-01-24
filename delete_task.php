<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["Username"] )) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["taskID"])) {
    $taskID = $_POST["taskID"];

    $delete_query = $mysqli->prepare("DELETE FROM task WHERE TaskID = ?");
    $delete_query->bind_param("i", $taskID);

    if( $delete_query->execute() ) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error: Unable to delete this task.";
    }

    $delete_query->close();
    $mysqli->close();
} else {
    header("Location: home.php");
    exit();
}
?>