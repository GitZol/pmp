<?php
session_start();
include 'db_connection.php';

if (isset($_POST["createProject"])) {
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