<?php
// File: edit_reservation.php
// Purpose: Allows users to edit their room reservations, including check-in/out dates and additional options (breakfast, parking, pets).

session_start(); // Start session to track user login state
require_once 'config/db_config.php'; // Include the database configuration file

// Restrict access to logged-in users
if (!isset($_SESSION["username"])) {
    header("Location: userlogin.php"); // Redirect to login page if not logged in
    exit();
}

$conn = getDbConnection(); // Establish database connection

// Check if a reservation ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Reservation ID not provided."); // Terminate if no ID is present
}

$reservation_id = $_GET['id']; // Get the reservation ID from the URL
$user_id = $_SESSION["user_id"]; // Get the user ID from the session

// Fetch the reservation and ensure it belongs to the logged-in user
$stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $reservation_id, $user_id);
$stmt->execute();
$reservation = $stmt->get_result()->fetch_assoc(); // Fetch the reservation as an associative array
$stmt->close();

if (!$reservation) {
    die("Reservation not found or access denied."); // Terminate if the reservation doesn't exist or doesn't belong to the user
}

// Handle form submission for reservation updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $check_in_date = $_POST["check_in_date"]; // Retrieve new check-in date
    $check_out_date = $_POST["check_out_date"]; // Retrieve new check-out date
    $with_breakfast = isset($_POST["with_breakfast"]) ? 1 : 0; // Check if breakfast is selected
    $with_parking = isset($_POST["with_parking"]) ? 1 : 0; // Check if parking is selected
    $with_pet = isset($_POST["with_pet"]) ? 1 : 0; // Check if pets are included

    $errors = []; // Initialize an array to store validation errors

    // Validate the input dates
    $check_in = DateTime::createFromFormat('Y-m-d', $check_in_date);
    $check_out = DateTime::createFromFormat('Y-m-d', $check_out_date);

    if (!$check_in || $check_in->format('Y-m-d') !== $check_in_date) {
        $errors[] = "Invalid check-in date.";
    }
    if (!$check_out || $check_out->format('Y-m-d') !== $check_out_date) {
        $errors[] = "Invalid check-out date.";
    }
    if (empty($errors) && $check_out <= $check_in) {
        $errors[] = "Check-out date must be after check-in date.";
    }

    // Update the reservation if no errors are found
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE reservations SET check_in_date = ?, check_out_date = ?, with_breakfast = ?, with_parking = ?, with_pet = ? WHERE id = ?");
        $stmt->bind_param("sssiii", $check_in_date, $check_out_date, $with_breakfast, $with_parking, $with_pet, $reservation_id);
        $stmt->execute();
        $stmt->close();

        // Store a success message in the session and redirect to the profile page
        $_SESSION["reservation_message"] = "Reservation updated successfully.";
        header("Location: profile.php");
        exit();
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation - Gondor Grand Hotel</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
</head>
<body class="reservations-page">
<main class="container my-5">
    <h2 class="text-center">Edit Reservation</h2>

    <!-- Display validation errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form for editing reservation -->
    <form method="POST" action="edit_reservation.php?id=<?php echo $reservation_id; ?>">
        <!-- Check-In Date -->
        <div class="mb-3">
            <label for="check_in_date" class="form-label">Check-In Date</label>
            <input type="date" id="check_in_date" name="check_in_date" class="form-control" value="<?php echo htmlspecialchars($reservation['check_in_date']); ?>" required>
        </div>
        <!-- Check-Out Date -->
        <div class="mb-3">
            <label for="check_out_date" class="form-label">Check-Out Date</label>
            <input type="date" id="check_out_date" name="check_out_date" class="form-control" value="<?php echo htmlspecialchars($reservation['check_out_date']); ?>" required>
        </div>
        <!-- Additional Options -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="with_breakfast" name="with_breakfast" <?php echo $reservation['with_breakfast'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="with_breakfast">With Breakfast</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="with_parking" name="with_parking" <?php echo $reservation['with_parking'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="with_parking">With Parking</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="with_pet" name="with_pet" <?php echo $reservation['with_pet'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="with_pet">Bringing Pets</label>
        </div>
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    </form>
</main>
<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
