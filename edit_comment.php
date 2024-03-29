<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])){
    header("Location: login.php");
    exit();
}

if (isset($_POST["commentID"]) && isset($_POST["commentContent"])){
    $commentID = $_POST["commentID"];
    $commentContent = $_POST["commentContent"];

    $update_query = $mysqli->prepare("UPDATE comment SET Content = ? WHERE CommentID = ?");
    $update_query->bind_param("si", $commentContent, $commentID);

    if ($update_query->execute()) {
        echo json_encode(array("success" => true));
        exit();
    } else{
        echo json_encode(array("success"=> false, "message" => "Error: Unable to update this comment"));
    }

    $update_query->close();
    $mysqli->close();
} else {
    echo json_encode(array("success"=> false,"message"=> "Invalid request."));
}
?>