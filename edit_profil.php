<?php
session_start();
require_once 'db.php';

// Pastikan hanya pengguna dengan role 'user' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");  // Arahkan ke halaman login jika bukan pengguna
    exit;
}

// Ambil ID pengguna dari session (karena profil yang diakses milik pengguna yang sedang login)
$id = $_SESSION['user_id'];  // Asumsi user_id disimpan dalam session saat login

// Ambil data pengguna dari database menggunakan prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['message'] = "Pengguna tidak ditemukan!";
    header("Location: login.php");  // Arahkan ke login jika pengguna tidak ditemukan
    exit;
}

// Proses pembaruan data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $profile_picture = $user['profile_picture']; // Gunakan foto lama jika tidak ada unggahan baru

    // Mengelola upload foto profil
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "assets/";  // Direktori penyimpanan file
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file (hanya gambar)
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check === false) {
            $_SESSION['message'] = "File yang diunggah bukan gambar!";
        } elseif ($_FILES['profile_picture']['size'] > 500000) {
            $_SESSION['message'] = "Ukuran file terlalu besar (maksimal 500KB)!";
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $_SESSION['message'] = "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan!";
        } else {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $_SESSION['message'] = "Gagal mengunggah file gambar!";
            }
        }
    }

    if (!isset($_SESSION['message'])) { // Jika tidak ada error
        // Perbarui data pengguna
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $profile_picture, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Data pengguna berhasil diperbarui!";
            header("Location: profil.php");
            exit;
        } else {
            $_SESSION['message'] = "Gagal memperbarui data pengguna!";
        }
    }
}

// Fungsi untuk menghapus akun (opsional)
if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Akun berhasil dihapus!";
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['message'] = "Gagal menghapus akun!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Edit Profil - Kursus Musik Profesional</h1>
    </header>

    <main>
        <section class="profile">
            <h2>Detail Profil Pengguna</h2>

            <!-- Tampilkan pesan error/sukses -->
            <?php if (isset($_SESSION['message'])): ?>
                <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="profile_picture">Foto Profil</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

                <!-- Menampilkan foto profil lama -->
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Foto Profil" width="100">
                <?php endif; ?>

                <button type="submit" class="btn">Perbarui Profil</button>
            </form>

            <form method="POST">
                <button type="submit" name="delete" class="btn delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">Hapus Akun</button>
            </form>
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
