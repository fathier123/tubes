<?php
session_start();
require_once 'db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id = $id");

// Tutup koneksi setelah operasi selesai
$conn->close();

$_SESSION['message'];
header("Location: admin_dashboard.php");  // Halaman akan dialihkan ke admin_dashboard.php
exit;
?>
