<?php
session_start();
require_once 'db.php';

// Cek apakah pengguna sudah login dan memiliki hak akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Jika tidak admin, alihkan ke halaman login
    exit();
}

// Periksa apakah ada ID yang dikirimkan melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama gambar dari database berdasarkan ID
    $stmt = $conn->prepare("SELECT gambar FROM jenis_kursus WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($gambar);
    $stmt->fetch();
    $stmt->close();

    // Hapus gambar dari folder assets
    if ($gambar) {
        $gambar_path = 'assets/' . $gambar;
        if (file_exists($gambar_path)) {
            unlink($gambar_path); // Menghapus gambar dari folder
        }
    }

    // Hapus data kursus dari database
    $stmt = $conn->prepare("DELETE FROM jenis_kursus WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Kursus berhasil dihapus'); window.location.href = 'jenis_kursus.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kursus'); window.location.href = 'jenis_kursus.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('ID kursus tidak ditemukan'); window.location.href = 'jenis_kursus.php';</script>";
}

$conn->close();
?>
