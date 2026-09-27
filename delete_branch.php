<?php
require '../config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger mt-5 text-center'>Invalid branch ID.</div>";
    exit();
}

$id = (int) $_GET['id'];

// Optional: check if branch exists
$stmt = $pdo->prepare("SELECT * FROM branches WHERE id = ?");
$stmt->execute([$id]);
$branch = $stmt->fetch();

if (!$branch) {
    echo "<div class='alert alert-danger mt-5 text-center'>Branch not found.</div>";
    exit();
}

// Delete branch
$delete = $pdo->prepare("DELETE FROM branches WHERE id = ?");
$delete->execute([$id]);

// Redirect
header("Location: view_branches.php");
exit();
