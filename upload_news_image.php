<?php
// Start the session to maintain user authentication and feedback data
session_start();

// Include the database configuration file for connection settings
require_once 'config/db_config.php'; 

// Check if the user is an admin; redirect to the homepage if not
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirect non-admin users
    exit();
}

// Set the active page for the navigation bar highlighting
$activePage = 'upload';

// Include the navigation bar for consistency across pages
include 'nav.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
    <title>Upload News Image</title> <!-- Set the page title -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="upload-news-page">

<main class="container my-5">
    <h2 class="text-center mb-4">Upload News Image</h2>

    <!-- Form for image upload -->
    <form action="process_upload.php" method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 500px;">
        <!-- Image file input -->
        <div class="mb-3">
            <label for="news_image" class="form-label">Select an Image:</label>
            <input type="file" class="form-control" id="news_image" name="news_image" required>
        </div>

        <!-- News title input -->
        <div class="mb-3">
            <label for="title" class="form-label">News Title:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <!-- Image description input -->
        <div class="mb-3">
            <label for="description" class="form-label">Image Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary w-100">Upload</button>
    </form>
</main>

<!-- Display success message if present -->
<?php if (isset($_SESSION['upload_message'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['upload_message']; unset($_SESSION['upload_message']); ?>
    </div>
<?php endif; ?>

<!-- Display error messages if present -->
<?php if (isset($_SESSION['upload_errors'])): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($_SESSION['upload_errors'] as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['upload_errors']); ?>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
