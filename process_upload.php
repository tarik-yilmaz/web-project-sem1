<?php
// File: process_upload.php
// Purpose: Handles the uploading and validation of news items (title, description, and image) by admins.

session_start(); // Start session to manage user state
require_once 'config/db_config.php'; // Include database configuration

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirect to homepage if the user is not an admin
    exit();
}

$errors = []; // Initialize an array to store errors
$conn = getDbConnection(); // Establish database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]); // Get and sanitize title input
    $description = trim($_POST["description"]); // Get and sanitize description input
    $image = $_FILES["news_image"]; // Get the uploaded image

    // Validation: Ensure required fields are filled
    if (empty($title) || empty($description)) {
        $errors[] = "All fields are required.";
    }

    // Check for upload errors
    if ($image["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "Error uploading the image.";
    }

    // Image processing
    $upload_dir = "news/"; // Directory to store uploaded images
    $allowed_types = ["image/jpeg", "image/png", "image/gif"]; // Allowed MIME types
    $file_type = mime_content_type($image["tmp_name"]); // Get MIME type of the uploaded file
    $file_extension = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION)); // Get file extension
    $allowed_extensions = ["jpg", "jpeg", "png", "gif"]; // Allowed file extensions
    $file_name = uniqid() . "_" . basename($image["name"]); // Generate a unique name for the file
    $target_file = $upload_dir . $file_name; // Path to save the uploaded file

    // Validation: Check file type and extension
    if (!in_array($file_type, $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
        $errors[] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
    }

    // Save file and insert data into the database
    if (empty($errors)) {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Insert news item into the database
            $stmt = $conn->prepare("INSERT INTO news (title, content, image_path, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $title, $description, $target_file);
            $stmt->execute();
            $stmt->close();

            $_SESSION["upload_message"] = "News item added successfully!"; // Success message
        } else {
            $errors[] = "Failed to save the uploaded file."; // Error if file save fails
        }
    }

    // Store errors or success messages in the session
    if (!empty($errors)) {
        $_SESSION["upload_errors"] = $errors;
    }

    $conn->close(); // Close database connection
    header("Location: upload_news_image.php"); // Redirect to the upload page
    exit();
}
?>
