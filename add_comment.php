<?php
session_start();

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["taskID"]) && isset($_POST["comment"])){
    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $taskID = $_POST["taskID"];
    $comment = $_POST["comment"];
    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: ". $mysqli->connect_error);
    }

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