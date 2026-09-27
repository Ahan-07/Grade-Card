<?php
require '../config.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger mt-5 text-center'>Invalid subject ID.</div>";
    exit();
}
$id = $_GET['id'];
$pdo->prepare("DELETE FROM subjects WHERE id = ?")->execute([$id]);
header('Location: view_subjects.php');
exit();
