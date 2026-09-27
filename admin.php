<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }
    .card:hover {
      transform: scale(1.02);
    }
    .icon-box {
      font-size: 2rem;
      padding: 20px;
      border-radius: 50%;
      background: rgba(255,255,255,0.3);
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .nav-link {
      color: white;
    }
    .nav-link:hover {
      color: #ffd700;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="admin.php">Admin Panel</a>
      <div class="d-flex">
        <ul>
            <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row text-center mb-4">
      <h2 class="text-white">Welcome, Admin</h2>
      <p class="text-white">Manage branches, subjects, and student data</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-white bg-success">
          <div class="card-body text-center">
            <div class="icon-box mb-3"><i class="bi bi-building"></i></div>
            <h5 class="card-title">Manage Branches</h5>
            <a href="admin/view_branches.php" class="btn btn-light btn-sm">View/Edit/Add</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-white bg-info">
          <div class="card-body text-center">
            <div class="icon-box mb-3"><i class="bi bi-journal-code"></i></div>
            <h5 class="card-title">Manage Subjects</h5>
            <a href="admin/add_subject.php" class="btn btn-light btn-sm">Add Subject</a>
            <a href="admin/view_subject.php" class="btn btn-outline-light btn-sm">View/Edit Subject</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-white bg-warning">
          <div class="card-body text-center">
            <div class="icon-box mb-3"><i class="bi bi-people"></i></div>
            <h5 class="card-title">Registered Students</h5>
            <a href="admin/view_student.php" class="btn btn-light btn-sm">View Students</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-white bg-danger">
          <div class="card-body text-center">
            <div class="icon-box mb-3"><i class="bi bi-envelope"></i></div>
            <h5 class="card-title">Contact Messages</h5>
            <a href="admin/view_contact.php" class="btn btn-light btn-sm">View Messages</a>
          </div>
        </div>
      </div>

<div class="col-md-4">
        <div class="card text-white bg-danger">
          <div class="card-body text-center">
            <div class="icon-box mb-3"><i class="bi bi-envelope"></i></div>
            <h5 class="card-title">Generate Grade Cards</h5>
            <a href="admin/generate_gradecard.php" class="btn btn-light btn-sm">Generate</a>
          </div>
        </div>
      </div>


<div class="col-md-4">
  <div class="card text-white bg-primary">
    <div class="card-body text-center">
      <div class="icon-box mb-3"><i class="bi bi-table"></i></div>
      <h5 class="card-title">View Grade Cards</h5>
      <a href="admin/view_gradecards.php" class="btn btn-light btn-sm">View Grade Card</a>
    </div>
  </div>
</div>

    </div>
  </div>

</body>
</html>
