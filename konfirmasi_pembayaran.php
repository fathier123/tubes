<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $kursus_id = $_POST['kursus_id'];
    $nomor_transaksi = $_POST['nomor_transaksi'];
    $jumlah_bayar = $_POST['jumlah_bayar'];

    require_once 'db.php';

    // Query untuk mengambil detail kursus
    $stmt = $conn->prepare("SELECT * FROM jenis_kursus WHERE id = ?");
    $stmt->bind_param("i", $kursus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $kursus = $result->fetch_assoc();

    if (!$kursus) {
        echo "Kursus tidak ditemukan!";
        exit();
    }

    // Verifikasi apakah jumlah bayar sesuai dengan harga kursus
    if ($jumlah_bayar == $kursus['harga']) {
        // Simpan status pembayaran ke database
        $stmt = $conn->prepare("UPDATE users SET payment_status = 'paid' WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();

        echo "Pembayaran berhasil! Anda sudah terdaftar untuk kursus.";
        // Redirect atau beri akses ke kursus
    } else {
        echo "Jumlah pembayaran tidak sesuai, silakan coba lagi.";
    }
}
?>
