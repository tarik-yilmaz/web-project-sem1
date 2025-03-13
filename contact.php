<!-- File: contact.php -->
<!-- Purpose: Displays a contact form for users to send messages to the hotel administration. Includes basic form validation and feedback. -->

<!-- Include Navigation -->
<?php 
  $activePage = 'contact'; // Highlight the active page in the navigation
  include 'nav.php'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Gondor Grand Hotel</title>
  <!-- Include Bootstrap for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="contact-page">
  <main class="container my-5">
    <h2 class="text-center">Contact Us</h2>
    <p class="text-center">We’d love to hear from you! Fill out the form below, and we’ll get back to you as soon as possible.</p>

    <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Retrieve POST data from the form
          $first_name = $_POST["first_name"];
          $last_name = $_POST["last_name"];
          $email = $_POST["email"];
          $subject = $_POST["subject"];
          $message = $_POST["message"];

          // Initialize an array to store error messages
          $errors = [];

          // Validation: Check if all fields are filled
          if (empty($first_name) || empty($last_name) || empty($email) || empty($subject) || empty($message)) {
              $errors[] = "Please fill in all fields.";
          }

          // Validation: Check if the email address is in a valid format
          if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $errors[] = "Invalid email format.";
          }
          
          // Display success or error messages based on validation
          if (empty($errors)) {
              // Success message
              echo "<div class='alert alert-success'>Your message has been sent successfully! Thank you, $first_name $last_name!</div>";
          } else {
              // Error message with a list of validation errors
              echo "<div class='alert alert-danger'><ul>";
              foreach ($errors as $error) {
                  echo "<li>$error</li>";
              }
              echo "</ul></div>";
          }
      }
    ?>

    <!-- Contact form -->
    <form method="POST" action="contact.php">
      <!-- First Name field -->
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter first name" required>
      </div>
      
      <!-- Last Name field -->
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter last name" required>
      </div>

      <!-- Email field -->
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <!-- Subject field -->
      <div class="mb-3">
        <label for="subject" class="form-label">Subject</label>
        <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter the subject" required>
      </div>

      <!-- Message field -->
      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Write your message" required></textarea>
      </div>

      <!-- Submit button -->
      <button type="submit" class="btn btn-primary w-100">Send Message</button>
    </form>
  </main>

  <?php include 'footer.php'; ?> <!-- Include the footer -->
</body>
</html>
