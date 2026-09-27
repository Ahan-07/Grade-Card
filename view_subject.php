<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
// Fetch branches for dropdown
$branches = $pdo->query("SELECT * FROM branches ORDER BY name")->fetchAll();

// Get selected filters
$selected_branch = $_GET['branch_id'] ?? '';
$selected_semester = $_GET['semester'] ?? '';

// Prepare query
$query = "SELECT s.*, b.name AS branch_name FROM subjects s JOIN branches b ON s.branch_id = b.id WHERE 1=1";
$params = [];

if ($selected_branch) {
    $query .= " AND s.branch_id = ?";
    $params[] = $selected_branch;
}
if ($selected_semester !== '') {
    $query .= " AND s.semester = ?";
    $params[] = $selected_semester;
}

$query .= " ORDER BY s.branch_id, s.semester, s.code";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$subjects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
<div class="container mt-4">
    <h3 class="mb-4">Subjects</h3>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Branch</label>
            <select name="branch_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Branches</option>
                <?php foreach ($branches as $branch): ?>
                    <option value="<?= $branch['id'] ?>" <?= $branch['id'] == $selected_branch ? 'selected' : '' ?>>
                        <?= htmlspecialchars($branch['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Semester</label>
            <select name="semester" class="form-select" onchange="this.form.submit()">
                <option value="">All Semesters</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?= $i ?>" <?= $i == $selected_semester ? 'selected' : '' ?>>Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <a href="view_subject.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <!-- Subjects Table -->
    <?php if ($subjects): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Branch</th>
                    <th>Semester</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?= htmlspecialchars($subject['branch_name']) ?></td>
                        <td><?= $subject['semester'] ?></td>
                        <td><?= $subject['code'] ?></td>
                        <td><?= htmlspecialchars($subject['name']) ?></td>
                        <td><?= $subject['type'] ?></td>
                        <td>
                            <a href="edit_subject.php?id=<?= $subject['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_subject.php?id=<?= $subject['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No subjects found.</div>
    <?php endif; ?>
</div>
</body>
</html>
