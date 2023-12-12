<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'project_management_platform');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Session initialization
session_start();

// Retrieve the user's ID from the session
$userID = $_SESSION['UserID'];

// Validate the user's identity
if (!$userID) {
    echo "Invalid user ID. Please login to access this page.";
    exit();
}

// Confirm the deletion
if (isset($_POST['confirmDelete']) && $_POST['confirmDelete'] == 'yes') {
    // Prepare the SQL query to delete the account
    $sql = "DELETE FROM user WHERE userID='$userID'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Your account has been successfully deleted.";

        // Destroy the session to log the user out
        session_destroy();

        // Redirect to the login page
        header('Location: login.php');
    } else {
        echo "An error occurred while deleting your account: " . $conn->error;
    }
} else {
    echo "Are you sure you want to delete your account?";
}

// Close the database connection
$conn->close();
?>
