<?php
// File: reservations.php
// Purpose: This script displays the reservation page where users can create a new room reservation.

session_start(); // Start the session to track user data

// Redirect users who are not logged in to the login page
if (!isset($_SESSION['username'])) {
    header("Location: userlogin.php");
    exit();
}

$activePage = 'reservations'; // Set active page for navigation
include 'nav.php'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Set character encoding -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
    <title>Room Reservation - Gondor Grand Hotel</title> <!-- Page title -->
    <!-- Include Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="reservations-page">

<main class="container my-5">
    <h2 class="text-center mb-4">Reserve a Room</h2>

    <?php
    // Display errors if any exist
    if (isset($_SESSION["reservation_errors"])) {
        echo "<div class='alert alert-danger'><ul>";
        foreach ($_SESSION["reservation_errors"] as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul></div>";
        unset($_SESSION["reservation_errors"]); // Clear errors after displaying
    }

    // Display success message if available
    if (isset($_SESSION["reservation_message"])) {
        echo "<div class='alert alert-success'>{$_SESSION["reservation_message"]}</div>";
        unset($_SESSION["reservation_message"]); // Clear message after displaying
    }
    ?>

    <!-- Reservation Form -->
    <form action="reservation_handler.php" method="POST" class="mb-5">
        <div class="mb-3">
            <label for="arrival_date" class="form-label">Arrival Date:</label>
            <input type="date" id="arrival_date" name="checkin_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="departure_date" class="form-label">Departure Date:</label>
            <input type="date" id="departure_date" name="checkout_date" class="form-control" required>
        </div>

        <!-- Room Type Selection -->
        <label class="form-label">Room Type:</label>
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-3">
            <?php
            // Define available room types
            $room_types = ["Dwarf", "Elf", "Ent", "Hobbit", "Human", "Maia", "Orc"];
            foreach ($room_types as $room_type) {
                echo "
                <div class='col'>
                    <img src='images/{$room_type}Room.jpg' alt='{$room_type} Room' class='room-image'>
                    <input type='radio' id='{$room_type}' name='room_type' value='{$room_type}' required>
                    <label for='{$room_type}'>{$room_type} Room</label>
                </div>";
            }
            ?>
        </div>

        <!-- Additional Options -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="breakfast" id="breakfast">
            <label class="form-check-label" for="breakfast">With Breakfast</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="parking" id="parking">
            <label class="form-check-label" for="parking">With Parking</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pets" id="pets">
            <label class="form-check-label" for="pets">Bringing Pets</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Reserve</button>
    </form>
</main>

<?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
