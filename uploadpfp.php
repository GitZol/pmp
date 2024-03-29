<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION["UserID"];

$query = "SELECT * FROM user WHERE UserID = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo "Error: " . $mysqli->error;
    exit();
}

$file = $_FILES['uploadPFP'];
$fileName = $file['name'];
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
$fileSize = $file['size'];
$fileTmpName = $file['tmp_name'];

if (empty($fileName) || !exif_imagetype($fileTmpName)) {
  echo "Invalid image file.";
  header("Location: account.php");
  exit;
}

if ($fileSize > 1024000) {
  echo "File size exceeds maximum limit of 1MB.";
  header("Location: account.php");
  exit;
}

$targetDir = 'img/pfp/';
$uniqueFileName = uniqid() . '.' . $fileExtension;
$targetFile = $targetDir . $uniqueFileName;

if (!move_uploaded_file($fileTmpName, $targetFile)) {
  echo "Error uploading file.";
  exit;
}

$sql = "UPDATE user SET PFPName = ?, PFPNameOriginal = ?, PFPSize = ?, PFPType = ? WHERE UserID = ?";
$stmt = mysqli_prepare($mysqli, $sql);

$stmt->bind_param('ssssi', $uniqueFileName, $fileName, $fileSize, $fileExtension, $userID);
$stmt->execute();
$stmt->close();

$loggedIn = isset($_SESSION["UserID"]);
if (file_exists($targetFile)) {
  echo "File uploaded successfully.";
  if ($loggedIn) {
    header("Location: account.php?message=update_successful");
    exit();
  }
} else {
  echo "Error uploading file.";
}

mysqli_close($mysqli);
?>