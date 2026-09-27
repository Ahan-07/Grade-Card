<?php
require 'config.php';

// Fetch branches for dropdown
$branches = $pdo->query("SELECT id, name FROM branches")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $father   = $_POST['father_name'];
    $category = $_POST['category'];
    $roll     = $_POST['roll_no'];
    $enroll   = $_POST['enrolment_no'];
    $branch_id = $_POST['branch_id'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Get branch name for course column
    $branch_name = '';
    foreach ($branches as $branch) {
        if ($branch['id'] == $branch_id) {
            $branch_name = $branch['name'];
            break;
        }
    }

    // Handle Image Upload
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $photo_name = $_FILES['photo']['name'];
    $photo_tmp  = $_FILES['photo']['tmp_name'];
    $ext        = pathinfo($photo_name, PATHINFO_EXTENSION);
    $unique_name = uniqid("stu_") . "." . $ext;
    $photo_path = $upload_dir . $unique_name;

    move_uploaded_file($photo_tmp, $photo_path);

    // Insert into DB (add branch_id and course)
    $stmt = $pdo->prepare("INSERT INTO students (name, father_name, category, roll_no, enrolment_no, branch_id, course, email, password, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $father, $category, $roll, $enroll, $branch_id, $branch_name, $email, $password, $photo_path]);

    echo "<div class='alert alert-success text-center'>Student Registered Successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Student Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5 p-4 bg-white shadow rounded" style="max-width: 700px;">
    <h3 class="text-center text-success mb-4">Jamia Millia Islamia</h3>
    <h4 class="text-center mb-3">Student Registration Form</h4>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Student Name</label>
        <input type="text" class="form-control" name="name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Father's Name</label>
        <input type="text" class="form-control" name="father_name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-select" name="category" required>
          <option value="">Select Category</option>
          <option>Regular</option>
          <option>Private</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Roll Number</label>
        <input type="text" class="form-control" name="roll_no" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Enrolment Number</label>
        <input type="text" class="form-control" name="enrolment_no" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Branch</label>
        <select class="form-select" name="branch_id" required>
          <option value="">Select Branch</option>
          <?php foreach ($branches as $branch): ?>
            <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload Photo</label>
        <input type="file" class="form-control" name="photo" accept="image/*" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-success">Register Student</button>
      </div>
    </form>
  </div>
</body>
</html>
