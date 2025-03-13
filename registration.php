<?php 
// File: registration.php
// Purpose: This script handles user registration, allowing new users to create accounts with details such as race, name, email, username, and password.

$activePage = 'registration'; // Set the active page for navigation
include 'nav.php'; // Include the navigation bar
require_once 'config/db_config.php'; // Include database configuration

$conn = getDbConnection(); // Establish a database connection
$errors = []; // Initialize an array to store error messages
$success_message = ''; // Initialize a variable to store success messages

// Initialize input data with default values or values from POST request
$input_data = [
    'race' => isset($_POST['race']) ? $_POST['race'] : '',
    'first_name' => isset($_POST['first_name']) ? $_POST['first_name'] : '',
    'last_name' => isset($_POST['last_name']) ? $_POST['last_name'] : '',
    'email' => isset($_POST['email']) ? $_POST['email'] : '',
    'username' => isset($_POST['username']) ? $_POST['username'] : ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

    // Validation
    if (empty($input_data['race']) || empty($input_data['first_name']) || empty($input_data['last_name']) || empty($input_data['email']) || empty($input_data['username']) || empty($password) || empty($confirm_password)) {
        $errors[] = "Please fill in all fields.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!filter_var($input_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_data['username']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = "The username is already taken.";
    }
    $stmt->close();

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, first_name, last_name, race) VALUES (?, ?, ?, 'user', ?, ?, ?)");
        $stmt->bind_param(
            "ssssss",
            $input_data['username'],
            $hashed_password,
            $input_data['email'],
            $input_data['first_name'],
            $input_data['last_name'],
            $input_data['race']
        );
        $stmt->execute();
        $stmt->close();

        $success_message = "Registration successful! Welcome, " . htmlspecialchars($input_data['username']) . ", noble member of the race of " . htmlspecialchars($input_data['race']) . "!";
        $input_data = [
            'race' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'username' => ''
        ]; // Reset input fields
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"> <!-- Set character encoding -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
  <title>Hotel Registration - Gondor Grand Hotel</title> <!-- Page title -->
  <!-- Include Bootstrap for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="registration-page">

<main class="container my-5">
    <h2 class="text-center">Registration</h2>

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

    <!-- Registration Form -->
    <form method="POST" action="registration.php">
      <div class="mb-3">
        <label for="race" class="form-label">Race</label>
        <select id="race" name="race" class="form-select" required>
          <option value="Dwarf" <?php echo ($input_data['race'] === 'Dwarf') ? 'selected' : ''; ?>>Dwarf</option>
          <option value="Elf" <?php echo ($input_data['race'] === 'Elf') ? 'selected' : ''; ?>>Elf</option>
          <option value="Ent" <?php echo ($input_data['race'] === 'Ent') ? 'selected' : ''; ?>>Ent</option>
          <option value="Hobbit" <?php echo ($input_data['race'] === 'Hobbit') ? 'selected' : ''; ?>>Hobbit</option>
          <option value="Human" <?php echo ($input_data['race'] === 'Human') ? 'selected' : ''; ?>>Human</option>
          <option value="Maia" <?php echo ($input_data['race'] === 'Maia') ? 'selected' : ''; ?>>Maia</option>
          <option value="Orc" <?php echo ($input_data['race'] === 'Orc') ? 'selected' : ''; ?>>Orc</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($input_data['first_name']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($input_data['last_name']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($input_data['email']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($input_data['username']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="confirm-password" class="form-label">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</main>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
