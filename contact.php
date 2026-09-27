<?php
session_start();
require 'config.php'; // DB connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Adjust session key based on your login logic
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['student'];

$success = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $stmt = $pdo->prepare("INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user['id'], $name, $email, $message]);

    $success = "Message sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6dd5ed, #2193b0);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .contact-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      padding: 40px;
      width: 100%;
      max-width: 600px;
    }
    .btn-gradient {
      background: linear-gradient(to right, #28a745, #20c997);
      border: none;
      color: white;
    }
    .btn-gradient:hover {
      background: linear-gradient(to right, #20c997, #28a745);
    }
  </style>
</head>
<body>

  <div class="contact-card">
    <h2 class="text-center mb-4">📬 Contact Us</h2>

    <?php if ($success): ?>
      <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Your Message</label>
        <textarea class="form-control" name="message" rows="4" required></textarea>
      </div>
      <div class="d-grid">
        <button class="btn btn-gradient btn-lg" type="submit">Send Message</button>
      </div>
    </form>
    <?php endif; ?>
  </div>

</body>
</html>
