<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $searchTerm = $_POST["search"];

    // Check if the search term is not empty
    if (empty($searchTerm)) {
        echo json_encode(["error" => "Search term is empty"]);
        exit();
    }

    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT UserID, Username, Email FROM user WHERE Username LIKE ? OR Email LIKE ?");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
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

    // Set the response type as JSON
    header('Content-Type: application/json');

    echo json_encode($users);

    $stmt->close();
    $mysqli->close();
}
?>
