<?php
include 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $searchTerm = $_POST["search"];

    if (empty($searchTerm)) {
        echo json_encode(["error" => "Search term is empty"]);
        exit();
    }

    $currentUserID = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
    
    $stmt = $mysqli->prepare("SELECT UserID, Username, Email FROM user WHERE (UserID <> ?) AND (Username LIKE ? OR Email LIKE ?)");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("iss", $currentUserID, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo json_encode(["error" => "Query execution failed: " . mysqli_error($mysqli)]);
        exit();
    }

    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = array("UserID" => $row["UserID"], "Username" => $row["Username"], "Email" => $row["Email"]);
    }

    header('Content-Type: application/json');

    echo json_encode($users);

    $stmt->close();
    $mysqli->close();
}
?>
