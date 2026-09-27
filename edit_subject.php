<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE subjects SET semester = ?, code = ?, name = ?, type = ? WHERE id = ?");
    $stmt->execute([
        $_POST['semester'],
        $_POST['code'],
        $_POST['name'],
        $_POST['type'],
        $id
    ]);
    header('Location: index.php');
    exit();
}
$subject = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$subject->execute([$id]);
$sub = $subject->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
<div class="container mt-5" style="max-width: 600px;">
    <h3 class="mb-4 text-center">Edit Subject</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Semester</label>
            <input type="number" name="semester" value="<?= htmlspecialchars($sub['semester']) ?>" class="form-control" min="1" max="12" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject Code</label>
            <input type="text" name="code" value="<?= htmlspecialchars($sub['code']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($sub['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select" required>
                <option value="THEORY" <?= $sub['type']=='THEORY' ? 'selected' : '' ?>>THEORY</option>
                <option value="PRACTICAL" <?= $sub['type']=='PRACTICAL' ? 'selected' : '' ?>>PRACTICAL</option>
            </select>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary" type="submit">Update Subject</button>
        </div>
    </form>
</div>
</body>
</html>
