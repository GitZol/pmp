<?php 
// session_start();

// if (isset($_SESSION["UserID"])) {
//     $userID = $_SESSION["UserID"];

//     $hostname = "127.0.0.1";
//     $username = "root";
//     $password = "";
//     $db_name = "project_management_platform";

//     $mysqli = new mysqli($hostname, $username, $password, $db_name);

//     if ($mysqli->connect_error) {
//         die("Connection failed: ". $mysqli->connect_error);
//     }

//     $delete_query = $mysqli->prepare("DELETE FROM user WHERE UserID = ?");
//     $delete_query->bind_param("i", $userID);

//     if($delete_query->execute() ) {
//         session_destroy();
//         header("Location: login.php");
//     } else {
//         echo "Error: Unable to delete this user.";
//     }

//     $delete_query->close();
//     $mysqli->close();
// } else {
//     header("Location: home.php");
//     exit();
// }

// $conn->close();
echo "I love cock";
?>
