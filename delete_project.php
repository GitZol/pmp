<?php
session_start();

if (!isset($_SESSION["Username"] )) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["projectID"])) {
    $projectID = $_POST["projectID"];

    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $delete_query = $mysqli->prepare("DELETE FROM project WHERE projectID = ?");
    $delete_query->bind_param("i", $projectID);

    if( $delete_query->execute() ) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error: Unable to delete this project.";
    }

    $delete_query->close();
    $mysqli->close();
} else {
    header("Location: home.php");
    exit();
}
?>