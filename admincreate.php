<?php
require '../config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin/admin_login.php");
    exit();
}
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT); // secure hash

$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Admin user created.";
?>