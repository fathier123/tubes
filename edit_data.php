<?php
session_start();
require_once 'db.php';

// Pastikan hanya user yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Ambil ID data utama dari URL
$data_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

// Pastikan data milik pengguna ini ada
$query = "SELECT * FROM data_utama WHERE id = $data_id AND user_id = $user_id";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die("Data tidak ditemukan atau Anda tidak memiliki izin.");
}

$data = $result->fetch_assoc();

// Jika formulir dikirim, proses pengubahan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_data = trim($_POST['nama_data']);
    $deskripsi = trim($_POST['deskripsi']);

    // Update data utama di database
    $update_query = "UPDATE data_utama SET nama_data = ?, deskripsi = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssii", $nama_data, $deskripsi, $data_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil diperbarui!";
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Terjadi kesalahan saat memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Utama</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Edit Data Utama</h1>
        <nav>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Perbarui Data Utama Anda</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="nama_data">Nama Data:</label>
                <input type="text" id="nama_data" name="nama_data" value="<?php echo $data['nama_data']; ?>" required>

                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required><?php echo $data['deskripsi']; ?></textarea>

                <button type="submit" class="btn">Simpan Perubahan</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Kursus Musik Profesional</p>
    </footer>
</body>
</html>
