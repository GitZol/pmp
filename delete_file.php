<?php
session_start();

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["fileID"])) {
    $fileID = $_POST["fileID"];

    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT FileName FROM file WHERE FileID = ?");
    $stmt->bind_param("i", $fileID);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($fileName);
            $stmt->fetch();

            $filePath = __DIR__ . '/uploads/' . $fileName;

            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    $stmtDelete = $mysqli->prepare("DELETE FROM file WHERE FileID = ?");
                    $stmtDelete->bind_param("i", $fileID);

                    if ($stmtDelete->execute()) {
                        echo json_encode(["success" => true, "message" => "File deleted successfully"]);
                    } else {
                        echo json_encode(["success" => false, "message" => "Error deleting file record: " . $stmtDelete->error]);
                    }

                    $stmtDelete->close();
                } else {
                    echo json_encode(["success" => false, "message" => "Error deleting file: Unable to remove file from server"]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Error deleting file: File not found on server"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File not found"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error fetching file: " . $stmt->error]);
    }

    $mysqli->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
