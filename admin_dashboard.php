<?php
// File: admin_dashboard.php
// Purpose: Admin dashboard for managing users, including updating statuses and deleting accounts.

session_start(); // Start the session to manage user login state
require_once 'config/db_config.php'; // Include the database configuration

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page if the user is not an admin
    header("Location: userlogin.php");
    exit(); // Ensure no further code is executed
}

// Establish a connection to the database
$conn = getDbConnection();
$success_message = ''; // Store success messages
$errors = []; // Store error messages

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $success_message = "User deleted successfully.";
    } else {
        $errors[] = "Failed to delete the user.";
    }
    $stmt->close(); // Close the statement to free resources
}

// Handle user status updates
if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    $new_status = $_POST['status']; // Get the new status
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $user_id);
    if ($stmt->execute()) {
        $success_message = "User status updated successfully.";
    } else {
        $errors[] = "Failed to update the user status.";
    }
    $stmt->close(); // Close the statement
}

// Retrieve all users for display in the dashboard
$users = [];
$stmt = $conn->prepare("SELECT id, username, email, role, status FROM users ORDER BY id");
$stmt->execute();
$result = $stmt->get_result(); // Get the result set
while ($row = $result->fetch_assoc()) {
    $users[] = $row; // Add each user to the array
}
$stmt->close();

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gondor Grand Hotel</title>
    <!-- Include Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body>
<?php include 'nav.php'; ?> <!-- Include the navigation bar -->
<main class="container my-5">
    <h2 class="text-center mb-4">Admin Dashboard</h2>

    <!-- Display success message if any -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- User management section -->
    <h3 class="mt-4">Manage Users</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <!-- Display user details -->
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['status']); ?></td>
                <td>
                    <!-- Form to update user status -->
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                    </form>
                    <!-- Form to delete a user -->
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
