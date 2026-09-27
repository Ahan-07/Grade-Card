<?php
require '../config.php';
$error = '';
$success = '';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $code = strtoupper(trim($_POST['code']));

    if ($name && $code) {
        // Check for duplicate code
        $check = $pdo->prepare("SELECT id FROM branches WHERE code = ?");
        $check->execute([$code]);

        if ($check->rowCount() > 0) {
            $error = "Branch code already exists!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO branches (name, code) VALUES (?, ?)");
            $stmt->execute([$name, $code]);
            // Redirect to view_branches.php after successful insert
            header("Location: view_branches.php");
            exit();
        }
    } else {
        $error = "Both name and code are required.";
    }
}


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
    <title>Add Branch</title>
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

<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Add New Branch</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Branch Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Branch Code (Short)</label>
            <input type="text" name="code" class="form-control text-uppercase" maxlength="10" required>
        </div>
        <div class="d-grid mb-2">
            <button type="submit" class="btn btn-success">Add Branch</button>
        </div>
        <div class="d-grid">
            <a href="view_branches.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
