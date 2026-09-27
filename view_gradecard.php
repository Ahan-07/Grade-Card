<?php
require 'config.php';

$enrolment_no = $_GET['enrolment_no'] ?? '';
$semester = $_GET['semester'] ?? '';

if (!$enrolment_no || !$semester) {
    die("Invalid request");
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE enrolment_no = ?");
$stmt->execute([$enrolment_no]);
$student = $stmt->fetch();

$subjects = $pdo->prepare("SELECT * FROM manual_grade_cards WHERE student_id = ? AND semester = ?");
$subjects->execute([$student['id'], $semester]);
$grades = $subjects->fetchAll();

$summary = $pdo->prepare("SELECT * FROM grade_card_summary WHERE student_id = ? AND semester = ?");
$summary->execute([$student['id'], $semester]);
$summaryRow = $summary->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grade Card - JMI</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body { display:flex; font-family: Arial, sans-serif; background: #fff; padding: 20px; align-items: center;justify-content: center;
    flex-direction: column;
    }
    .gradecard { max-width: 900px; margin: auto; padding: 20px; border: 1px solid #000; }
    .logo { width: 70px; }
    .profile-pic { width: 80px; height: 100px; object-fit: cover; border: 1px solid #000; }
    .table th, .table td { vertical-align: middle !important; font-size: 14px; }
    .border-black { border: 1px solid #000; }
    .table th, .table td { border: 1px solid #000 !important; }
  </style>
</head>
<body>
<div class="gradecard">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <img src="jmi-logo.svg" class="logo">
    <div class="text-center flex-fill">
      <h5 class="mb-0" style="font-family: 'Arial Black';">جامعہ ملیہ اسلامیہ</h5>
      <h5 class="mb-0">JAMIA MILLIA ISLAMIA, NEW DELHI</h5>
      <small>(A Central University by an Act of Parliament)<br>NAAC Accredited Grade "A++"</small>
      <h6 class="mt-2">DIPLOMA IN <?= strtoupper($student['course']) ?> ENGINEERING</h6>
      <strong>SEMESTER-<?= $semester ?> EXAMINATION <?= date('Y') ?></strong>
    </div>
    <img src="<?= htmlspecialchars($student['photo']) ?>" alt="Profile Photo" class="profile-pic">
  </div>

  <table class="table table-bordered">
    <tr>
      <td><strong>Name of the Candidate</strong>: <?= $student['name'] ?></td>
      <td><strong>Examination Roll No</strong>: <?= $student['roll_no'] ?></td>
    </tr>
    <tr>
      <td><strong>Father's Name</strong>: <?= $student['father_name'] ?></td>
      <td><strong>Enrolment No</strong>: <?= $student['enrolment_no'] ?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>Category</strong>: <?= $student['category'] ?></td>
    </tr>
  </table>

  <!-- Subjects Table -->
  <table class="table text-center">
    <thead>
      <tr>
        <th rowspan="2">COURSE NO</th>
        <th rowspan="2">TITLE</th>
        <th colspan="2">IA</th>
        <th colspan="2">IAP</th>
        <th colspan="2">ESE</th>
        <th colspan="2">ESPE</th>
        <th rowspan="2">TOTAL</th>
      </tr>
      <tr>
        <th>MO</th><th>MM</th>
        <th>MO</th><th>MM</th>
        <th>MO</th><th>MM</th>
        <th>MO</th><th>MM</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($grades as $row): ?>
      <tr>
        <td><?= $row['course_no'] ?></td>
        <td><?= $row['subject'] ?></td>
        <td><?= $row['ia'] ?></td><td>40</td>
        <td><?= $row['iap'] ?></td><td>20</td>
        <td><?= $row['ese'] ?></td><td>70</td>
        <td><?= $row['espe'] ?></td><td>30</td>
        <td><?= $row['total'] ?></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="10"><strong>TOTAL MARKS SEM-<?= $semester ?></strong></td>
        <td><strong><?= $summaryRow['total_marks'] ?? '0' ?>/<?= $summaryRow['max_marks'] ?? '700' ?></strong></td>
      </tr>
    </tbody>
  </table>

  <!-- Footer Info -->
  <p><strong>RESULT/REMARKS</strong>: <?= strtolower($summaryRow['result']) ?></p>
  <p><small><strong>Note</strong>: A denotes not appeared or absent. U denotes "Unfair means"</small></p>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <div>Checked by : ......................................</div>
    <div class="text-end">
      <strong>ASSTT CONTROLLER OF EXAMINATIONS</strong>
    </div>
  </div>

  <div class="mt-2 small d-flex justify-content-between">
    <div><strong>Date of Result</strong>: <?= date('d-m-Y') ?></div>
    <div><strong>Date of Issue</strong>: <?= date('d-m-Y') ?></div>
  </div>

  <p class="mt-2 small"><em>This is a computer generated marksheet in Pdf form to be verified from The Office of The Controller of Examinations JMI, before submitting it as a proof</em></p>
</div>

<button onclick="printSection('.gradecard')" class="btn btn-primary my-2">🖨️ Print Admit Card</button>
        <a href="index.php" class="btn btn-secondary my-2">⬅️ Back</a>
<script>
  function printSection(sectionId) {
  var printContents = document.querySelector(sectionId).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload(); // To restore JS events and state
}
</script>
</body>
</html>
