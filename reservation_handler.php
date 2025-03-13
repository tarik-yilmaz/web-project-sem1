<?php
// File: reservation_handler.php
// Purpose: This script handles the reservation logic for the application, including creating, cancelling, and updating reservations.

session_start(); // Start the session to track user data
require_once 'config/db_config.php'; // Include database configuration

// Redirect users who are not logged in to the login page
if (!isset($_SESSION["username"])) {
    header("Location: userlogin.php");
    exit();
}

$conn = getDbConnection(); // Establish a database connection
$errors = []; // Initialize an array to store error messages
$user_id = $_SESSION["user_id"]; // Retrieve the logged-in user's ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["cancel_reservation"])) {
        // Handle reservation cancellation
        $reservation_id = $_POST["reservation_id"];
        $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $reservation_id, $user_id);
        if ($stmt->execute()) {
            $_SESSION["reservation_message"] = "Reservation cancelled successfully!";
        } else {
            $_SESSION["reservation_errors"][] = "Failed to cancel the reservation.";
        }
        $stmt->close();
        header("Location: profile.php");
        exit();
    } else {
        // Handle new reservation creation
        $checkin_date = $_POST["checkin_date"];
        $checkout_date = $_POST["checkout_date"];
        $room_type = $_POST["room_type"];
        $breakfast = isset($_POST["breakfast"]) ? 1 : 0;
        $parking = isset($_POST["parking"]) ? 1 : 0;
        $pets = isset($_POST["pets"]) ? 1 : 0;

        $checkin = DateTime::createFromFormat('Y-m-d', $checkin_date);
        $checkout = DateTime::createFromFormat('Y-m-d', $checkout_date);

        // Validate the provided dates
        if (!$checkin || !$checkout || $checkout <= $checkin) {
            $errors[] = "Invalid date selection. Ensure check-out is after check-in.";
        }

        if (empty($errors)) {
            // Insert new reservation into the database
            $stmt = $conn->prepare("INSERT INTO reservations (user_id, check_in_date, check_out_date, status, with_breakfast, with_parking, with_pet) VALUES (?, ?, ?, 'new', ?, ?, ?)");
            $stmt->bind_param("issiii", $user_id, $checkin_date, $checkout_date, $breakfast, $parking, $pets);
            $stmt->execute();
            $stmt->close();

            $_SESSION["reservation_message"] = "Reservation successfully created!";
            header("Location: profile.php");
            exit();
        } else {
            // Store errors in the session and redirect
            $_SESSION["reservation_errors"] = $errors;
            header("Location: reservations.php");
            exit();
        }
    }
} elseif (isset($_GET["action"]) && isset($_GET["id"])) {
    // Handle reservation status updates (confirm or cancel)
    $action = $_GET["action"];
    $reservation_id = $_GET["id"];

    $status = $action === "confirm" ? "confirmed" : "cancelled";
    $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $reservation_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION["reservation_message"] = "Reservation {$status}!";
    header("Location: profile.php");
    exit();
} else {
    // Redirect to reservations page if no valid action is provided
    header("Location: reservations.php");
    exit();
}
?>
