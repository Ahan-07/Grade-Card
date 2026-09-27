<?php
session_start();
require 'config.php';

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$student = $_SESSION['student'];
$id = $student['id'];

// Fetch all grade card summaries for this student
$stmt = $pdo->prepare("SELECT * FROM grade_card_summary WHERE student_id = ? ORDER BY semester ASC");
$stmt->execute([$id]);
$gradeCards = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            min-height: 100vh;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
        .btn-download {
            background: linear-gradient(to right, #00c9ff, #92fe9d);
            border: none;
            color: black;
            font-weight: bold;
        }
        .btn-download:hover {
            background: linear-gradient(to right, #92fe9d, #00c9ff);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">GradeCardGen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
      
      <?php if ($user): ?>
            <li class="nav-item"><a  class="nav-link" href="dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a  class="nav-link" href="logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link"  href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link"  href="register.php">Register</a></li>
          <?php endif; ?>
          </ul>
    </div>
  </div>
</nav>

<div class="container py-5">
    <h2 class="text-center mb-4">🎓 Student Dashboard</h2>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white text-dark p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3 text-center">
                        <img src="<?= htmlspecialchars($student['photo']) ?>" alt="Profile Photo" class="profile-img">
                    </div>
                    <div class="col-md-9">
                        <h4><?= htmlspecialchars($student['name']) ?></h4>
                        <p>Father: <?= htmlspecialchars($student['father_name']) ?></p>
                        <p>Roll No: <?= htmlspecialchars($student['roll_no']) ?> | Enrolment: <?= htmlspecialchars($student['enrolment_no']) ?></p>
                        <p>Course: <?= htmlspecialchars($student['course']) ?> | Category: <?= htmlspecialchars($student['category']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Semester-wise Grade Cards -->
<div class="card bg-white text-dark mt-4 p-4">
    <h5 class="mb-3">📄 Semester-wise Grade Cards</h5>
    <?php if ($gradeCards): ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Total Marks</th>
                    <th>Max Marks</th>
                    <th>Result</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($gradeCards as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['semester']) ?></td>
                    <td><?= htmlspecialchars($row['total_marks']) ?></td>
                    <td><?= htmlspecialchars($row['max_marks']) ?></td>
                    <td><?= htmlspecialchars($row['result']) ?></td>
                    <td>
                        <a href="view_gradecard.php?enrolment_no=<?= urlencode($student['enrolment_no']) ?>&semester=<?= $row['semester'] ?>" class="btn btn-download btn-sm">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning mt-4">No grade cards available yet.</div>
    <?php endif; ?>
</div>
        </div>
    </div>
</div>
</body>
</html>
