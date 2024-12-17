<?php
session_start();
require_once 'db.php';

// Pastikan hanya user yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$data_id = intval($_GET['id']);

// Hapus data utama berdasarkan ID dan user_id
$delete_query = "DELETE FROM data_utama WHERE id = $data_id AND user_id = $user_id";
if ($conn->query($delete_query)) {
    header("Location: user_dashboard.php");
    exit;
} else {
    die("Terjadi kesalahan saat menghapus data.");
}
?>
