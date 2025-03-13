<?php
// File: edit_user.php
// Purpose: Allows admins to edit user details such as name, email, race, role, and optionally update the password.

session_start(); // Start session to manage user authentication state
require_once 'config/db_config.php'; // Include the database configuration file

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php'); // Redirect to homepage if the user is not an admin
    exit();
}

$conn = getDbConnection(); // Establish database connection
$errors = []; // Initialize an array to store error messages
$success_message = ''; // Initialize a variable to store success messages

// Fetch user information if a valid user ID is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id']; // Get the user ID from the URL
    $stmt = $conn->prepare("SELECT id, username, email, race, role, first_name, last_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc(); // Fetch the user as an associative array
    $stmt->close();

    if (!$user) {
        $errors[] = "User not found."; // Add an error if the user does not exist
    }
} else {
    $errors[] = "Invalid request."; // Add an error if no user ID is provided
}

// Handle form submission for user updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id']; // Get the user ID from the form
    $first_name = $_POST['first_name']; // Get updated first name
    $last_name = $_POST['last_name']; // Get updated last name
    $email = $_POST['email']; // Get updated email
    $race = $_POST['race']; // Get updated race
    $role = $_POST['role']; // Get updated role
    $new_password = $_POST['new_password']; // Get new password, if provided

    // Validation: Ensure all required fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($race) || empty($role)) {
        $errors[] = "All fields are required.";
    }

    // Proceed with updating the user if no validation errors
    if (empty($errors)) {
        // Build the SQL query dynamically
        $query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, race = ?, role = ?";
        $params = [$first_name, $last_name, $email, $race, $role]; // Parameters for the query
        $types = "sssss"; // Data types for bind_param

        if (!empty($new_password)) {
            // If a new password is provided, hash it and include it in the query
            $query .= ", password = ?";
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the password
            $params[] = $hashed_password;
            $types .= "s";
        }

        $query .= " WHERE id = ?"; // Add condition to update the specific user
        $params[] = $user_id;
        $types .= "i";

        // Execute the query
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $success_message = "User updated successfully."; // Set success message if the query is successful
        } else {
            $errors[] = "Failed to update user."; // Add error if the query fails
        }
        $stmt->close();
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
    <!-- Include Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include 'nav.php'; ?> <!-- Include the navigation bar -->

<main class="container my-5">
    <h2 class="text-center">Edit User</h2>

    <!-- Display error messages if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (!empty($success_message)): ?>
        <!-- Display success message if the update is successful -->
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Display the form if the user is found -->
    <?php if (isset($user)): ?>
        <form method="POST" action="edit_user.php">
            <input type="hidden" name="update_user" value="1">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">

            <!-- First Name -->
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            
            <!-- Last Name -->
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            
            <!-- Race -->
            <div class="mb-3">
                <label for="race" class="form-label">Race</label>
                <select id="race" name="race" class="form-select" required>
                    <option value="Dwarf" <?php echo $user['race'] === 'Dwarf' ? 'selected' : ''; ?>>Dwarf</option>
                    <option value="Elf" <?php echo $user['race'] === 'Elf' ? 'selected' : ''; ?>>Elf</option>
                    <option value="Ent" <?php echo $user['race'] === 'Ent' ? 'selected' : ''; ?>>Ent</option>
                    <option value="Hobbit" <?php echo $user['race'] === 'Hobbit' ? 'selected' : ''; ?>>Hobbit</option>
                    <option value="Human" <?php echo $user['race'] === 'Human' ? 'selected' : ''; ?>>Human</option>
                    <option value="Maia" <?php echo $user['race'] === 'Maia' ? 'selected' : ''; ?>>Maia</option>
                    <option value="Orc" <?php echo $user['race'] === 'Orc' ? 'selected' : ''; ?>>Orc</option>
                </select>
            </div>
            
            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <!-- New Password -->
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
