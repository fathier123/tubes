<?php
session_start();
require_once 'db.php';

// Pastikan hanya pengguna dengan role 'user' yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];  // Asumsi user_id disimpan dalam session saat login
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['message'] = "Pengguna tidak ditemukan!";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header>
    <div class="container">
        <h1>Profil Pengguna - Kursus Musik Profesional</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="jenis_kursus.php">Jenis Kursus</a></li> <!-- Navigasi ke Jenis Kursus -->
                <li><a href="profil.php">Profil</a></li> <!-- Navigasi ke Profil Pengguna -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>


    <main>
        <section class="profile">
            <div class="container">
                <h2>Detail Profil Pengguna</h2>

                <!-- Tampilkan pesan error/sukses -->
                <?php if (isset($_SESSION['message'])): ?>
                    <p class="error"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
                <?php endif; ?>

                <!-- Tabel Profil Pengguna -->
                <table>
                    <tr>
                        <th>Nama Pengguna</th>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Foto Profil</th>
                        <td>
                            <?php if (!empty($user['profile_picture'])): ?>
                                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Foto Profil">
                            <?php else: ?>
                                Tidak ada foto profil.
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <!-- Link untuk mengedit profil -->
                <a href="edit_profil.php" class="btn">Edit Profil</a>
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
