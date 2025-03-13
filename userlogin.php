<?php
// File: userlogin.php
// Purpose: Allow users to log in to the Gondor Grand Hotel system and access their profiles or admin features.

session_start(); // Start the session to manage user login state

$activePage = 'userlogin'; // Highlight "Login" in the navigation bar
include 'nav.php'; // Include the navigation bar
require_once 'config/db_config.php'; // Include database connection configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Handle login form submission
    $username = trim($_POST["username"]); // Sanitize username input
    $password = $_POST["password"]; // Get the password input

    $conn = getDbConnection(); // Establish a database connection
    $errors = []; // Array to store validation errors

    // Query to fetch user details by username
    $stmt = $conn->prepare("SELECT id, username, password, role, race, status FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) { // Check if exactly one user is found
        $user = $result->fetch_assoc();

        // Check if the user's account is active
        if ($user['status'] !== 'active') {
            $errors[] = "Your account is inactive. Please contact the administrator.";
        } 
        // Verify the provided password
        elseif (password_verify($password, $user['password'])) {
            // Store user information in the session
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["race"] = $user["race"];

            header("Location: profile.php"); // Redirect to the profile page
            exit();
        } else {
            $errors[] = "Invalid username or password."; // Incorrect password
        }
    } else {
        $errors[] = "Invalid username or password."; // Username not found
    }

    $stmt->close(); // Close the statement
    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"> <!-- Set character encoding -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
  <title>Hotel Login - Gondor Grand Hotel</title> <!-- Page title -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap -->
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="user-login-page">

  <main class="container my-5">
    <h2 class="text-center">Login</h2>
    
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
    
    <!-- Login Form -->
    <form method="POST" action="userlogin.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" id="username" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </main>

  <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
