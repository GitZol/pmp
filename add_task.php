<?php
session_start();

if (isset($_POST["createTask"])){
    if (isset($_POST["projectID"])){
        $projectID = $_POST["projectID"];

        $taskName = isset($_POST["taskName"]) ? $_POST["taskName"] : '';
        $taskDesc = isset($_POST["taskDescription"]) ? $_POST["taskDescription"] : '';
        $taskDueDate = isset($_POST["taskDueDate"]) ? $_POST["taskDueDate"] : '';
        $taskPriority = isset($_POST["taskPriority"]) ? $_POST["taskPriority"] : '';
        $taskStatus = isset($_POST["taskStatus"]) ? $_POST["taskStatus"] : '';
    }else{
        echo "No project selected.";
    }

    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $db_name = "project_management_platform";

    $mysqli = new mysqli($hostname, $username, $password, $db_name);

    if($mysqli->connect_error){
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO task (ProjectID, TaskName, Description, DueDate, Priority, Status, UserID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $projectID, $taskName, $taskDesc, $taskDueDate, $taskPriority, $taskStatus, $_SESSION["UserID"]);

    if ($stmt->execute()){
        echo "New task added successfully!";
        header("Location: home.php");
        exit();
    } else {
        echo "Error creating task: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>