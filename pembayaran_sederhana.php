<?php
session_start();

// Jika pengguna belum login, arahkan ke login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$kursus_id = $_GET['kursus_id'];
require_once 'db.php';
$stmt = $conn->prepare("SELECT * FROM jenis_kursus WHERE id = ?");
$stmt->bind_param("i", $kursus_id);
$stmt->execute();
$result = $stmt->get_result();
$kursus = $result->fetch_assoc();

if (!$kursus) {
    echo "Kursus tidak ditemukan!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Kursus - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Detail Pembayaran Kursus</h2>
            <h3><?php echo $kursus['nama']; ?></h3>
            <p><?php echo $kursus['deskripsi']; ?></p>
            <p>Harga: Rp <?php echo number_format($kursus['harga'], 0, ',', '.'); ?></p>

            <h3>Pilih Metode Pembayaran</h3>

            <!-- Pembayaran Manual (Transfer Bank/Tunai) -->
            <p>Untuk melakukan pembayaran, silakan transfer ke rekening bank berikut:</p>
            <ul>
                <li>Bank: BCA</li>
                <li>No. Rekening: 123-456-789</li>
                <li>Atas Nama: Kursus Musik Profesional</li>
            </ul>

            <p>Setelah melakukan pembayaran, harap konfirmasi melalui form berikut:</p>

            <form action="konfirmasi_pembayaran.php" method="POST">
                <input type="hidden" name="kursus_id" value="<?php echo $kursus['id']; ?>">
                <label for="nomor_transaksi">Nomor Transaksi:</label>
                <input type="text" name="nomor_transaksi" required>

                <label for="jumlah_bayar">Jumlah Pembayaran:</label>
                <input type="number" name="jumlah_bayar" required>

                <button type="submit" class="btn">Konfirmasi Pembayaran</button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Kursus Musik Profesional. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>
</html>
