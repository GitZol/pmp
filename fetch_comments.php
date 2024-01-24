<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['taskID'])) {
    $taskID = $_GET['taskID'];


    $commentsData = [];
    $stmt = $mysqli->prepare("SELECT c.CommentID, c.Content, c.Timestamp, c.UserID, c.TaskID, u.Username
    FROM comment c
    JOIN user u ON u.UserID = c.UserID
    WHERE c.TaskID = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $commentID = $row['CommentID'];
            $commentContent = $row['Content'];
            $timestamp = $row['Timestamp'];
            $userID = $row['UserID'];
            $username = $row['Username'];

            $commentsData[] = [
                'CommentID' => $commentID,
                'Content' => $commentContent,
                'Timestamp' => $timestamp,
                'UserID' => $userID,
                'Username' => $username,
                'TaskID' => $taskID
            ];
        }
        $mysqli->close();
        header('Content-Type: application/json');
        echo json_encode($commentsData);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error executing query']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['error'=> 'Invalid request']);
    exit;
}
?>