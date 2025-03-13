<?php
// File: news.php
// Purpose: Displays the latest news items and provides functionality for admins to edit or delete news.

session_start(); // Start the session to manage user state
$activePage = 'news'; // Set the active page for navigation
include 'nav.php'; // Include the navigation bar
require_once 'config/db_config.php'; // Include the database configuration

$conn = getDbConnection(); // Establish database connection
$message = ''; // Message to display after an action

// Check if the user is an admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Handle delete request
if ($is_admin && isset($_GET['delete'])) {
    $news_id = $_GET['delete']; // Get the news ID to delete
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $news_id);
    if ($stmt->execute()) {
        $message = "News item deleted successfully."; // Success message
    } else {
        $message = "Failed to delete news item."; // Failure message
    }
    $stmt->close();
}

// Handle update request
if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_news'])) {
    $news_id = $_POST['news_id']; // Get the news ID to update
    $title = $_POST['title']; // Get the updated title
    $content = $_POST['content']; // Get the updated content

    $stmt = $conn->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $news_id);
    if ($stmt->execute()) {
        $message = "News item updated successfully."; // Success message
    } else {
        $message = "Failed to update news item."; // Failure message
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
    <title>News - Gondor Grand Hotel</title> <!-- Page title -->
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="news-page">

<main class="container my-5">
    <h2 class="text-center mb-4">Latest News</h2>

    <!-- Display action messages -->
    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="news-container">
        <?php
        // Fetch news items from the database
        $stmt = $conn->prepare("SELECT id, title, content, image_path, created_at FROM news ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Loop through each news item
            while ($row = $result->fetch_assoc()) {
                echo '<div class="news-item">';

                // Display image if available
                if (!empty($row["image_path"])) {
                    echo '<img src="' . htmlspecialchars($row["image_path"], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') . '">';
                }

                // Display title, date, and content
                echo '<h3>' . htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p><strong>Date:</strong> ' . htmlspecialchars($row["created_at"], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p>' . htmlspecialchars($row["content"], ENT_QUOTES, 'UTF-8') . '</p>';

                // Admin options for edit and delete
                if ($is_admin) {
                    echo '<form method="POST" class="mb-2">';
                    echo '<input type="hidden" name="news_id" value="' . $row["id"] . '">';
                    echo '<div class="mb-2">';
                    echo '<input type="text" name="title" class="form-control" value="' . htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') . '" required>';
                    echo '</div>';
                    echo '<div class="mb-2">';
                    echo '<textarea name="content" class="form-control" rows="3" required>' . htmlspecialchars($row["content"], ENT_QUOTES, 'UTF-8') . '</textarea>';
                    echo '</div>';
                    echo '<button type="submit" name="update_news" class="btn btn-warning btn-sm">Save Changes</button>';
                    echo '</form>';
                    echo '<a href="?delete=' . $row["id"] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this news item?\')">Delete</a>';
                }

                echo '</div>';
            }
        } else {
            echo '<p>No news available at the moment.</p>'; // Message if no news is found
        }

        $stmt->close(); // Close statement
        $conn->close(); // Close connection
        ?>
    </div>
</main>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
