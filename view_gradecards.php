
<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

// Fetch branches for dropdown
$branches = $pdo->query("SELECT id, name FROM branches")->fetchAll();

$students = [];
$gradeCards = [];
$selected_branch = $_GET['branch_id'] ?? '';
$selected_semester = $_GET['semester'] ?? '';

if ($selected_branch && $selected_semester) {
    // Get students of this branch
    $stmt = $pdo->prepare("SELECT * FROM students WHERE branch_id = ?");
    $stmt->execute([$selected_branch]);
    $students = $stmt->fetchAll();

    // Get grade cards for these students and semester
    $stmt = $pdo->prepare("SELECT * FROM grade_card_summary WHERE student_id IN (SELECT id FROM students WHERE branch_id = ?) AND semester = ?");
    $stmt->execute([$selected_branch, $selected_semester]);
    $gradeCards = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Grade Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 p-4 bg-white shadow rounded">
    <h4 class="mb-4">View Grade Cards (Branch & Semester Wise)</h4>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <select name="branch_id" class="form-select" required>
                <option value="">Select Branch</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= ($selected_branch == $branch['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($branch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select name="semester" class="form-select" required>
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?= $i ?>" <?= ($selected_semester == $i) ? 'selected' : '' ?>>Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">View</button>
        </div>
    </form>

    <?php if ($students && $selected_semester): ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Roll No</th>
                    <th>Enrolment No</th>
                    <th>Total Marks</th>
                    <th>Max Marks</th>
                    <th>Result</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $stu): ?>
                <?php
                $gc = null;
                foreach ($gradeCards as $row) {
                    if ($row[0]['student_id'] == $stu['id']) {
                        $gc = $row[0];
                        break;
                    }
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($stu['name']) ?></td>
                    <td><?= htmlspecialchars($stu['roll_no']) ?></td>
                    <td><?= htmlspecialchars($stu['enrolment_no']) ?></td>
                    <td><?= $gc ? htmlspecialchars($gc['total_marks']) : '-' ?></td>
                    <td><?= $gc ? htmlspecialchars($gc['max_marks']) : '-' ?></td>
                    <td><?= $gc ? htmlspecialchars($gc['result']) : '-' ?></td>
                    <td>
                        <?php if ($gc): ?>
                            <a href="../view_gradecard.php?enrolment_no=<?= urlencode($stu['enrolment_no']) ?>&semester=<?= $selected_semester ?>" class="btn btn-success btn-sm">View</a>
                        <?php else: ?>
                            <span class="text-danger">Not Generated</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($selected_branch && $selected_semester): ?>
        <div class="alert alert-warning">No students or grade cards found for this selection.</div>
    <?php endif; ?>
</div>
</body>
</html>