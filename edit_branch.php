<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

$id = $_GET['id'];
$subject = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$subject->execute([$id]);
$data = $subject->fetch();
$branches = $pdo->query("SELECT * FROM branches")->fetchAll();

if (!$data) {
    echo "<div class='alert alert-danger mt-5 text-center'>Subject not found.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE subjects SET branch_id = ?, semester = ?, code = ?, name = ?, type = ? WHERE id = ?");
    $stmt->execute([
        $_POST['branch_id'],
        $_POST['semester'],
        $_POST['code'],
        $_POST['name'],
        $_POST['type'],
        $id
    ]);
    header('Location: view_subjects.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Subject</title>
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

<div class="container mt-5" style="max-width:600px">
    <h3 class="text-center mb-4">Edit Subject</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Branch</label>
            <select name="branch_id" class="form-select" required>
                <?php foreach ($branches as $b): ?>
                    <option value="<?= $b['id'] ?>" <?= $b['id'] == $data['branch_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Semester</label>
            <input type="number" name="semester" class="form-control" value="<?= $data['semester'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Subject Code</label>
            <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($data['code']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Subject Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-select">
                <option value="THEORY" <?= $data['type'] == 'THEORY' ? 'selected' : '' ?>>THEORY</option>
                <option value="PRACTICAL" <?= $data['type'] == 'PRACTICAL' ? 'selected' : '' ?>>PRACTICAL</option>
            </select>
        </div>
        <button class="btn btn-primary w-100">Update Subject</button>
    </form>
</div>
</body>
</html>
