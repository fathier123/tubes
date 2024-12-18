<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$nota = []; // Array untuk menyimpan data nota

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
        $message = "Kursus tidak ditemukan!";
    } else {
        // Verifikasi apakah jumlah bayar sesuai dengan harga kursus
        if ($jumlah_bayar == $kursus['harga']) {
            // Simpan status pembayaran ke database
            $stmt = $conn->prepare("UPDATE users SET payment_status = 'paid' WHERE id = ?");
            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();

            // Simpan data untuk nota
            $nota = [
                'kursus' => $kursus['nama'],
                'harga' => $kursus['harga'],
                'nomor_transaksi' => $nomor_transaksi,
                'tanggal' => date('d-m-Y H:i:s')
            ];

            $message = "Pembayaran berhasil!";
        } else {
            $message = "Jumlah pembayaran tidak sesuai, silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <h1>Platform Kursus Online</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="logout.php">Keluar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="auth">
                <p class="<?= !empty($nota) ? 'success' : 'error' ?>"><?= $message ?></p>
            </div>
        <?php endif; ?>

        <!-- Tampilkan Pesan Mencolok dengan warna identik dengan keberhasilan pembayaran -->
        <div class="contact-message" style="background-color: #d4edda; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; color: #155724; border-radius: 5px;">
            <p><strong>Silakan hubungi nomor ini untuk bantuan lebih lanjut:</strong> <a href="tel:+62812345678" style="color: #155724; text-decoration: none;">0812345678</a></p>
        </div>

        <!-- Form jika nota belum ada -->
        <?php if (empty($nota)): ?>
            <form action="" method="POST" class="auth">
                <label for="kursus_id">Pilih ID Kursus:</label>
                <input type="number" id="kursus_id" name="kursus_id" required placeholder="Masukkan ID Kursus">

                <label for="nomor_transaksi">Nomor Transaksi:</label>
                <input type="text" id="nomor_transaksi" name="nomor_transaksi" required placeholder="Nomor Transaksi">

                <label for="jumlah_bayar">Jumlah Pembayaran:</label>
                <input type="number" id="jumlah_bayar" name="jumlah_bayar" required placeholder="Jumlah Pembayaran">

                <button type="submit">Konfirmasi Pembayaran</button>
            </form>
        <?php else: ?>
            <!-- Tampilkan Nota -->
            <div class="nota">
                <h3>Bukti Pembayaran</h3>
                <p><strong>Kursus:</strong> <?= $nota['kursus'] ?></p>
                <p><strong>Harga:</strong> Rp<?= number_format($nota['harga'], 0, ',', '.') ?></p>
                <p><strong>Nomor Transaksi:</strong> <?= $nota['nomor_transaksi'] ?></p>
                <p><strong>Tanggal:</strong> <?= $nota['tanggal'] ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>


    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Platform Kursus Online. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
