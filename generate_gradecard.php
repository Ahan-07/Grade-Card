<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

$student = null;
if (isset($_GET['enrolment_no'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE enrolment_no = ?");
    $stmt->execute([$_GET['enrolment_no']]);
    $student = $stmt->fetch();
}

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

    // Insert grade card summary
    $stmt = $pdo->prepare("INSERT INTO grade_card_summary (student_id, semester, total_marks, max_marks, result) 
                           VALUES (?, ?, ?, ?, ?)
                           ON DUPLICATE KEY UPDATE total_marks=?, max_marks=?, result=?");
    $stmt->execute([
        $student_id, $semester, $total_marks, $max_marks, $result,
        $total_marks, $max_marks, $result
    ]);

    // Remove existing detailed entries to prevent duplication
    $pdo->prepare("DELETE FROM manual_grade_card WHERE student_id = ? AND semester = ?")->execute([$student_id, $semester]);

    // Insert each subject's entry
    for ($i = 0; $i < count($subjects); $i++) {
        $stmt = $pdo->prepare("INSERT INTO manual_grade_card 
            (student_id, semester, course_no, subject_name, ia, iap, ese, espe, total, grade)
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
    }

    echo "<div style='text-align:center; margin-top:20px;' class='alert alert-success'>
            ✅ Grade card saved successfully! 
            <br><br><a href='manual_entry.php' class='btn btn-success mt-2'>← Go Back</a>
          </div>";
} else {
    echo "<div class='alert alert-danger text-center mt-4'>Invalid request.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Grade Card Entry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
     .nav-link {
      color: white;
    }
    .nav-link:hover {
      color: #ffd700;
    }
  </style>
</head>
<body class="bg-light">
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

  <div class="container mt-5 p-4 bg-white shadow rounded">
    <h4 class="text-center mb-4">🧞 Enter Grade Card by Enrolment No</h4>

    <!-- Search Form -->
    <form method="GET" class="mb-4 row g-2 justify-content-center">
      <div class="col-md-4">
        <input type="text" name="enrolment_no" class="form-control" placeholder="Enter Enrolment No" required value="<?= htmlspecialchars($_GET['enrolment_no'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary">Search</button>
      </div>
    </form>

    <?php if ($student): ?>
    <!-- Student Info -->
    <form method="POST" action="save_manual_gradecard.php">
      <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
      <input type="hidden" name="enrolment_no" value="<?= $student['enrolment_no'] ?>">

      <div class="row g-3 mb-3">
        <div class="col-md-4"><label class="form-label">Student Name</label><input type="text" class="form-control" value="<?= $student['name'] ?>" readonly></div>
        <div class="col-md-4"><label class="form-label">Father's Name</label><input type="text" class="form-control" value="<?= $student['father_name'] ?>" readonly></div>
        <div class="col-md-4"><label class="form-label">Roll Number</label><input type="text" class="form-control" value="<?= $student['roll_no'] ?>" readonly></div>
        <div class="col-md-4"><label class="form-label">Enrolment No</label><input type="text" class="form-control" value="<?= $student['enrolment_no'] ?>" readonly></div>
        <div class="col-md-4"><label class="form-label">Category</label><input type="text" class="form-control" value="<?= $student['category'] ?>" readonly></div>
        <div class="col-md-4"><label class="form-label">Branch</label><input type="text" class="form-control" id="branch" name="branch" value="<?= $student['course'] ?>" readonly></div>
      </div>

      <!-- Semester -->
      <div class="mb-3">
        <label for="semester">Semester</label>
        <select class="form-select" id="semester" name="semester" required onchange="loadSubjects()">
          <option value="">Select Semester</option>
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <option value="<?= $i ?>">Semester <?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <!-- Subject Entry -->
      <div class="table-responsive mb-3">
        <table class="table table-bordered text-center">
          <thead><tr><th>Course No</th><th>Subject</th><th>IA</th><th>IAP</th><th>ESE</th><th>ESPE</th><th>Total</th><th>Grade</th></tr></thead>
          <tbody id="subject-table">
            <!-- Populated dynamically -->
          </tbody>
        </table>
      </div>

      <!-- Total -->
      <div class="row mb-3">
        <div class="col-md-4"><label>Total Marks</label><input name="total_marks" type="number" class="form-control" /></div>
        <div class="col-md-4"><label>Max Marks</label><input name="max_marks" type="number" class="form-control" value="700"/></div>
        <div class="col-md-4"><label>Result</label>
          <select name="result" class="form-select">
            <option>Passed</option>
            <option>Failed</option>
          </select>
        </div>
      </div>

      <div class="text-end"><button id="submitBtn" class="btn btn-success" type="submit" disabled>Submit Grade Card</button></div>
    </form>
    <?php elseif (isset($_GET['enrolment_no'])): ?>
      <div class="alert alert-warning text-center">Student not found for Enrolment No: <?= htmlspecialchars($_GET['enrolment_no']) ?></div>
    <?php endif; ?>
  </div>

  <script>
    function loadSubjects() {
      const enrol = document.querySelector('[name="enrolment_no"]').value;
      const sem = document.getElementById('semester').value;
      fetch(`subject_fetch.php?enrolment_no=${enrol}&semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          let html = '';
          data.forEach((sub, i) => {
            html += `
              <tr>
                <td><input name="course_no[]" class="form-control" value="${sub.code}" readonly></td>
                <td><input name="subject[]" class="form-control" value="${sub.name}" readonly></td>
                <td><input name="ia[]" type="number" class="form-control" oninput="calcTotal(this)" min="0" max="30"></td>
                <td><input name="iap[]" type="number" class="form-control" oninput="calcTotal(this)" min="0" max="20"></td>
                <td><input name="ese[]" type="number" class="form-control" oninput="calcTotal(this)" min="0" max="70"></td>
                <td><input name="espe[]" type="number" class="form-control" oninput="calcTotal(this)" min="0" max="30"></td>
                <td><input name="total[]" type="number" class="form-control" readonly></td>
                <td><input name="grade[]" class="form-control" readonly></td>
              </tr>`;
          });
          document.getElementById('subject-table').innerHTML = html;
          document.getElementById('submitBtn').disabled = false;
        });
    }

    function calcTotal(el) {
      const row = el.closest('tr');
      const ia = parseInt(row.querySelector('[name="ia[]"]').value) || 0;
      const iap = parseInt(row.querySelector('[name="iap[]"]').value) || 0;
      const ese = parseInt(row.querySelector('[name="ese[]"]').value) || 0;
      const espe = parseInt(row.querySelector('[name="espe[]"]').value) || 0;
      const total = ia + iap + ese + espe;
      row.querySelector('[name="total[]"]').value = total;
      const grade = total >= 85 ? 'A+' : total >= 75 ? 'A' : total >= 65 ? 'B+' : total >= 55 ? 'B' : total >= 45 ? 'C' : 'F';
      row.querySelector('[name="grade[]"]').value = grade;

      // Recalculate grand total marks
      calcGrandTotal();
    }

    function calcGrandTotal() {
      let sum = 0;
      document.querySelectorAll('[name="total[]"]').forEach(input => {
        sum += parseInt(input.value) || 0;
      });
      document.querySelector('[name="total_marks"]').value = sum;
    }
  </script>
</body>
</html>
