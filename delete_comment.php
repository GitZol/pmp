<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])){
    header("Location: login.php");
    exit();
}

if (isset($_POST["commentID"])) {
    $commentID = $_POST["commentID"];

    $delete_query = $mysqli->prepare("DELETE FROM comment WHERE CommentID = ?");
    $delete_query->bind_param("i", $commentID);

    if ($delete_query->execute()) {
        echo json_encode(array("success" => true));
        exit();
    } else{
        echo json_encode(array("success"=> false, "message" => "Error: Unable to delete this comment"));
    }

    $delete_query->close();
    $mysqli->close();
} else {
    echo json_encode(array("success"=> false,"message"=> "Invalid request."));
}
?>