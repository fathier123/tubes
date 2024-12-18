<?php
session_start();
require_once 'db.php';

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Query data pengguna berdasarkan role
if ($role === 'admin') {
    $query = "SELECT id, username, email, role, created_at, payment_status FROM users";  // Removed 'profile_picture'
    $result = $conn->query($query);
} else {
    $query = "SELECT nama, email FROM users WHERE id = ?";  // Removed 'profile_picture'
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($nama, $email);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kursus Musik Profesional</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Kursus Musik Profesional</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                        <li><a href="jenis_kursus.php">Jenis Kursus</a></li>
                    <?php else: ?>
                        <li><a href="profil.php">Profil</a></li>
                        <li><a href="user_dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if ($role === 'admin'): ?>
                <section class="crud">
                    <h2>Data Pengguna</h2>
                    <a href="add_user.php" class="btn">Tambah Pengguna</a>

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo ucfirst($user['role']); ?></td>
                                    <td><?php echo ucfirst($user['payment_status']); ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="edit">Edit</a>
                                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
            <?php else: ?>
                <section class="profil-container">
                    <h2>Profil Saya</h2>
                    <div class="profil-details">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <a href="edit_profil.php" class="btn">Edit Profil</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Kursus Musik Profesional. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>
</html>

<?php
if ($role === 'admin') {
    $result->close();
}
$conn->close();
?>
