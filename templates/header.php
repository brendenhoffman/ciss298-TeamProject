<?php session_start(); ?>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

      <!-- Logo + Name as Home button -->
      <a class="navbar-brand d-flex align-items-center" href="#" onclick="loadPage('home')">
        <img src="/assets/logo.png" alt="Logo" width="32" height="32" class="me-2 d-none d-md-inline">
        Hotel Mirage
      </a>

      <!-- Phone icon for narrow screens -->
      <div class="d-flex align-items-center ms-auto">
        <a href="tel:+15551234567" class="d-lg-none me-2" title="Call us">
          <img src="/assets/call.png" alt="Call" width="28" height="28">
        </a>

        <!-- Hamburger Menu -->
        <button class="navbar-toggler order-last" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
          aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- Navbar Content -->
      <div class="collapse navbar-collapse text-end" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#" onclick="loadPage('reservation.php')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">Reservations</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="loadPage('testimonials.php')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">Testimonials</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="loadPage('about')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">About</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="moreDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              More
            </a>
            <ul class="dropdown-menu text-end" aria-labelledby="moreDropdown">
              <li><a class="dropdown-item" href="#" onclick="loadPage('contactus')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">Contact</a></li>
              <li><a class="dropdown-item" href="#" onclick="loadPage('gallery.php')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">Gallery</a></li>
            </ul>
          </li>
        </ul>

        <!-- Right Side: Phone Number (DESKTOP) + Login/Sign Up -->
        <div class="d-lg-flex align-items-center gap-3">
          <div class="d-none d-lg-block text-nowrap">
            <a href="tel:+15551234567" class="text-white text-decoration-none fw-bold">
              (555) 123-4567
            </a>
          </div>

          <div class="w-100 w-lg-auto text-end">
            <?php if (isset($_SESSION['user'])): ?>
              <div class="dropdown d-inline-block">
                <button class="btn btn-outline-light btn-link text-white fw-bold text-decoration-none dropdown-toggle px-2" type="button"
                  id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <?= htmlspecialchars($_SESSION['user']['username']) ?><?= $_SESSION['user']['is_admin'] ? ' (Admin)' : '' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end text-end" aria-labelledby="userDropdown">
                  <li><a class="dropdown-item" href="#" onclick="loadPage('myReservations.php')" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">My Reservations</a></li>
                  <li><a class="dropdown-item" href="#" onclick="logoutUser()">Log Out</a></li>
                </ul>
              </div>
            <?php else: ?>
              <a href="#" onclick="loadPage('login.php')" class="btn btn-outline-light btn-sm emphasis-button py-2" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">&nbsp;Login&nbsp;</a>
              <a href="#" onclick="loadPage('signup.php')" class="btn btn-success btn-sm emphasis-button py-2" data-bs-toggle="collapse" data-bs-target=".navbar-collapse.show">Sign Up</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
