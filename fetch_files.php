<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["taskID"])) {

    $taskID = $_POST["taskID"];

    $stmt = $mysqli->prepare("SELECT FileID, FileName, FileType, UploadDate, FileURL FROM file WHERE TaskID = ?");
    $stmt->bind_param("i", $taskID);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $files = [];

        while ($row = $result->fetch_assoc()) {
            $row['FileURL'] = 'http://127.0.0.1/pmp/uploads/' . $row['FileName'];
            $files[] = $row;
        }

        $stmt->close();
        $mysqli->close();

        echo json_encode(["success" => true, "files" => $files]);
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error fetching files: " . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>