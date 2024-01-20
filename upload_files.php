<?php
session_start();

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["taskID"])) {
    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $taskID = $_POST["taskID"];

    $uploadDirectory = "uploads/";

    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $uploadedFiles = [];

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT ProjectID from task WHERE TaskID = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $stmt->bind_result($projectID);
    $stmt->fetch();
    $stmt->close();

    if (!$projectID) {
        echo json_encode(["success" => false, "message" => "Project not found for the given task"]);
        exit();
    }

    foreach ($_FILES['files']['name'] as $key => $value) {
        
        $fileName = basename($_FILES['files']['name'][$key]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueFileName = $fileName . '_' . uniqid() . '.' . $fileExtension;
        $targetFilePath = $uploadDirectory . $uniqueFileName; 

        //Move file to specified dir
        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
            $uploadedFiles[] = $uniqueFileName;
            $fileID = $mysqli->insert_id;

            $stmt = $mysqli->prepare("INSERT INTO file (FileName, FileType, UploadDate, UserID, TaskID, ProjectID) VALUES (?, ?, NOW(), ?, ?, ?)");
            $stmt->bind_param("ssiii", $uniqueFileName, $_FILES['files']['type'][$key], $_SESSION["UserID"], $taskID, $projectID);            

            $stmt->execute();
            $stmt->close();

        } else {
            echo json_encode(["success" => false, "message" => "Error uploading files"]);
            exit();
        }
    }

    $mysqli->close();

    echo json_encode(["success" => true, "uploadedFiles" => $uploadedFiles]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>