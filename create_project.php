<?php
session_start();

$hostname = "127.0.0.1";
$username = "root";
$password = "";
$db_name = "project_management_platform";

if (isset($_POST["createProject"])) {
    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if ($mysqli->connect_error) {  
        die("Connection failed: ". $mysqli->connect_error);
    }

    $projectName = isset($_POST["projectName"]) ? $_POST["projectName"] : '';
    $projectDesc = isset($_POST["description"]) ? $_POST["description"] : '';
    $startDate = isset($_POST["startDate"]) ? $_POST["startDate"] : '';
    $endDate = isset($_POST["endDate"]) ? $_POST["endDate"] : '';
    $userID = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : '';

    $stmt = $mysqli->prepare("INSERT INTO `project` (`ProjectName`, `Description`, `StartDate`, `EndDate`, `UserID`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $projectName, $projectDesc, $startDate, $endDate, $userID);

    if ($stmt->execute()) {
        echo "New project created successfully!";
        header("Location: home.php");
        exit();
    } else {
        echo "Error creating project: ". $stmt->error;
    }

    $stmt->close();
    $mysqli->close();  

}
?>