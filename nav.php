<?php
// File: nav.php
// Purpose: Provides the navigation bar for the website, including dynamic links based on user authentication and role.

// Start session only if it hasn't been started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Initialize session to manage user state
}
?>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Hotel Name -->
        <a class="navbar-brand" href="/webdev-project-sem1/index.php">Gondor Grand Hotel</a>

        <!-- Toggle Button for Mobile Navigation -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto"> <!-- Align links to the right -->
                <!-- Standard Navigation Links -->
                <li class="nav-item">
                    <a class="nav-link <?php echo ($activePage == 'index') ? 'active' : ''; ?>" href="/webdev-project-sem1/index.php">Homepage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($activePage == 'news') ? 'active' : ''; ?>" href="/webdev-project-sem1/news.php">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($activePage == 'faq') ? 'active' : ''; ?>" href="/webdev-project-sem1/faq.php">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($activePage == 'impressum') ? 'active' : ''; ?>" href="/webdev-project-sem1/impressum.php">Impressum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($activePage == 'contact') ? 'active' : ''; ?>" href="/webdev-project-sem1/contact.php">Contact</a>
                </li>

                <?php if (isset($_SESSION['username'])): ?>
                    <!-- User Account Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo in_array($activePage, ['profile', 'reservations']) ? 'active' : ''; ?>" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item <?php echo ($activePage == 'profile') ? 'active' : ''; ?>" href="/webdev-project-sem1/profile.php">Profile</a></li>
                            <li><a class="dropdown-item <?php echo ($activePage == 'reservations') ? 'active' : ''; ?>" href="/webdev-project-sem1/reservations.php">Reservations</a></li>
                        </ul>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <!-- Admin Panel Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?php echo in_array($activePage, ['upload', 'admin_dashboard']) ? 'active' : ''; ?>" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item <?php echo ($activePage == 'upload') ? 'active' : ''; ?>" href="/webdev-project-sem1/upload_news_image.php">Upload News</a></li>
                                <li><a class="dropdown-item <?php echo ($activePage == 'admin_dashboard') ? 'active' : ''; ?>" href="/webdev-project-sem1/admin_user_management.php">User Management</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- Logout Link -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'logout') ? 'active' : ''; ?>" href="/webdev-project-sem1/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- Registration and Login Links -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'registration') ? 'active' : ''; ?>" href="/webdev-project-sem1/registration.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($activePage == 'userlogin') ? 'active' : ''; ?>" href="/webdev-project-sem1/userlogin.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
