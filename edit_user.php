<?php
session_start();
require_once 'db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID pengguna dari URL secara aman
$id = intval($_GET['id']);

// Pastikan ID valid
if (!$id) {
    $_SESSION['message'] = "ID pengguna tidak valid!";
    header("Location: admin_dashboard.php");
    exit;
}

// Ambil data pengguna dari database menggunakan prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['message'] = "Pengguna tidak ditemukan!";
    header("Location: admin_dashboard.php");
    exit;
}

// Proses pembaruan data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Perbarui data pengguna tanpa mengelola foto profil
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data pengguna berhasil diperbarui!";
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION['message'] = "Gagal memperbarui pengguna!";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - Admin</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <h1>Edit Pengguna - Kursus Musik Profesional</h1>
    </header>

    <main>
        <section class="auth">
            <h2>Edit Data Pengguna</h2>

            <!-- Tampilkan pesan error/sukses -->
            <?php if (isset($_SESSION['message'])): ?>
                <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="username">Nama Pengguna</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>

                <button type="submit" class="btn">Perbarui Pengguna</button>
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
