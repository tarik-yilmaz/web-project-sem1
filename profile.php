<?php
// File: profile.php
// Purpose: Allows users to view and edit their profile information and see their reservations.

session_start(); // Start session to manage user state
$activePage = 'profile'; // Set the active page for navigation
include 'nav.php'; // Include the navigation bar
require_once 'config/db_config.php'; // Include database configuration

// Redirect to login page if the user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: userlogin.php");
    exit();
}

$conn = getDbConnection(); // Establish database connection
$user_id = $_SESSION["user_id"]; // Get the logged-in user's ID
$errors = []; // Initialize an array to store errors
$success_message = ''; // Initialize a variable for success messages

// Fetch user information from the database
$stmt = $conn->prepare("SELECT username, first_name, last_name, race, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {
    $email = trim($_POST["email"]); // Sanitize email input
    $first_name = trim($_POST["first_name"]); // Sanitize first name input
    $last_name = trim($_POST["last_name"]); // Sanitize last name input
    $old_password = $_POST["old_password"]; // Current password
    $new_password = $_POST["new_password"]; // New password
    $confirm_password = $_POST["confirm_password"]; // Confirm new password

    // Verify the old password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stored_password = $result->fetch_assoc()["password"];
    $stmt->close();

    if (!password_verify($old_password, $stored_password)) {
        $errors[] = "The old password is incorrect.";
    }

    // Validate the new password
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            $errors[] = "The new password must be at least 8 characters long.";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "The new passwords do not match.";
        }
    }

    if (empty($errors)) {
        // Update user information and optionally the password
        $query = "UPDATE users SET email = ?, first_name = ?, last_name = ?";
        $params = [$email, $first_name, $last_name];
        $types = "sss";

        if (!empty($new_password)) {
            $query .= ", password = ?";
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password
            $params[] = $hashed_password;
            $types .= "s";
        }

        $query .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();

        $success_message = "Profile updated successfully.";
    }
}

// Fetch reservations for the logged-in user
$stmt = $conn->prepare("SELECT id, room_type, check_in_date, check_out_date, status, with_breakfast, with_parking, with_pet FROM reservations WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile - Gondor Grand Hotel Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="profile-page">

<main class="container my-5">
<?php
if ($user["role"] === "admin") {
    // Display admin-specific greeting
    echo "<h2 class='text-center'>Welcome, mighty Steward of Gondor Grand Hotel, " . htmlspecialchars($user["username"]) . "!</h2>";
} else {
    // Display user-specific greeting
    echo "<h2 class='text-center'>Welcome, " . htmlspecialchars($user["username"]) . ", noble member of the race of " . htmlspecialchars($user["race"]) . "!</h2>";
}
?>

    <!-- Display errors or success messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <!-- Edit Profile Form -->
<!-- Edit Profile Form -->
<h3>Edit Profile</h3>
<form method="POST" action="profile.php">
  <input type="hidden" name="update_profile" value="1">
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user["email"]); ?>" required>
  </div>
  <div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user["first_name"]); ?>" required>
  </div>
  <div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user["last_name"]); ?>" required>
  </div>
  <div class="mb-3">
    <label for="race" class="form-label">Race</label>
    <select id="race" name="race" class="form-select" required>
      <option value="Dwarf" <?php echo ($user['race'] === 'Dwarf') ? 'selected' : ''; ?>>Dwarf</option>
      <option value="Elf" <?php echo ($user['race'] === 'Elf') ? 'selected' : ''; ?>>Elf</option>
      <option value="Ent" <?php echo ($user['race'] === 'Ent') ? 'selected' : ''; ?>>Ent</option>
      <option value="Hobbit" <?php echo ($user['race'] === 'Hobbit') ? 'selected' : ''; ?>>Hobbit</option>
      <option value="Human" <?php echo ($user['race'] === 'Human') ? 'selected' : ''; ?>>Human</option>
      <option value="Maia" <?php echo ($user['race'] === 'Maia') ? 'selected' : ''; ?>>Maia</option>
      <option value="Orc" <?php echo ($user['race'] === 'Orc') ? 'selected' : ''; ?>>Orc</option>
    </select>
  </div>
  <div class="mb-3">
    <label for="old_password" class="form-label">Current Password</label>
    <input type="password" id="old_password" name="old_password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label for="new_password" class="form-label">New Password</label>
    <input type="password" id="new_password" name="new_password" class="form-control">
  </div>
  <div class="mb-3">
    <label for="confirm_password" class="form-label">Confirm New Password</label>
    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

    <!-- Reservations Section -->
    <h3 class="mt-5">Your Reservations</h3>
    <?php if (!empty($reservations)): ?>
        <ul class="list-group">
            <?php foreach ($reservations as $reservation): ?>
                <li class="list-group-item">
                    <strong>Room Type:</strong> <?php echo htmlspecialchars($reservation['room_type']); ?><br>
                    <strong>Check-In:</strong> <?php echo htmlspecialchars($reservation['check_in_date']); ?><br>
                    <strong>Check-Out:</strong> <?php echo htmlspecialchars($reservation['check_out_date']); ?><br>
                    <strong>Breakfast:</strong> <?php echo $reservation['with_breakfast'] ? "Yes" : "No"; ?><br>
                    <strong>Parking:</strong> <?php echo $reservation['with_parking'] ? "Yes" : "No"; ?><br>
                    <strong>Pet:</strong> <?php echo $reservation['with_pet'] ? "Yes" : "No"; ?><br>
                    <strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($reservation['status'])); ?><br>
                    
                    <!-- Buttons for Edit and Cancel -->
                    <a href="reservation_handler.php?action=edit&id=<?php echo $reservation['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <?php if ($reservation['status'] !== 'cancelled'): ?>
                        <a href="reservation_handler.php?action=cancel&id=<?php echo $reservation['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reservations found.</p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
