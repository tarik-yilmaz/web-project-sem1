<?php
session_start();
require_once 'config/db_config.php';

// Check if the request is a POST request and the 'update_status' action is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $user_id = $_POST["user_id"]; // Retrieve the user ID from the form
    $status = $_POST["status"]; // Retrieve the new status from the form

    // Establish a database connection
    $conn = getDbConnection();

    // Prepare an SQL statement to update the user's status
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $user_id); // Bind the parameters (status and user ID)
    $stmt->execute(); // Execute the statement
    $stmt->close(); // Close the statement
    $conn->close(); // Close the database connection

    // Redirect back to the admin user management page after updating
    header("Location: admin_user_management.php");
    exit();
}
?>
