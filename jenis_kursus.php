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
    $gambar_folder = 'assets/' . $gambar;  // Path gambar

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

// Ambil kata kunci dari parameter URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modifikasi query untuk menyertakan pencarian
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM jenis_kursus WHERE nama LIKE ? OR deskripsi LIKE ?");
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Query default jika tidak ada pencarian
    $result = $conn->query("SELECT * FROM jenis_kursus");
}
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
                    <?php endif; ?>

                    <!-- Navigasi Jenis Kursus dan Logout (Tidak ada Profil untuk Admin) -->
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <?php if ($_SESSION['role'] !== 'admin'): ?>
                        <li><a href="profil.php">Profil</a></li>
                    <?php endif; ?>
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

            <!-- Form Pencarian -->
            <form action="jenis_kursus.php" method="GET">
                <input type="text" name="search" placeholder="Cari kursus..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit">Cari</button>
            </form>
            
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

            <!-- Daftar Kursus dalam Bentuk Card -->
            <h3>Daftar Kursus</h3>
            <div class="card-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($kursus = $result->fetch_assoc()): ?>
                        <div class="card">
                            <img src="assets/<?php echo $kursus['gambar']; ?>" alt="<?php echo $kursus['nama']; ?>" class="card-img">
                            <div class="card-content">
                                <h4 class="card-title"><?php echo $kursus['nama']; ?></h4>
                                <p class="card-description"><?php echo $kursus['deskripsi']; ?></p>
                                <p class="card-price">Harga: <?php echo "Rp " . number_format($kursus['harga'], 0, ',', '.'); ?></p>
                                <p class="card-duration">Durasi: <?php echo $kursus['durasi']; ?> Bulan</p>
                                <div class="card-actions">
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <a href="edit_kursus.php?id=<?php echo $kursus['id']; ?>" class="btn edit">Edit</a>
                                        <a href="delete_kursus.php?id=<?php echo $kursus['id']; ?>" class="btn delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">Delete</a>
                                    <?php else: ?>
                                        <a href="pembayaran_sederhana.php?kursus_id=<?php echo $kursus['id']; ?>" class="btn">Bayar Sekarang</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada kursus yang ditemukan untuk kata kunci "<strong><?php echo htmlspecialchars($search); ?></strong>".</p>
                <?php endif; ?>
            </div>
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
