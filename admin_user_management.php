<?php
// File: admin_user_management.php
// Purpose: Allows admins to manage users and view reservations. Displays all users and their statuses, as well as reservations.

session_start(); // Start session to manage user authentication state
require_once 'config/db_config.php'; // Include the database configuration

$activePage = 'admin_dashboard'; // Highlight the active page in the navigation
include 'nav.php'; // Include the navigation bar

// Restrict access to admin users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to homepage if the user is not an admin
    header('Location: index.php');
    exit(); // Stop further script execution
}

$conn = getDbConnection(); // Establish database connection

// ** Fetch the list of all users **
$stmt = $conn->prepare("SELECT id, username, email, role, status FROM users ORDER BY id");
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Get users as an associative array
$stmt->close();

// ** Fetch all reservations **
$stmt = $conn->prepare(
    "SELECT r.id, u.username, r.check_in_date, r.check_out_date, r.status, r.with_breakfast, r.with_parking, r.with_pet 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id ORDER BY r.id"
);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Get reservations as an associative array
$stmt->close();

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
</head>
<body class="admin-page">
<main class="container my-5">
    <h2 class="text-center">Admin Dashboard: User Management</h2>

    <!-- User list -->
    <h3>All Users</h3>
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
                        <form method="POST" action="update_user.php" class="d-inline">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="status" class="form-select form-select-sm d-inline w-auto">
                                <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                        </form>
                        <!-- Link to edit user details -->
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Reservation list -->
    <h3 class="mt-5">All Reservations</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Breakfast</th>
                <th>Parking</th>
                <th>Pet</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <!-- Display reservation details -->
                    <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['username']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['check_in_date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['check_out_date']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                    <td><?php echo $reservation['with_breakfast'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $reservation['with_parking'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $reservation['with_pet'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
