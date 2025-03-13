<!-- File: impressum.php -->
<!-- Purpose: Provides the legal information and disclaimer for the website, including provider details, contact information, and copyright notice. -->

<!-- Include Navigation -->
<?php 
  $activePage = 'impressum'; // Highlight the active Impressum page in the navigation
  include 'nav.php'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Impressum - Gondor Grand Hotel</title>
  <!-- Include Bootstrap for responsive design and styling -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="impressum-page">

  <!-- Main Content -->
  <main class="container my-5">
    <h2 class="text-center mb-4">Impressum</h2>
    <div class="row">
      <!-- Left Column: Legal and contact information -->
      <div class="col-md-8">
        <p>UID-Number: MEL12345678</p>
        <p> 
          Provider:<br>
          Middle-Earth Lore Publications<br>
          Gondor Grand Hotel<br>
          Gondor, 3791<br>
          Middle-earth
        </p>
        <p>
          Contact:<br>
          Email: info@middleearthlore.com<br>
          Phone: +43 123 456789<br>
          Website: www.middleearthlore.com
        </p>
        <p>
          Authorized Representative:<br>
          Tarik Yilmaz, CEO<br>
          Middle-Earth Lore Publications
        </p>
        <p>
          Responsible for content according to § 55 Abs. 2 RStV:<br>
          Tarik Yilmaz<br>
          Gondor Grand Hotel<br>
          Gondor, 3791<br>
          Middle-earth
        </p>
      </div>

      <!-- Right Column: Representative's image and name -->
      <div class="col-md-4 text-center">
        <img src="images/gondor_king.jpg" alt="Your Photo" class="profile-image"> <!-- Placeholder image for CEO -->
        <p class="mt-3">Tarik Yilmaz, CEO</p>
      </div>
    </div>

    <!-- Disclaimer Section -->
    <div class="mt-5">
      <h3 class="text-center">Disclaimer</h3>
      <p>
        This website and the included FAQ are based on The Lord of the Rings, a work by J.R.R. Tolkien. 
        All content is for educational and entertainment purposes only and is not officially affiliated 
        with the rights holders of The Lord of the Rings. All rights to the content of The Lord of the Rings 
        belong to the respective rights holders, in particular HarperCollins and Middle-earth Enterprises.
      </p>

      <!-- Copyright Section -->
      <h3 class="text-center">Copyright</h3>
      <p>
      This website is a fan-made project inspired by the fantasy genre. It is not affiliated with, endorsed by, 
      or associated with the Tolkien Estate, Warner Bros., or any official Lord of the Rings rights holders.
      </p>
    </div>
  </main>

  <!-- Include Footer -->
  <?php include 'footer.php'; ?>
</body>
</html>
