<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["taskID"]) && isset($_POST["comment"])){
    $taskID = $_POST["taskID"];
    $comment = $_POST["comment"];

    $stmt = $mysqli->prepare("INSERT INTO comment (Content, TaskID, UserID) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $comment, $taskID, $_SESSION["UserID"]);

    if ($stmt->execute()){
        $stmt->close();
        $mysqli->close();
        echo json_encode(["success" => true]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error adding comment: " . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
?>