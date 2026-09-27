<?php
require '../config.php'; // DB connection
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
// Fetch all students
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        img.photo-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
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
    <h3 class="text-center mb-4 text-success">Registered Students</h3>

    <?php if (count($students) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>Category</th>
                        <th>Course</th>
                        <th>Roll No</th>
                        <th>Enrollment No</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><img src="<?= htmlspecialchars($student['photo']) ?>" class="photo-thumb" alt="photo"></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['father_name']) ?></td>
                            <td><?= htmlspecialchars($student['category']) ?></td>
                             <td><?= htmlspecialchars($student['course']) ?></td>
                            <td><?= htmlspecialchars($student['roll_no']) ?></td>
                            <td><?= htmlspecialchars($student['enrolment_no']) ?></td>
                            <td>
                                <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No student records found.</div>
    <?php endif; ?>
</div>
</body>
</html>
