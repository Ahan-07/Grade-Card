<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

$branches = $pdo->query("SELECT * FROM branches")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO subjects (branch_id, semester, code, name, type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['branch_id'],
        $_POST['semester'],
        trim($_POST['code']),
        trim($_POST['name']),
        $_POST['type']
    ]);
    $success = "Subject added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Subject</title>
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
    <h3 class="text-center mb-4">Add Subject</h3>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Branch</label>
            <select name="branch_id" class="form-select" required>
                <option value="">Select Branch</option>
                <?php foreach ($branches as $b): ?>
                    <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Semester</label>
            <input type="number" name="semester" class="form-control" required min="1" max="6">
        </div>
        <div class="mb-3">
            <label>Subject Code</label>
            <input type="text" name="code" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Subject Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-select" required>
                <option value="THEORY">THEORY</option>
                <option value="PRACTICAL">PRACTICAL</option>
            </select>
        </div>
        <button class="btn btn-success w-100">Add Subject</button>
    </form>
</div>
</body>
</html>
