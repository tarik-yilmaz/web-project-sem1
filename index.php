<!-- File: index.php -->
<!-- Purpose: The homepage of the Gondor Grand Hotel website, providing a welcoming message and introductory content. -->

<!-- Include Navigation -->
<?php 
  session_start(); // Start the session to track user state
  $activePage = 'index'; // Highlight the active homepage in the navigation
  include 'nav.php'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8"> <!-- Set character encoding for the page -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Enable responsive design -->
  <title>Gondor Grand Hotel</title> <!-- Title of the homepage -->
  <!-- Include Bootstrap for styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>

<body class="index-page">
  <!-- Main Content -->
  <main class="container my-5">
    <h2 class="text-center">Welcome to the Gondor Grand Hotel</h2> <!-- Welcoming title -->
    <p class="text-center">
      Experience the world of Tolkien up close - live like a hobbit, dine like a wizard, and sleep like a king.
    </p>
    <div id="page-content">
      <!-- Placeholder for specific content -->
    </div>
  </main>

  <!-- Include Footer -->
  <?php include 'footer.php'; ?> <!-- Footer file inclusion -->
</body>
</html>
