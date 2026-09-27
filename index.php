<?php
session_start();
require 'config.php'; // Contains $pdo and $apiKey

$user = $_SESSION['user'] ?? $_SESSION['student'] ?? null;
$error = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grade Card Generator</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
    }
    .hero {
      background: linear-gradient(to right, #007bff, #00c6ff);
      color: white;
      padding: 100px 0;
      text-align: center;
    }
    .hero h1 {
      font-size: 48px;
      font-weight: bold;
    }
    .features i {
      font-size: 40px;
      color: #007bff;
      margin-bottom: 15px;
    }
    .counter {
      background: #fff;
      padding: 60px 0;
    }
    .counter .icon {
      font-size: 40px;
      color: #007bff;
    }
    .counter .count {
      font-size: 36px;
      font-weight: bold;
    }
    .faq-section {
      padding: 60px 15px;
    }
    footer {
      background: #343a40;
      color: #fff;
      padding: 30px 0;
    }
    footer a {
      color: #ccc;
      text-decoration: none;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">GradeCardGen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      
      <?php if ($user): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <?php endif; ?>
          </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <h1 data-aos="fade-down">Create Beautiful Grade Cards Instantly</h1>
    <p data-aos="fade-up" data-aos-delay="200">Quick, simple, and downloadable PDFs for students and institutions.</p>
    <a href="admin.php" class="btn btn-light btn-lg mt-3" data-aos="zoom-in" data-aos-delay="400">Start Now</a>
  </div>
</section>

<!-- Features -->
<section id="features" class="py-5">
  <div class="container text-center">
    <h2 class="mb-4">Why Use Our Platform?</h2>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <i class="fas fa-file-pdf"></i>
        <h4>Downloadable PDFs</h4>
        <p>Instant and printable results with beautiful formatting.</p>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
        <i class="fas fa-lock"></i>
        <h4>Secure & Private</h4>
        <p>Your academic data is safe, encrypted and not stored.</p>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <i class="fas fa-clock"></i>
        <h4>Time Saving</h4>
        <p>Save hours of formatting. Just fill and download.</p>
      </div>
    </div>
  </div>
</section>

<!-- Counters -->
<section class="counter" id="stats">
  <div class="container text-center">
    <div class="row g-4">
      <div class="col-md-3" data-aos="fade-up">
        <div class="icon"><i class="fas fa-users"></i></div>
        <div class="count" data-count="2500">0</div>
        <p>Students Served</p>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="150">
        <div class="icon"><i class="fas fa-file-download"></i></div>
        <div class="count" data-count="8000">0</div>
        <p>PDFs Downloaded</p>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="count" data-count="4">0</div>
        <p>Semesters Supported</p>
      </div>
      <div class="col-md-3" data-aos="fade-up" data-aos-delay="450">
        <div class="icon"><i class="fas fa-thumbs-up"></i></div>
        <div class="count" data-count="99">0</div>
        <p>% Positive Feedback</p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" id="faq">
  <div class="container">
    <h2 class="text-center mb-5">Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion" data-aos="fade-up">
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
            How do I generate my grade card?
          </button>
        </h2>
        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Click on "Start Now", fill in your details and marks, then Generate your GradeCard.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
            Do I need to register to use the service?
          </button>
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes, registration is required. Also registered users can save history and access more features.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="faq3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
            Is my data stored or shared?
          </button>
        </h2>
        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
             All data is processed in real-time and stored on our servers.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer id="footer">
  <div class="container text-center">
    <p>&copy; 2025 GradeCardGen. All rights reserved.</p>
    <div>
      <a href="#"><i class="fab fa-github mx-2"></i></a>
      <a href="#"><i class="fab fa-linkedin mx-2"></i></a>
      <a href="#"><i class="fab fa-twitter mx-2"></i></a>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000 });
</script>
<script>
  // Counter Animation
  const counters = document.querySelectorAll('.count');
  counters.forEach(counter => {
    counter.innerText = '0';
    const updateCount = () => {
      const target = +counter.getAttribute('data-count');
      const current = +counter.innerText;
      const inc = target / 100;
      if (current < target) {
        counter.innerText = Math.ceil(current + inc);
        setTimeout(updateCount, 30);
      } else {
        counter.innerText = target;
      }
    };
    updateCount();
  });
</script>
</body>
</html>
