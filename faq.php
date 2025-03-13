<!-- File: faq.php -->
<!-- Purpose: Displays an FAQ section for guests to explore details about the hotel, its themed experiences, and offerings. -->

<!-- Include Navigation -->
<?php 
  $activePage = 'faq'; // Highlight the active FAQ page in the navigation
  include 'nav.php'; // Include the navigation bar
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ - Gondor Grand Hotel</title>
  <!-- Include Bootstrap for responsive design and accordion component -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
</head>
<body class="faq-page">

  <!-- Main Content -->
  <main class="container my-5">
    <h2 class="text-center">Frequently Asked Questions</h2>
    <div id="page-content">
      <!-- Accordion for FAQ items -->
      <div class="accordion" id="faqAccordion">

        <!-- FAQ Item 1 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq1">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
              1. What kind of rooms does the hotel offer?
            </button>
          </h2>
          <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Our hotel offers a variety of themed rooms that transport guests to the realms of Middle-earth. 
              Guests can choose from options like "The Shire Hobbit Hole" with cozy, round doors and 
              rustic décor, "Rivendell Suite" featuring Elven elegance, "Rohan Lodge" designed with rich 
              wood textures and tapestries, and "Gondor Royal Chambers" with elegant stone walls and 
              luxurious furnishings. For those seeking a more daring stay, there's also "The Moria Mine" 
              suite, with dwarven aesthetics and stone carvings.
            </div>
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
              2. Are there any themed dining options?
            </button>
          </h2>
          <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Absolutely! Enjoy a hearty breakfast or a seven-meal day inspired by hobbit traditions 
              at The Green Dragon Tavern. For more elegant fare, Elven Glade offers a Rivendell-inspired 
              fine dining experience, with dishes crafted from seasonal ingredients. Adventurers can try the 
              Ranger's Rest which features rugged, hearty meals inspired by the travels of Aragorn and the Dunedain.
            </div>
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
              3. Is there a special experience or package for “The One Ring”?
            </button>
          </h2>
          <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Yes! Guests can participate in the Ringbearer's Journey, a quest-inspired package that takes you
              on a unique hotel-wide scavenger hunt. Start by finding "The One Ring" hidden in your room,
              then follow clues through Rivendell, Bree, Moria, and Lothlorien-inspired areas.
              The journey ends with a "Mount Doom" experience, where you'll have a dramatic opportunity
              to “destroy” the ring (or keep a souvenir replica)!
            </div>
          </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq4">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
              4. Do you offer guided tours or activities within the hotel?
            </button>
          </h2>
          <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              We have several immersive activities, such as the Gondorian Knight Training for young guests,
              an Elvish Calligraphy Workshop, and a Dwarven Forge Tour, where guests can learn about ancient forging techniques
              and even make a souvenir. Our most popular experience is the Evening in the Prancing Pony,
              where guests gather for tales and songs performed by costumed characters around a cozy fire.            
            </div>
          </div>
        </div>

        <!-- FAQ Item 5 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq5">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
              5. Are there any Elvish-inspired wellness services?
            </button>
          </h2>
          <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Yes, visit The Healing House of Lothlorien, a tranquil spa influenced by Elven practices.
              Services include herbal baths, sound healing, and massages inspired by ancient techniques.
              There's also a "Star Pool" for nighttime swims under a ceiling of "starlight,"
              an homage to Galadriel's reverence for the stars.            
            </div>
          </div>
        </div>

        <!-- FAQ Item 6 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq6">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
              6. Can I have a personalized sword or staff crafted during my stay?            
            </button>
          </h2>
          <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              You can! The Dwarven Workshop offers a crafting experience where guests can design and
              take home a custom sword, dagger, or wizard's staff.
              Our blacksmiths will help bring your vision to life, with engravings 
              and detailing inspired by the armorers of Gondor and Rohan.            
            </div>
          </div>
        </div>

        <!-- FAQ Item 7 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq7">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
              7. Is there an area where guests can relax like hobbits?
            </button>
          </h2>
          <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Indeed! The Hobbiton Lounge is a cozy space filled with plush armchairs, hearths, and a small library.
              Here, guests can unwind with hobbit-sized mugs of ale, tea, or mulled cider.
              Twice a day, the lounge hosts Shire Storytime, where a “hobbit” tells stories about the Shire, Bilbo's travels, and
              more.            
            </div>
          </div>
        </div>

        <!-- FAQ Item 8 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq8">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
              8. Are pets allowed, especially if they're named after Middle-earth characters?
            </button>
          </h2>
          <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              We welcome pets in The Shire Rooms, especially those with Middle-earth-inspired names.
              We provide a "Pet Welcome Package" with treats, a small bed, and a name tag featuring their
              character's language (Elvish, Dwarvish, or Common Tongue).            
            </div>
          </div>
        </div>

        <!-- FAQ Item 9 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq9">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
              9. Is there a nightly event for guests?
            </button>
          </h2>
          <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="faq9" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Yes, every evening we host Middle-earth Evenings in the main hall. The event includes live music inspired by the
              cultures of elves, dwarves, and men, alongside a storytelling session by our staff. Each night has a different theme,
              from “Tales from the Shire” to “Songs of Gondor” or “Dwarven Banter.”            
            </div>
          </div>
        </div>

        <!-- FAQ Item 10 -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq10">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
              10. Are there any special souvenirs available only at the hotel?
            </button>
          </h2>
          <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="faq10" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Our gift shop, The Merchant of Dale, offers exclusive items only available at our 
              hotel, including handwoven Elvish
              blankets, hobbit-style mugs, replica maps of Middle-earth, and jewelry modeled 
              after items in Tolkien's lore. Guests
              can also purchase keepsakes from their Ringbearer's Journey experience.            
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>

  <?php include 'footer.php'; ?>
  </body>
</html>

