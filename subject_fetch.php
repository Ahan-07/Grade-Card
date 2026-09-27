<?php
require '../config.php';

header('Content-Type: application/json');

$enrol = $_GET['enrolment_no'] ?? '';
$semester = $_GET['semester'] ?? '';

if (!$enrol || !$semester) {
    echo json_encode([]);
    exit;
}

// Step 1: Get student and branch
$stmt = $pdo->prepare("SELECT * FROM students WHERE enrolment_no = ?");
$stmt->execute([$enrol]);
$student = $stmt->fetch();

if (!$student) {
    echo json_encode([]);
    exit;
}

// Step 2: Find branch_id from course name
$branchStmt = $pdo->prepare("SELECT id FROM branches WHERE name = ?");
$branchStmt->execute([$student['course']]);
$branch = $branchStmt->fetch();

if (!$branch) {
    echo json_encode([]);
    exit;
}

// Step 3: Get subjects
$subjectStmt = $pdo->prepare("SELECT name, code FROM subjects WHERE branch_id = ? AND semester = ?");
$subjectStmt->execute([$branch['id'], $semester]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

// Step 4: Return JSON
echo json_encode($subjects);
