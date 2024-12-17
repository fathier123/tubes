<?php
session_start();
require_once 'db.php';

// Cek apakah pengguna login dan memiliki hak akses
if (!isset($_SESSION['role'])) {
    header('Location: login.php'); // Jika belum login, alihkan ke halaman login
    exit();
}

// Proses form jika data dikirimkan oleh admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $durasi = $_POST['durasi'];

    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_folder = 'assets/' . $gambar;  // Perbaiki path gambar (langsung di folder assets)

    // Pindahkan gambar ke folder
    if (!move_uploaded_file($gambar_tmp, $gambar_folder)) {
        echo "<script>alert('Gagal meng-upload gambar. Periksa izin folder.');</script>";
    }

    // Insert data ke dalam tabel jenis_kursus
    $stmt = $conn->prepare("INSERT INTO jenis_kursus (nama, deskripsi, harga, durasi, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $nama, $deskripsi, $harga, $durasi, $gambar);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');</script>";
    }
    $stmt->close();
}

// Ambil data jenis kursus dari database
$result = $conn->query("SELECT * FROM jenis_kursus");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Kursus - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Kursus Musik Profesional</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>

                <!-- Cek apakah pengguna sudah login dan role-nya -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Jika role adalah admin, tampilkan Admin Dashboard -->
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <!-- Jika user biasa, tampilkan User Dashboard -->
                        <li><a href="user_dashboard.php">Dashboard</a></li>
                    <?php endif; ?>

                    <!-- Navigasi Jenis Kursus dan Logout -->
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <!-- Jika belum login, tampilkan link login dan register -->
                    <li><a href="login.php">Masuk</a></li>
                    <li><a href="register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<main>
    <section class="jenis-kursus">
        <div class="container">
            <h2>Jenis Kursus yang Tersedia</h2>
            
            <!-- Form Tambah Kursus (Hanya Tampil untuk Admin) -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <h3>Tambah Kursus Baru</h3>
                <form action="jenis_kursus.php" method="POST" enctype="multipart/form-data">
                    <label for="nama">Nama Kursus</label>
                    <input type="text" id="nama" name="nama" required>

                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" required></textarea>

                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" required>

                    <label for="durasi">Durasi (bulan)</label>
                    <input type="number" id="durasi" name="durasi" required>

                    <label for="gambar">Gambar</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" required>

                    <button type="submit">Tambah Kursus</button>
                </form>
            <?php endif; ?>

            <!-- Tabel Kursus -->
            <h3>Daftar Kursus</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Gambar</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <th>Aksi</th>
                        <?php else: ?>
                            <th>Aksi</th> <!-- Tombol pembayaran untuk user -->
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($kursus = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $kursus['nama']; ?></td>
                            <td><img src="assets/<?php echo $kursus['gambar']; ?>" alt="<?php echo $kursus['nama']; ?>" width="50"></td>
                            <td><?php echo $kursus['deskripsi']; ?></td>
                            <td><?php echo "Rp " . number_format($kursus['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $kursus['durasi']; ?> Bulan</td>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <a href="edit_kursus.php?id=<?php echo $kursus['id']; ?>" class="edit">Edit</a>
                                    <a href="delete_kursus.php?id=<?php echo $kursus['id']; ?>" class="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">Delete</a>
                                </td>
                            <?php else: ?>
                                <!-- Tombol bayar untuk user -->
                                <td>
                                    <a href="pembayaran_sederhana.php?kursus_id=<?php echo $kursus['id']; ?>" class="btn">Bayar Sekarang</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Kursus Musik Profesional</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
