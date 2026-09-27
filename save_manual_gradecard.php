<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Save Grade Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
        }
        .card-custom {
            max-width: 600px;
            margin: 60px auto 0 auto;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            background: rgba(255,255,255,0.95);
            padding: 2.5rem 2rem 2rem 2rem;
        }
        .alert-success {
            font-size: 1.2rem;
            font-weight: 500;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #155724;
            border: none;
        }
        .alert-danger {
            font-size: 1.1rem;
            font-weight: 500;
            background: linear-gradient(90deg, #ffdde1 0%, #ee9ca7 100%);
            color: #721c24;
            border: none;
        }
        .btn-success {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            font-weight: 500;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
        }
        .heading {
            font-weight: 700;
            letter-spacing: 1px;
            color: #2d3436;
            margin-bottom: 1.5rem;
        }
         .nav-link {
      color: white;
    }
    .nav-link:hover {
      color: #ffd700;
    }
    </style>
</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="../admin.php">Admin Panel</a>
      <div class="d-flex">
        <ul>
            <li class="nav-item"><a class="nav-link" href="../admin.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
<div class="card card-custom">
    <h2 class="text-center heading mb-4">Grade Card Submission</h2>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $semester = $_POST['semester'];
    $subjects = $_POST['subject'];
    $course_nos = $_POST['course_no'];
    $ia = $_POST['ia'];
    $iap = $_POST['iap'];
    $ese = $_POST['ese'];
    $espe = $_POST['espe'];
    $totals = $_POST['total'];
    $grades = $_POST['grade'];
    $total_marks = $_POST['total_marks'];
    $max_marks = $_POST['max_marks'];
    $result = $_POST['result'];

    try {
        // Insert grade card summary
        $stmt = $pdo->prepare("INSERT INTO grade_card_summary (student_id, semester, total_marks, max_marks, result) 
                               VALUES (?, ?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE total_marks=?, max_marks=?, result=?");
        $stmt->execute([
            $student_id, $semester, $total_marks, $max_marks, $result,
            $total_marks, $max_marks, $result
        ]);

        // Remove existing detailed entries to prevent duplication
        $pdo->prepare("DELETE FROM manual_grade_cards WHERE student_id = ? AND semester = ?")->execute([$student_id, $semester]);

        // Insert each subject's entry
        $errorRows = [];
        for ($i = 0; $i < count($subjects); $i++) {
            try {
                $stmt = $pdo->prepare("INSERT INTO manual_grade_cards 
                    (student_id, semester, course_no, subject, ia, iap, ese, espe, total, grade)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $student_id,
                    $semester,
                    $course_nos[$i],
                    $subjects[$i],
                    $ia[$i] ?: 0,
                    $iap[$i] ?: 0,
                    $ese[$i] ?: 0,
                    $espe[$i] ?: 0,
                    $totals[$i] ?: 0,
                    $grades[$i]
                ]);
            } catch (PDOException $e) {
                $errorRows[] = "<div class='alert alert-danger'>Row $i Error: " . $e->getMessage() . "</div>";
            }
        }

        if (empty($errorRows)) {
            echo "<div class='alert alert-success text-center mb-4'>
                    ✅ Grade card saved successfully! 
                    <br><br><a href='generate_gradecard.php' class='btn btn-success mt-2'>← Go Back</a>
                  </div>";
        } else {
            foreach ($errorRows as $err) echo $err;
            echo "<div class='alert alert-danger text-center mt-3'>Some rows could not be saved. Please check above errors.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center'>DB Error: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center mt-4'>Invalid request.</div>";
}
?>
</div>
</body>
</html>
