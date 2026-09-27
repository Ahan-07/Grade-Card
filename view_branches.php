<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
$branches = $pdo->query("SELECT * FROM branches ORDER BY id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Branches</title>
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
  <div class="container mt-5">
    <h3 class="text-center mb-4">All Branches</h3>

    <a href="add_branch.php" class="btn btn-success mb-3">+ Add New Branch</a>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Branch Name</th>
          <th>Branch Code</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($branches as $i => $branch): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($branch['name']) ?></td>
            <td><?= htmlspecialchars($branch['code']) ?></td>
            <td>
              <a href="edit_branch.php?id=<?= $branch['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete_branch.php?id=<?= $branch['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (count($branches) === 0): ?>
          <tr><td colspan="3" class="text-center text-muted">No branches found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
