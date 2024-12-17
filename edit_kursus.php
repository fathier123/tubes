<?php
session_start();
require_once 'db.php';

// Cek apakah pengguna login dan memiliki hak akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil ID kursus dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kursus dari database
    $stmt = $conn->prepare("SELECT * FROM jenis_kursus WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $kursus = $result->fetch_assoc();

    // Jika kursus tidak ditemukan, alihkan ke halaman daftar kursus
    if (!$kursus) {
        header('Location: jenis_kursus.php');
        exit();
    }
} else {
    header('Location: jenis_kursus.php');
    exit();
}

// Proses form jika data dikirimkan oleh admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $durasi = $_POST['durasi'];

    // Proses upload gambar (jika ada gambar baru)
    $gambar = $_FILES['gambar']['name'];
    if ($gambar) {
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_folder = 'assets/' . $gambar;

        // Pindahkan gambar ke folder
        if (!move_uploaded_file($gambar_tmp, $gambar_folder)) {
            echo "<script>alert('Gagal meng-upload gambar. Periksa izin folder.');</script>";
        }
    } else {
        $gambar = $kursus['gambar']; // Gunakan gambar lama jika tidak ada gambar baru
    }

    // Update data kursus di database
    $stmt = $conn->prepare("UPDATE jenis_kursus SET nama = ?, deskripsi = ?, harga = ?, durasi = ?, gambar = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $nama, $deskripsi, $harga, $durasi, $gambar, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); window.location.href = 'jenis_kursus.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kursus - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Kursus Musik Profesional</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="user_dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
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
            <h2>Edit Kursus</h2>

            <!-- Form Edit Kursus -->
            <form action="edit_kursus.php?id=<?php echo $kursus['id']; ?>" method="POST" enctype="multipart/form-data">
                <label for="nama">Nama Kursus</label>
                <input type="text" id="nama" name="nama" value="<?php echo $kursus['nama']; ?>" required>

                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?php echo $kursus['deskripsi']; ?></textarea>

                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" value="<?php echo $kursus['harga']; ?>" required>

                <label for="durasi">Durasi (bulan)</label>
                <input type="number" id="durasi" name="durasi" value="<?php echo $kursus['durasi']; ?>" required>

                <label for="gambar">Gambar (Opsional)</label>
                <input type="file" id="gambar" name="gambar" accept="image/*">

                <button type="submit">Perbarui Kursus</button>
            </form>
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
